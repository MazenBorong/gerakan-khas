@php
    $navRole = $navRole ?? \App\Gk\Support\RoleReader::forUser(auth()->user());
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    @include('partials.gk.fonts')
    @include('partials.gk.head-meta')
    @vite(['resources/css/app.css', 'resources/scss/gk/entry.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="gk-surface min-h-dvh antialiased">
    @include('partials.gk.app-nav', ['navRole' => $navRole ?? 'staff'])
    <main class="gk-main">
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>
