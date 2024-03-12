<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Grand Bazaar | {{ $user->public_name }} > {{ $name }} > {{ $action }}</title>
    
    <link rel="stylesheet" href="{{ asset('market.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('filter.css') }}"> --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">
</head>

<body>
    {{-- <script>
        alert('<div class="alert-container">' +
              '<div class="alert-title">Attention</div>' +
              '<div class="alert-instructions">' +
              'Please disable JavaScript:' +
              '<ol>' +
              '<li>Open a new tab.</li>' +
              '<li>Type "about:config" in the address bar and press Enter.</li>' +
              '<li>Accept and proceed if prompted.</li>' +
              '<li>Search for "javascript" and find "javascript.enabled".</li>' +
              '<li>Right-click on the toggle to disable it.</li>' +
              '</ol>' +
              'After completing these steps, return here, refresh the page, and you\'re done!' +
              '</div>' +
              '<div class="alert-code">' +
              'about:config' +
              '</div>' +
              '</div>');
    </script> --}}

        

    @if (session('let_welcome'))
        @include('User.welcome')

    @elseif (session('ask_pgp'))
        @include('Auth.pgp')

    @else
        @include('User.navebar')

        @if ($name == 'store')
            @include('User.store')
        @else
            @include('User.action')
        @endif

        {{-- @include('User.footer') --}}
    @endif
</body>

</html>
