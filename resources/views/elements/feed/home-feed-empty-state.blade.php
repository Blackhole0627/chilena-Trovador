@include('elements.empty-state', [
    'illustration' => asset('/img/no-content-available.svg'),
    'title' => __('Your feed is empty'),
    'copy' => __('Follow creators to see their latest posts here.'),
    'primaryAction' => [
        'url' => route('search.get', ['filter' => 'people']),
        'label' => __('Explore creators'),
        'icon' => 'people-outline',
        'style' => 'primary',
    ],
    'secondaryAction' => !getSetting('site.hide_create_post_menu') && GenericHelper::isEmailEnforcedAndValidated() ? [
        'url' => route('posts.create'),
        'label' => __('Create post'),
        'icon' => 'add-outline',
        'style' => 'outline-primary',
    ] : null,
])
