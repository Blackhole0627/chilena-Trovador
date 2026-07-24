{{--
    Trovador branded error page (standalone, dependency-free so it renders even on 500/503).
    Variables: code, title, message, button (label, optional), buttonUrl (optional), reload (bool, optional).
    Theme-aware: reads the app_theme cookie directly (no DB), defaults to dark. The plug image is
    optional — shown only when the matching file exists in public/img.
--}}
@php
    $__light = (($_COOKIE['app_theme'] ?? 'dark') === 'light');
    $__bg    = $__light ? '#fefefe' : '#12110f';
    $__title = $__light ? '#141210' : '#ffffff';
    $__msg   = $__light ? 'rgba(20,18,16,.6)' : 'rgba(255,255,255,.72)';
    $__plug  = $__light ? 'img/error-plug-light.png' : 'img/error-plug.png';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} · Trovador</title>
    <style>
        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;}
        body{min-height:100vh;display:flex;align-items:center;justify-content:center;background:{{ $__bg }};color:{{ $__title }};
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;padding:24px;}
        .te-wrap{width:100%;max-width:520px;text-align:center;}
        .te-img{width:min(340px,72vw);height:auto;margin:0 auto -8px;display:block;
            -webkit-mask-image:radial-gradient(ellipse 70% 56% at 50% 50%,#000 52%,transparent 86%);
            mask-image:radial-gradient(ellipse 70% 56% at 50% 50%,#000 52%,transparent 86%);}
        .te-code{font-size:clamp(56px,14vw,82px);font-weight:800;line-height:1;color:#FF5A5F;margin:0 0 10px;letter-spacing:-2px;}
        .te-title{font-size:clamp(20px,5vw,26px);font-weight:700;margin:0 0 10px;color:{{ $__title }};}
        .te-msg{font-size:16px;line-height:1.5;color:{{ $__msg }};margin:0 0 28px;}
        .te-btn{display:inline-block;padding:13px 32px;border-radius:12px;background:#E2725B;color:#fff;
            text-decoration:none;font-weight:700;font-size:15px;border:0;cursor:pointer;transition:opacity .15s ease;}
        .te-btn:hover{opacity:.9;color:#fff;}
    </style>
</head>
<body>
    <div class="te-wrap">
        @if(file_exists(public_path($__plug)))
            <img class="te-img" src="{{ asset($__plug) }}" alt="">
        @endif
        <div class="te-code">{{ $code }}</div>
        <h1 class="te-title">{{ $title }}</h1>
        <p class="te-msg">{{ $message }}</p>
        @if(!empty($button))
            <a class="te-btn" href="{{ $buttonUrl ?? url('/') }}"@if(!empty($reload)) onclick="event.preventDefault();location.reload();"@endif>{{ $button }}</a>
        @endif
    </div>
</body>
</html>
