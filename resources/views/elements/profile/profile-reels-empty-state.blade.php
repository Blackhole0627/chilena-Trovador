@include('elements.empty-state', [
    'illustration' => asset('/img/no-content-available.svg'),
    'title' => $title,
    'copy' => $copy,
    'primaryAction' => $showCreateAction ? [
        'url' => route('reels.create'),
        'label' => __('Create your reel'),
        'icon' => 'add-outline',
        'style' => 'primary',
    ] : null,
])
