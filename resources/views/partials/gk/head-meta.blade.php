@php
    $m = config('gk.meta');
    $site = $m['site_name'];
    $desc = $m['description'];
    $theme = $m['theme_color'];
    $robots = $m['robots'];
    $headTitle = filled($title ?? null) ? ($title.' — '.$site) : $site;
    $canonical = url()->current();
    $locale = str_replace('_', '-', app()->getLocale());
@endphp
<link rel="icon" href="{{ url('/favicon.svg') }}" type="image/svg+xml" sizes="any">
<link rel="icon" href="{{ url('/favicon-32.png') }}" type="image/png" sizes="32x32">
<link rel="icon" href="{{ url('/icon-512.png') }}" type="image/png" sizes="512x512">
<link rel="apple-touch-icon" href="{{ url('/apple-touch-icon.png') }}" sizes="180x180">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $desc }}">
<meta name="application-name" content="{{ $site }}">
<meta name="theme-color" content="{{ $theme }}">
<meta name="robots" content="{{ $robots }}">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="{{ $site }}">
<title>{{ $headTitle }}</title>
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="{{ $locale }}">
<meta property="og:title" content="{{ $headTitle }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ url('/icon-512.png') }}">
<meta property="og:image:width" content="512">
<meta property="og:image:height" content="512">
<meta property="og:site_name" content="{{ $site }}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $headTitle }}">
<meta name="twitter:description" content="{{ $desc }}">
