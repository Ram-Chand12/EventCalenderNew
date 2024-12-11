<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
  
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/assets/dist/css/style2.css">

</head>

<body>

    @php
    use Illuminate\Support\Facades\Log;
      
    @endphp
    <div id="app">
        {{-- @include('sweetalert::alert') --}}
        {{ $slot }}
    </div>

    <!-- Scripts -->
    {{-- <script src="/assets/plugins/jquery/jquery.min.js"></script> --}}
    {{-- @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9']) --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
   
</body>

</html>