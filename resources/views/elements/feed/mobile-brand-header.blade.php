@php
    $themeLogo = GenericHelper::getCurrentThemeLogo();
@endphp

<div class="d-flex d-md-none px-3 py-2 feed-mobile-search mobile-feed-brand-header neutral-bg fixed-top-m align-items-center justify-content-between">
    <a href="{{route('feed')}}" class="mobile-feed-brand-logo d-inline-flex align-items-center" aria-label="{{getSetting('site.name')}}">
        <img src="{{$themeLogo}}" alt="{{__('Site logo')}}">
    </a>

    <form action="{{route('search.get')}}" class="search-box-wrapper mobile-feed-search-form" method="GET">
        <div class="input-group input-group-seamless-append mobile-feed-search-group">
            <input type="text" class="form-control shadow-none form-control-sm mobile-feed-search-input" aria-label="{{__('Search')}}" placeholder="{{__('Search')}}" name="query">
            <div class="input-group-append">
                <button type="submit" class="h-pill h-pill-primary rounded text-primary mobile-feed-search-button d-flex justify-content-center align-items-center" aria-label="{{__('Search')}}">
                    @include('elements.icon',['icon'=>'search','variant'=>'mediumish','centered'=>true])
                </button>
            </div>
        </div>
        <input type="hidden" name="filter" value="{{getSetting('feed.default_search_widget_filter') ? getSetting('feed.default_search_widget_filter') : 'top'}}">
    </form>
</div>
