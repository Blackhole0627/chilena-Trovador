/*
* Feed page & component
 */
"use strict";
/* global app, initialPostIDs, paginatorConfig, PostsPaginator, Post, SuggestionsSlider, initStickyComponent, getCookie */

$(function () {
    if(typeof paginatorConfig !== 'undefined'){
        if((paginatorConfig.total > 0 && paginatorConfig.total > paginatorConfig.per_page) && paginatorConfig.hasMore) {
            PostsPaginator.initScrollLoad();
        }
        PostsPaginator.init(paginatorConfig.next_page_url, '.posts-wrapper');
    }
    else{
        // eslint-disable-next-line no-console
        console.error('Pagination failed to initialize.');
    }
    PostsPaginator.initPostsGalleries(initialPostIDs);
    PostsPaginator.initPostsHyperLinks();
    // Animate polls
    Post.animatePollResults();

    Post.setActivePage('feed');
    if(getCookie('app_prev_post') !== null){
        PostsPaginator.scrollToLastPost(getCookie('app_prev_post'));
    }
    Post.initPostsMediaModule();
    // Initing read more/less toggler based on clip property
    PostsPaginator.initDescriptionTogglers();
    SuggestionsSlider.init('#suggestions-box');
    SuggestionsSlider.init('#suggestions-box-expired');
    if(app.feedDisableRightClickOnMedia === true){
        Post.disablePostsRightClick();
    }
    Feed.initMobileBrandSearch();

    if (window.StoriesSwiper) {
        window.StoriesSwiper.containerId = "stories-swiper"; // optional, default already
        window.StoriesSwiper.init({
            feedUrl: `${app.baseUrl}/stories/feed`
        });
    }

});

window.onunload = function(){
    // Reset scrolling to top
    window.scrollTo(0,0);
};

$(window).scroll(function () {
    initStickyComponent('.feed-widgets','sticky');
});

// eslint-disable-next-line no-unused-vars
var Feed = {
    initMobileBrandSearch: function () {
        var $header = $(".mobile-feed-brand-header");

        if (!$header.length) {
            return;
        }

        $header.on("click", ".mobile-feed-search-button", function (event) {
            var $input = $header.find(".mobile-feed-search-input");

            if (!$header.hasClass("is-searching")) {
                event.preventDefault();
                Feed.openMobileBrandSearch($header);
                return;
            }

            if ($.trim($input.val()) === "") {
                event.preventDefault();
                Feed.closeMobileBrandSearch($header);
            }
        });

        $header.on("submit", ".mobile-feed-search-form", function (event) {
            if (!$header.hasClass("is-searching")) {
                event.preventDefault();
                Feed.openMobileBrandSearch($header);
                return;
            }

            if ($.trim($header.find(".mobile-feed-search-input").val()) === "") {
                event.preventDefault();
                Feed.closeMobileBrandSearch($header);
            }
        });

        $header.on("keydown", ".mobile-feed-search-input", function (event) {
            if (event.key === "Escape") {
                Feed.closeMobileBrandSearch($header);
            }
        });

        $(document).on("click.mobileBrandSearch", function (event) {
            if (
                $header.hasClass("is-searching") &&
                $header.has(event.target).length === 0 &&
                $.trim($header.find(".mobile-feed-search-input").val()) === ""
            ) {
                Feed.closeMobileBrandSearch($header);
            }
        });
    },

    openMobileBrandSearch: function ($header) {
        $header.addClass("is-searching");
        $header.find(".mobile-feed-search-input").trigger("focus");
    },

    closeMobileBrandSearch: function ($header) {
        $header.removeClass("is-searching");
        $header.find(".mobile-feed-search-input").trigger("blur");
    }
};
