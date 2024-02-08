<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Cockfight') }} </title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @if(app()->environment('staging'))
        <script>
            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) || e.key === 'F12' || e.keyCode === 123) {
                    e.preventDefault();
                }
            });
            document.addEventListener('contextmenu', function (e) {
                e.preventDefault();
            });
        </script>
    @endif


    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
@php
    $cssClass = '';
    if(request()->routeIs('dragon-tiger')) {
        $cssClass = 'dragon-tiger';
    }
@endphp

<body class="{{  $cssClass }}">
@inertia

<x-translations/>


<input type="hidden" class="sd88 kv88 ls88 vk88 ct7 t88 vt88 sco88 jack388 dp88 pb"/>

<div id="message-modal"></div>
<div id="iframe-music"></div>

</body>
</html>
