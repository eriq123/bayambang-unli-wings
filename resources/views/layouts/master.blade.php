<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @googlefonts
    @vite(['resources/sass/app.scss'])

    <style>
        body {
            background: url('/bamboo-background.webp') fixed center / contain;
        }
    </style>
</head>

<body>
    <header class="container-fluid header__container">
        <div class="container">
            @include('partials.nav')
        </div>
    </header>

    <div class="container {{ in_array(Route::currentRouteName(), ['login', 'register']) ? 'container__auth' : '' }}">
        @yield('content')
    </div>

    @vite(['resources/js/app.js'])
</body>

</html>
