<div class="user-search-box-item mb-4">
    <div class="d-flex flex-wrap">
        <div class="col-auto pr-0">
            <img src="{{$user->avatar}}" class="avatar rounded-circle shadow"/>
        </div>
        <div class="col">
            <div class="d-flex justify-content-between">
                <div class="text-truncate user-search-box-info">
                    <div class="m-0 h6 text-truncate d-flex align-items-center">
                        <a href="{{route('profile',['username'=>$user->username])}}" class="text-bold text-{{(Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme') == 'dark' ? 'white' : 'dark') : (Cookie::get('app_theme') == 'dark' ? 'white' : 'dark'))}} d-flex align-items-center">
                            {{$user->name}}
                        </a>
                        @if(GenericHelper::isUserVerified($user))
                            <span class="" data-toggle="tooltip" data-placement="top" title="{{__('Verified user')}}">
                            @include('elements.icon',['icon'=>'verified','centered'=>true,'classes'=>'ml-1'])
                    </span>
                        @endif
                    </div>
                    <div class="m-0 text-truncate small"><a href="{{route('profile',['username'=>$user->username])}}" class="text-muted">&commat;{{$user->username}}</a></div>
                </div>
                <div class="d-flex align-items-center">
                    @if(GenericHelper::shouldRenderExplorePeopleFollowButton($user))
                        @if(Auth::check())
                            <button
                                type="button"
                                class="btn btn-round btn-outline-primary btn-sm px-3 mb-0 search-follow-button"
                                aria-label="{{GenericHelper::getExplorePeopleFollowButtonState($user)['title']}}"
                                data-user-id="{{$user->id}}"
                                data-follow-action="{{GenericHelper::getExplorePeopleFollowButtonState($user)['action']}}"
                                data-follow-title="{{GenericHelper::getExplorePeopleFollowButtonState($user)['follow_title']}}"
                                data-unfollow-title="{{GenericHelper::getExplorePeopleFollowButtonState($user)['unfollow_title']}}"
                                data-follow-class="btn-outline-primary"
                                data-unfollow-class="btn-outline-primary"
                                onclick="Lists.toggleSearchFollow('{{$user->id}}', this)"
                            >{{GenericHelper::getExplorePeopleFollowButtonState($user)['title']}}</button>
                        @else
                            <button
                                type="button"
                                class="btn btn-round btn-outline-primary btn-sm px-3 mb-0"
                                aria-label="{{__('Follow')}}"
                                onclick="$('#login-dialog').modal('show')"
                            >{{__('Follow')}}</button>
                        @endif
                    @endif
                    <a
                        role="button"
                        class="btn btn-round btn-outline-primary btn-sm px-3 mb-0 {{GenericHelper::shouldRenderExplorePeopleFollowButton($user) ? 'ml-2' : ''}}"
                        href="{{route('profile',['username'=>$user->username])}}"
                        aria-label="{{__('View profile')}}"
                    >{{__("View")}}</a>
                </div>
            </div>

            <div class="mt-1 description-content line-clamp-3">
                @if($user->bio)
                    @if(getSetting('profiles.allow_profile_bio_markdown'))
                        {!!  GenericHelper::parseProfileMarkdownBio($user->bio) !!}
                    @else
                        {!!GenericHelper::parseSafeHTML($user->bio)!!}
                    @endif
                @else
                    {{__('No description available.')}}
                @endif
            </div>

        </div>
    </div>
</div>
