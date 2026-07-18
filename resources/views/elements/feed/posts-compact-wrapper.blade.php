@if(count($posts))
    @foreach($posts as $post)
        @include('elements.feed.post-compact-tile', [
            'post' => $post,
            'compactPostPreview' => PostsHelper::getPostCompactMediaPreviewData($post, $compactMediaType ?? null),
        ])
    @endforeach
@else
    @if(isset($emptyStateView))
        @include($emptyStateView, array_merge($emptyStateData ?? [], ['classes' => 'profile-compact-posts-empty']))
    @else
        <div class="profile-compact-posts-empty d-flex justify-content-center align-items-center">
            <div class="col-10">
                <img src="{{asset('/img/no-content-available.svg')}}">
            </div>
        </div>
        <div class="profile-compact-posts-empty d-flex justify-content-center align-items-center">
            <h5 class="text-center mb-2 mt-2">{{__('No posts available')}}</h5>
        </div>
    @endif
@endif
