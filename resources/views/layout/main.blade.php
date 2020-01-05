<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('header')

    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    @include('layout.header')

    <div class="container mx-auto px-6 mt-6">
        @yield('content')
    </div>

    @yield('javascript')
</body>
</html>