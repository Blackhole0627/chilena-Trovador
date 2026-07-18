<?php

namespace App\Http\Requests;

use App\Providers\AttachmentServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class UploadAttachamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->route('type') ?: request()->route('type');

        if(in_array($type, ['payment-request', 'verify-asset'])) {
            return [
                'file' => 'required|mimes:'.'jpg,jpeg,png,pdf,xls,xlsx'.'|max:'.(string) ((int) getSetting('media.max_file_upload_size') * 1024),
            ];
        } else {
            $allowedExtensions = getSetting('media.allowed_file_extensions');
            if($type === 'message'){
                $allowedExtensions = AttachmentServiceProvider::withRecordedAudioExtensions($allowedExtensions);
            }

            return [
                'file' => 'required|mimes:'.$allowedExtensions.'|max:'.(string) ((int) getSetting('media.max_file_upload_size') * 1024),
            ];
        }
    }
}
