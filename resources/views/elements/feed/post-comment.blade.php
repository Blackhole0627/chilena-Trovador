<div id="comment-{{$comment->id}}"
     class="post-comment d-flex flex-row mb-2 {{!empty($isReply) ? 'post-comment-reply' : 'post-comment-root'}}"
     data-commentID="{{$comment->id}}"
     data-comment-id="{{$comment->id}}"
     data-raw="{{ $comment->message }}">

    <div class="">
        <img class="rounded-circle post-comment-avatar" src="{{$comment->author->avatar}}" alt="{{$comment->author->username}}">
    </div>

    <div class="pl-2 w-100 post-comment-content">
        <div class="d-flex flex-row justify-content-between">
            <div class="text-bold d-flex align-items-center flex-wrap">
                <a href="{{route('profile',['username'=>$comment->author->username])}}" class="text-dark-r">{{$comment->author->username}}</a>
                @if(GenericHelper::isUserVerified($comment->author))
                    <span data-toggle="tooltip" data-placement="top" title="{{__('Verified user')}}">
                        @include('elements.icon',['icon'=>'verified','centered'=>true,'classes'=>'ml-1'])
                    </span>
                @endif
                @if(isset($isFirst) && $isFirst === true)
                    <span class="border fs-point-75 text-muted px-1 ml-1 rounded" data-toggle="tooltip" data-placement="top" title="{{__('First comment')}}">{{__('First')}}</span>
                @endif
                @if(!empty($isReply) && $comment->replyTarget && $comment->replyTarget->author)
                    <span class="small text-muted font-weight-normal ml-2">
                        {{__('Replying to')}}
                        <a href="{{route('profile', ['username' => $comment->replyTarget->author->username])}}" class="text-primary">
                            {{'@'.$comment->replyTarget->author->username}}
                        </a>
                    </span>
                @endif
            </div>
            <div class="position-absolute separator d-flex align-items-center">
                @if(Auth::user()->id != $comment->author->id)
                    <div class="d-flex">
                        <span class="h-pill h-pill-primary rounded react-button {{PostsHelper::didUserReact($comment->reactions) ? 'active' : ''}}" data-toggle="tooltip" data-placement="top" title="{{__("Like")}}" onclick="Post.reactTo('comment',{{$comment->id}})">
                            <span class="reaction-icon-active {{PostsHelper::didUserReact($comment->reactions) ? '' : 'd-none'}}">
                                @include('elements.icon',['icon'=>'heart', 'classes'=>'text-primary'])
                            </span>
                            <span class="reaction-icon-inactive {{PostsHelper::didUserReact($comment->reactions) ? 'd-none' : ''}}">
                                @include('elements.icon',['icon'=>'heart-outline'])
                            </span>
                        </span>
                    </div>
                @endif

                @if(Auth::user()->id == $comment->author->id || Auth::user()->id == $comment->post->user_id)
                    <div class="dropdown {{GenericHelper::getSiteDirection() == 'rtl' ? 'dropright' : 'dropleft'}} comment-management-menu ml-1">
                        <a class="h-pill h-pill-primary rounded comment-management-toggle"
                           data-toggle="dropdown"
                           href="#"
                           role="button"
                           aria-label="{{__('Comment options')}}"
                           aria-haspopup="true"
                           aria-expanded="false">
                            @include('elements.icon',['icon'=>'ellipsis-horizontal-outline'])
                        </a>
                        <div class="dropdown-menu">
                            @if(Auth::user()->id == $comment->author->id)
                                <a class="dropdown-item" href="javascript:void(0)" onclick="Post.showEditCommentInterface({{$comment->post->id}},{{$comment->id}})">{{__('Edit')}}</a>
                            @endif
                            <a class="dropdown-item" href="javascript:void(0)" onclick="Post.showDeleteCommentDialog({{$comment->post->id}},{{$comment->id}})">{{__('Delete')}}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div>
            <div class="pb-1">
                <div id="comment-content-{{$comment->id}}" class="text-break comment-content line-clamp-3">
                    {!! PostsHelper::renderCommentText($comment) !!}
                </div>
                <a href="javascript:void(0)"
                   class="comment-content-toggle text-primary pointer-cursor small d-none"
                   aria-controls="comment-content-{{$comment->id}}"
                   aria-expanded="false">
                    <span class="label-more">{{__('Show more')}}</span>
                    <span class="label-less d-none">{{__('Show less')}}</span>
                </a>
            </div>
            <div class="d-flex text-muted small">
                <div class="d-flex align-items-center">
                    @if($comment->updated_at->notEqualTo($comment->created_at))
                        <div data-toggle="tooltip" data-placement="bottom" title="{{__("Edited at") }} {{ $comment->updated_at->format('Y-m-d H:i') }}">
                            {{\Carbon\Carbon::parse($comment->created_at)->diffForHumans(null,false,true)}}
                        </div>
                    @else
                        {{\Carbon\Carbon::parse($comment->created_at)->diffForHumans(null,false,true)}}
                    @endif
                </div>
                <div class="ml-2">
                    <span class="comment-reactions-label-count">{{count($comment->reactions)}}</span>
                    <span class="comment-reactions-label">{{trans_choice('likes',count($comment->reactions))}}</span>
                </div>
                {{-- Trovador: flat comments (TikTok-Live style) — reply threads disabled --}}
            </div>
        </div>
    </div>

    <div class="pl-2 w-100 post-comment-edit d-none">
        <div class="d-flex flex-row justify-content-between">
            <div class="w-100 pr-2">
                <div class="edit-post-comment-area">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="input-group w-100">
                            <div class="hl-textarea-wrap w-100">
                                <div class="hl-backdrop" aria-hidden="true">
                                    <div class="hl-highlights"></div>
                                </div>

                                <textarea name="message"
                                          class="form-control comment-textarea comment-text edit-comment-textarea hl-textarea"
                                          placeholder="{{__('Write a message..')}}"
                                          onkeyup="textAreaAdjust(this)">{{$comment->message}}</textarea>
                            </div>

                            <span class="invalid-feedback pl-2 text-bold" role="alert"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="d-flex mt-2">
                    <span class="ml-1 h-pill h-pill-primary rounded react-button save-comment-edit-button" data-toggle="tooltip" data-placement="top" title="{{__("Save")}}" onclick="Post.saveEditedComment({{$comment->post->id}},{{$comment->id}})">
                         @include('elements.icon',['icon'=>'checkmark-outline'])
                    </span>
                    <span class="ml-1 h-pill h-pill-primary rounded react-button" data-toggle="tooltip" data-placement="top" title="{{__("Cancel")}}" onclick="Post.cancelEditCommentInterface()">
                         @include('elements.icon',['icon'=>'close-outline'])
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>
