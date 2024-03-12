<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if ($user->theme == 'dark')
        <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('auth.css') }}">
    <link rel="stylesheet" href="{{ asset('filter.css') }}">
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>WHALES MARKET | STORE</title>
</head>

<body>
    @include('User.navebar')
    <div class="container">
        <div class="main-div">
            <div class="notific-container" style="text-align: center">
                <h3>Creating New Store</h3>
                <hr>
                @if ($errors->any())
                    <ul style="list-style: none">
                        @foreach ($errors->all() as $error)
                            <li style="color:red;">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <div>
                    @if ($user->twofa_enable == 'no')
                        <p
                            style="text-align: center; background-color: var(--white-background-color); padding: 8px; border: none; margin-bottom: 16px; box-sizing: border-box; border-radius: 5px; color: red; font-family:Verdana, Geneva, Tahoma, sans-serif;">
                            Two-Factor Authentication is Disabled, add your public pgp key that match your public
                            name
                            and check
                            the `enable 2FA box` to enable 2FA! for you to continue!!! <br><br>Sorry Mate ༼ つ ◕_◕ ༽つ
                        </p>
                    @else
                            @include('Store.createStore')
                    @endif
                </div>

            </div>
        </div>
    </div>
    @include('User.footer')
</body>

</html>
