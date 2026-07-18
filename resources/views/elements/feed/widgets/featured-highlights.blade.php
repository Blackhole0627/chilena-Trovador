{{-- F4 — "Destacados" feed widget with tabs: Reels, Fotos, Audio, Perfiles nuevos --}}
@php
    use App\Helpers\FeaturedContentHelper;
    $tvReels = FeaturedContentHelper::reels();
    $tvPhotos = FeaturedContentHelper::photos();
    $tvAudio = FeaturedContentHelper::audio();
    $tvProfiles = FeaturedContentHelper::newProfiles();
@endphp

@if($tvReels->count() || $tvPhotos->count() || $tvAudio->count() || $tvProfiles->count())
<div class="card mb-3 trovador-featured-widget">
    <div class="card-body">
        <h6 class="text-bold mb-2">🔥 {{ __('Destacados') }}</h6>

        <ul class="nav nav-pills mb-3 trovador-featured-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tv-feat-reels">{{ __('Reels') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tv-feat-photos">{{ __('Fotos') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tv-feat-audio">{{ __('Audio') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tv-feat-profiles">{{ __('Perfiles nuevos') }}</a></li>
        </ul>

        <div class="tab-content">
            {{-- Reels --}}
            <div class="tab-pane fade show active" id="tv-feat-reels">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    @foreach($tvReels as $reel)
                        <a href="{{ route('reels.get', ['reel_id' => $reel->id]) }}" class="mr-2 text-center" style="min-width:90px;">
                            <img src="{{ $reel->user->avatar ? \Illuminate\Support\Facades\Storage::disk('public')->url($reel->user->avatar) : asset('/img/default-avatar.jpg') }}"
                                 class="rounded" style="width:80px;height:110px;object-fit:cover;" alt="reel">
                            <div class="small text-truncate" style="max-width:90px;">{{ $reel->user->name }}</div>
                        </a>
                    @endforeach
                    @if(!$tvReels->count())<div class="text-muted small">{{ __('Sin contenido aún') }}</div>@endif
                </div>
            </div>

            {{-- Fotos --}}
            <div class="tab-pane fade" id="tv-feat-photos">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    @foreach($tvPhotos as $post)
                        <a href="{{ route('posts.get', ['post_id' => $post->id, 'username' => $post->user->username]) }}" class="mr-2 text-center" style="min-width:90px;">
                            <img src="{{ $post->user->avatar ? \Illuminate\Support\Facades\Storage::disk('public')->url($post->user->avatar) : asset('/img/default-avatar.jpg') }}"
                                 class="rounded" style="width:80px;height:80px;object-fit:cover;" alt="photo">
                            <div class="small text-truncate" style="max-width:90px;">{{ '@'.$post->user->username }}</div>
                        </a>
                    @endforeach
                    @if(!$tvPhotos->count())<div class="text-muted small">{{ __('Sin contenido aún') }}</div>@endif
                </div>
            </div>

            {{-- Audio --}}
            <div class="tab-pane fade" id="tv-feat-audio">
                <div class="d-flex flex-column">
                    @foreach($tvAudio as $post)
                        <a href="{{ route('posts.get', ['post_id' => $post->id, 'username' => $post->user->username]) }}" class="d-flex align-items-center mb-2 text-dark-r">
                            @include('elements.icon',['icon'=>'musical-notes-outline','classes'=>'mr-2'])
                            <span class="text-truncate">{{ $post->user->name }} — {{ \Illuminate\Support\Str::limit(strip_tags($post->text), 40) }}</span>
                        </a>
                    @endforeach
                    @if(!$tvAudio->count())<div class="text-muted small">{{ __('Sin contenido aún') }}</div>@endif
                </div>
            </div>

            {{-- Perfiles nuevos --}}
            <div class="tab-pane fade" id="tv-feat-profiles">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    @foreach($tvProfiles as $profile)
                        <a href="{{ route('profile', ['username' => $profile->username]) }}" class="mr-2 text-center" style="min-width:90px;">
                            <img src="{{ $profile->avatar ? \Illuminate\Support\Facades\Storage::disk('public')->url($profile->avatar) : asset('/img/default-avatar.jpg') }}"
                                 class="rounded-circle" style="width:64px;height:64px;object-fit:cover;" alt="profile">
                            <div class="small text-truncate" style="max-width:90px;">{{ $profile->name }}</div>
                        </a>
                    @endforeach
                    @if(!$tvProfiles->count())<div class="text-muted small">{{ __('Sin contenido aún') }}</div>@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
