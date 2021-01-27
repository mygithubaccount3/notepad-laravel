<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.frontend.includes.head')
    <body class="antialiased">
        @yield('content')
        @include('layouts.frontend.includes.scripts')
    </body>
</html>
