/**
 *
 * Main App Component
 *
 */
"use strict";
/* global app, mediaSettings, Dropzone, trans, launchToast */

// Disable dropzone uploader auto loading globally as we will instantiate it manually
Dropzone.autoDiscover = false;

var FileUpload = {

    attachaments: [],
    myDropzone : null,
    isLoading: false,
    isTranscodingVideo: false,
    state: {},

    /**
     * Instantiates the media uploader plugin
     * @param selector
     * @param url
     */
    initDropZone:function (selector,url, isChunkUpload = false, extraOptions = {}) {

        // Prepping chunk uploads, if enabled by admin
        let chunkSize = 1024;
        if(isChunkUpload){
            chunkSize = mediaSettings.upload_chunk_size * 1000000;
            url = url.replace('/upload/','/uploadChunked/');
        }

        FileUpload.myDropzone = new Dropzone(selector, {
            paramName: "file", // The name that will be used to transfer the file
            previewTemplate: document.querySelector('#tpl').innerHTML,
            url: app.baseUrl + url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            clickable:['.file-upload-button'],
            previewsContainer: ".dropzone-previews",
            maxFilesize: mediaSettings.max_file_upload_size, // MB
            maxFiles: extraOptions.maxFiles || null,
            addRemoveLinks: true,
            dictRemoveFile: "x",
            acceptedFiles: mediaSettings.allowed_file_extensions,
            chunking: isChunkUpload,
            forceChunking: isChunkUpload,
            chunkSize: chunkSize,
            parallelChunkUploads: false,
            retryChunks: false,
            retryChunksLimit: 2,
            dictDefaultMessage: trans("Drop files here to upload"),
            // dictDefaultMessage: false,

            init: function() {
                // FileUpload.attachaments
                FileUpload.attachaments.map((element)=>{
                    var mockFile = { name: element.attachmentID, upload:{attachmentID:element.attachmentID} , type:element.type, thumbnail: element.thumbnail};
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, element.thumbnail);
                    this.emit("complete", mockFile);
                    FileUpload.updatePreviewElement(mockFile, false, element);
                });
                FileUpload.updatePreviewsState();
                var _this = this;
                $(".draft-clear-button").on("click", function() {
                    _this.removeAllFiles(true);
                });
            },
            dictInvalidFileType: trans("You can't upload files of this type."),
        });

        FileUpload.myDropzone.on("addedfile", file => {
            FileUpload.updatePreviewElement(file, true);
            FileUpload.updatePreviewsState();
            FileUpload.isLoading = true;
        });

        FileUpload.myDropzone.on("uploadprogress", (file, progress) => {
            if(progress >= 100 && FileUpload.requiresServerVideoProcessing(file)){
                FileUpload.showVideoEncodingState(file);
                FileUpload.isTranscodingVideo = true;
            }
        });

        FileUpload.myDropzone.on("success", (file, response) => {
            var hasCoconutJob = typeof response.coconut_id !== 'undefined' && response.coconut_id !== null;

            if(hasCoconutJob){
                FileUpload.isTranscodingVideo = true;
            }
            if(response.success){
                file.upload.attachmentID = response.attachmentID;
                FileUpload.attachaments.push({attachmentID: response.attachmentID, path: response.path, type:response.type, thumbnail:response.thumbnail, length: response.length});

                if(FileUpload.isVideoFile(file) && !FileUpload.isAudioFile(file, response) && hasCoconutJob){
                    FileUpload.showVideoEncodingState(file);
                }
                else{
                    switch (FileUpload.getPreviewType(file, response)) {
                    case 'video':
                    case 'audio':
                        FileUpload.updatePreviewElement(file, false, response);
                        break;
                    }
                }
            }
            if(FileUpload.isVideoFile(file) && !FileUpload.isAudioFile(file, response) && !hasCoconutJob){
                FileUpload.isTranscodingVideo = false;
            }
            FileUpload.isLoading = false;
        });

        FileUpload.myDropzone.on("removedfile", function(file) {
            FileUpload.revokeLocalPreviewUrl(file);
            FileUpload.attachaments = FileUpload.attachaments.filter((attachment)=>{
                if(attachment.attachmentID !== file.upload.attachmentID){
                    return attachment;
                }
                else{
                    FileUpload.removeAttachment(attachment);
                }
            });
            FileUpload.updatePreviewsState();
        });

        FileUpload.myDropzone.on("error", (file, errorMessage) => {
            if(typeof errorMessage.errors !== 'undefined'){
                launchToast('danger',trans('Error'),errorMessage.message);
                // launchToast('danger',trans('Error'),errorMessage.errors.file)
                // $.each(errorMessage.errors,function (field,error) {
                //     launchToast('danger',trans('Error'),error);
                // });
            }
            else{
                if(typeof errorMessage.message !== 'undefined'){
                    launchToast('danger',trans('Error'),errorMessage.message);
                }
                else{
                    launchToast('danger',trans('Error'),errorMessage);
                }
            }
            FileUpload.myDropzone.removeFile(file);
            FileUpload.isLoading = false;
            FileUpload.isTranscodingVideo = false;
        });
    },

    updatePreviewsState:function () {
        const previewContainer = $('.dropzone-previews');
        const previews = previewContainer.find('.dz-preview');
        const hasPreviews = previews.length > 0;
        const hasAudioPreviews = previews.find('.audio-preview-item').length > 0;
        const hasNonAudioPreviews = previews.filter(function () {
            return $(this).find('.audio-preview-item').length === 0;
        }).length > 0;

        previewContainer.toggleClass('has-files', hasPreviews);
        previewContainer.toggleClass('has-audio-only', hasPreviews && hasAudioPreviews && !hasNonAudioPreviews);
    },

    /**
     * Updates the preview template based on uploaded file
     * @param file
     * @param localFile
     * @param attachment
     */
    updatePreviewElement:function (file,localFile, attachment = false) {
        let filePreview = $(file.previewElement);
        const previewType = FileUpload.getPreviewType(file, attachment);
        filePreview.find('.dz-image').remove();
        switch (previewType) {
        case 'video':
            filePreview.find('.audio-preview-item').remove();
            filePreview.find('.video-preview-item').remove();
            filePreview.prepend(videoPreview());
            var videoPreviewEl = filePreview.find('video').get(0);
            if(localFile){
                FileUpload.setMediaSourceForPreviewByElementAndFile(videoPreviewEl, file);
            }
            else{
                FileUpload.setPreviewSource(videoPreviewEl, file, attachment);
            }
            break;
        case 'audio':
            filePreview.find('.video-preview-item').remove();
            filePreview.find('.audio-preview-item').remove();
            filePreview.prepend(audioPreview());
            filePreview.addClass("w-100");
            filePreview.find('audio').addClass("w-100");
            filePreview.find(".audio-preview-item").addClass("w-100");
            var audioPreviewEl = filePreview.find('audio').get(0);
            filePreview.addClass("w-100");
            if(localFile){
                FileUpload.setMediaSourceForPreviewByElementAndFile(audioPreviewEl, file);
            }
            else{
                FileUpload.setPreviewSource(audioPreviewEl, file, attachment);
            }
            break;
        case 'application/vnd.ms-excel':
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            filePreview.prepend(excelPreview());
            break;
        case 'application/pdf':
            filePreview.prepend(pdfPreview());
            break;
        default:
            filePreview.prepend(imagePreview());
            if(!localFile){
                let previewElement = filePreview.find('img').get(0);
                FileUpload.setPreviewSource(previewElement, file, attachment);
            }
            break;
        }
    },

    getPreviewType: function (file, attachment = false) {
        if(FileUpload.isAudioFile(file, attachment)){
            return 'audio';
        }

        if(FileUpload.isVideoFile(file)){
            return 'video';
        }

        return file && file.type ? file.type : '';
    },

    /**
     * Sets up the media src for the uploaded file type
     * @param element
     * @param file
     * @returns {boolean}
     */
    setMediaSourceForPreviewByElementAndFile: function (element, file) {
        if(typeof element === 'undefined'){ return false;}
        file.upload = file.upload || {};
        const previewMimeType = FileUpload.getPreviewMimeType(file);
        if (element.canPlayType(previewMimeType).length && element.canPlayType(previewMimeType) !== "no") {
            const fileURL = window.URL.createObjectURL(file);
            file.upload.localPreviewPlayable = true;
            file.upload.localPreviewUrl = fileURL;
            $(element).attr('src', fileURL);
            $(element).attr('type', previewMimeType);
        }
        else{
            file.upload.localPreviewPlayable = false;
            if(FileUpload.isAudioFile(file)){
                FileUpload.showAudioPlaceholder(file, trans('Audio preview unavailable'), trans('Uploading audio...'));
            }
            else{
                FileUpload.showVideoPlaceholder(file, trans('Video preview unavailable'), trans('Uploading video...'));
            }
        }
    },

    getPreviewMimeType: function (file) {
        const fileName = file && typeof file.name === 'string' ? file.name.toLowerCase() : '';

        if(FileUpload.isAudioFile(file) && /\.m4a$/i.test(fileName)){
            return 'audio/mp4';
        }

        if(FileUpload.isAudioFile(file) && /\.webm$/i.test(fileName)){
            return 'audio/webm';
        }

        return file && file.type ? file.type : '';
    },

    /**
     * Sets media source | Thumbnail
     * @param element
     * @param file
     * @param attachment
     */
    setPreviewSource: function (element, file, attachment) {
        if(attachment.coconut_id !== null && attachment.path.indexOf('videos/tmp/') >= 0){
            FileUpload.showVideoEncodingState(file);
        }
        else{
            let fileSrc = attachment.path;
            if(attachment.type === 'image'){
                fileSrc = typeof attachment.thumbnail !== 'undefined' ? attachment.thumbnail : attachment.path;
            }
            FileUpload.clearVideoEncodingState(file);
            FileUpload.revokeLocalPreviewUrl(file);
            $(element).attr('src', fileSrc);

        }
    },

    isVideoFile: function (file) {
        return file && typeof file.type === 'string' && file.type.indexOf('video') === 0;
    },

    isAudioFile: function (file, attachment = false) {
        const attachmentType = attachment && typeof attachment.type === 'string' ? attachment.type.toLowerCase() : '';
        const fileType = file && typeof file.type === 'string' ? file.type.toLowerCase() : '';
        const fileName = file && typeof file.name === 'string' ? file.name.toLowerCase() : '';

        return attachmentType === 'audio' ||
            ['mp3', 'wav', 'ogg', 'm4a', 'webm'].indexOf(attachmentType) >= 0 ||
            fileType.indexOf('audio/') === 0 ||
            /\.(mp3|wav|ogg|m4a|webm)$/i.test(fileName);
    },

    isMp4File: function (file) {
        var fileName = file && typeof file.name === 'string' ? file.name : '';
        return file && (file.type === 'video/mp4' || /\.mp4$/i.test(fileName));
    },

    requiresServerVideoProcessing: function (file) {
        if(!FileUpload.isVideoFile(file) || FileUpload.isAudioFile(file) || typeof mediaSettings === 'undefined'){
            return false;
        }

        var driver = mediaSettings.transcoding_driver || 'none';
        if(driver === 'ffmpeg'){
            return !FileUpload.isMp4File(file) || mediaSettings.enforce_mp4_conversion === true;
        }

        if(driver === 'coconut'){
            return !FileUpload.isMp4File(file) || mediaSettings.coconut_enforce_mp4_conversion === true;
        }

        return false;
    },

    showVideoEncodingState: function (file) {
        if(!file || !file.previewElement) return;

        $(file.previewElement).addClass('is-video-processing');

        var hasLocalPreview = file.upload && file.upload.localPreviewPlayable === true;
        if(hasLocalPreview){
            FileUpload.clearVideoPlaceholder(file);
            $(file.previewElement).find('.video-preview-item').addClass('is-encoding');
        }
        else{
            FileUpload.showVideoPlaceholder(file, trans('Encoding video...'), trans('Preview will appear when conversion finishes.'));
        }
    },

    clearVideoEncodingState: function (file) {
        if(!file || !file.previewElement) return;
        $(file.previewElement).removeClass('is-video-processing');
        $(file.previewElement).find('.video-preview-item').removeClass('is-encoding video-preview-item--placeholder');
        $(file.previewElement).find('.video-encoding-overlay, .video-preview-placeholder').remove();
        $(file.previewElement).find('video').removeClass('d-none');
    },

    showVideoPlaceholder: function (file, title, subtitle) {
        if(!file || !file.previewElement) return;

        var filePreview = $(file.previewElement);
        var videoItem = filePreview.find('.video-preview-item').first();
        if(!videoItem.length){
            filePreview.find('.dz-image').remove();
            filePreview.prepend(videoPreview());
            videoItem = filePreview.find('.video-preview-item').first();
        }

        videoItem.removeClass('is-encoding').addClass('video-preview-item--placeholder');
        videoItem.find('video').addClass('d-none').removeAttr('src');
        videoItem.find('.video-encoding-overlay, .video-preview-placeholder').remove();
        videoItem.append(videoPreviewPlaceholder(title, subtitle));
    },

    clearVideoPlaceholder: function (file) {
        if(!file || !file.previewElement) return;

        var videoItem = $(file.previewElement).find('.video-preview-item');
        videoItem.removeClass('video-preview-item--placeholder');
        videoItem.find('.video-preview-placeholder').remove();
        videoItem.find('video').removeClass('d-none');
        if(!videoItem.find('.video-encoding-overlay').length){
            videoItem.append(videoEncodingOverlay());
        }
    },

    showAudioPlaceholder: function (file, title, subtitle) {
        if(!file || !file.previewElement) return;

        var filePreview = $(file.previewElement);
        var audioItem = filePreview.find('.audio-preview-item').first();
        if(!audioItem.length){
            filePreview.find('.dz-image').remove();
            filePreview.prepend(audioPreview());
            audioItem = filePreview.find('.audio-preview-item').first();
        }

        audioItem.find('audio').addClass('d-none').removeAttr('src');
        audioItem.find('.audio-preview-placeholder').remove();
        audioItem.append(audioPreviewPlaceholder(title, subtitle));
    },

    revokeLocalPreviewUrl: function (file) {
        if(file && file.upload && file.upload.localPreviewUrl){
            window.URL.revokeObjectURL(file.upload.localPreviewUrl);
            file.upload.localPreviewUrl = null;
        }
    },

    /**
     * Removes an attached file
     * @param attachmentID
     */
    removeAttachment: function (attachmentID) {
        if(typeof attachmentID === 'object' && attachmentID !== null){
            attachmentID = attachmentID.attachmentID || attachmentID.id;
        }
        $.ajax({
            type: 'POST',
            data: {
                'attachmentId': attachmentID,
            },
            url: app.baseUrl+'/attachment/remove',
            success: function () {
                launchToast('success',trans('Success'), trans('Attachment removed.'));
            },
            error: function () {
                launchToast('danger',trans('Error'), trans('Failed to remove the attachment.'));
            }
        });
    },

};

/**
 * Video preview Component
 * @returns {string}
 */
function videoPreview() {
    return `<div class="video-preview-item shadow">
                <span data-dz-name></span>
                <span data-dz-size></span>
            <video class="video-preview" controls autoplay muted loop></video>
        </div>`;
}

function videoEncodingOverlay() {
    return `<div class="video-encoding-overlay">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">${trans('Loading...')}</span>
                </div>
                <span>${trans('Encoding video...')}</span>
        </div>`;
}

function videoPreviewPlaceholder(title, subtitle) {
    return `<div class="video-preview-placeholder video-preview-placeholder--theme">
                <div class="video-preview-placeholder-icon">
                    <ion-icon name="videocam-outline"></ion-icon>
                </div>
                <div class="video-preview-placeholder-title">${title}</div>
                <div class="video-preview-placeholder-subtitle">${subtitle}</div>
        </div>`;
}

/**
 * Image preview Component
 * @returns {string}
 */
function imagePreview() {
    return `<div class="dz-image shadow">
            <img data-dz-thumbnail/>
        </div>
        <div class="dz-details">
            <div class="dz-filename"><span data-dz-name></span></div>
            <div class="dz-size" data-dz-size></div>
        </div>`;
}

/**
 * Audio preview Component
 * @returns {string}
 */
function audioPreview() {
    return `<div class="audio-preview-item">
                    <span data-dz-name></span>
                    <span data-dz-size></span>
                <audio id="audio-preview" controls type="audio/mpeg"></audio>
        </div>`;
}

function audioPreviewPlaceholder(title, subtitle) {
    return `<div class="audio-preview-placeholder small text-muted">
                <div>${title}</div>
                <div>${subtitle}</div>
        </div>`;
}

/**
 * Pdf document preview Component
 * @returns {string}
 */
function pdfPreview() {
    return `<div class="pdf-preview-item">
                    <div class="dz-image shadow p-4">
                        <img data-dz-thumbnail src="${mediaSettings.manual_payments_pdf_icon}"/>
                    </div>
                    <span data-dz-name></span>
                    <span data-dz-size></span>
        </div>`;
}

/**
 * Excel document preview Component
 * @returns {string}
 */
function excelPreview() {
    return `<div class="xls-preview-item">
                    <div class="dz-image shadow p-4">
                        <img data-dz-thumbnail src="${mediaSettings.manual_payments_excel_icon}"/>
                    </div>
                    <span data-dz-name></span>
                    <span data-dz-size></span>
        </div>`;
}
