@include('elements.empty-state', [
    'illustration' => asset('/img/no-content-available.svg'),
    'title' => $title,
    'copy' => $copy,
    'classes' => $classes ?? '',
    'primaryAction' => $showCreateAction ? [
        'url' => route('posts.create'),
        'label' => __('Create post'),
        'icon' => 'add-outline',
        'style' => 'primary',
    ] : null,
])
