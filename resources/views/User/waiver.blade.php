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

<link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ asset('auth.css') }}">
    <link rel="stylesheet" href="{{ asset('filter.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

    <title>WHALES MARKET | STORE WAIVER</title>
</head>

<body>
    @include('User.navebar')
    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                @if (session('success'))
                <p style="color:green; text-align:center;">{{ session('success') }}</p>
            @endif
                @if (!$user->waiver && \App\Models\MarketFunction::where('name', 'waiver')->first()->enable == 1)

                    @if ($user->twofa_enable == 'yes')
                        <h3>Requesting a Store Waiver</h3>
                        <hr>
                        @if ($errors->any())
                            <ul style="list-style: none">
                                @foreach ($errors->all() as $error)
                                    <li style="color:red;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if (session('success'))
                            <p style="color:green; text-align:center;">{{ session('success') }}</p>
                        @endif
                        <div>
                            <form action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <span style="color: red;">Remove all meta data from your images!</span><br><br>
                                <textarea name="reason" cols="30" rows="10" style="width: 100%; margin-bottom: 1em;"
                                    placeholder="Share why Whales Market should grant you a store waiver. Describe the products you plan to sell, your shipping locations, daily shipping capacity, available quantities, and whether you're working solo or with a team. Be informative and provide detailed information to support your request."
                                    required></textarea>


                                <div
                                    style="border:1px solid gray; margin:.2em; border-radius:.5rem; text-align:left; padding: .5em">
                                    Products
                                    proof if not digital 1:
                                    <input type="file" name="proof1" accept="image/png, image/jpeg, image/jpg"
                                        required>
                                </div>
                                <div
                                    style="border:1px solid gray; margin:.2em; border-radius:.5rem; text-align:left; padding: .5em">
                                    Products
                                    proof if not digital 2:
                                    <input type="file" name="proof2" accept="image/png, image/jpeg, image/jpg"
                                        required>
                                </div>
                                <div
                                    style="border:1px solid gray; margin:.2em; border-radius:.5rem; text-align:left; padding: .5em">
                                    Products
                                    proof if not digital 3:
                                    <input type="file" name="proof3" accept="image/png, image/jpeg, image/jpg"
                                        required>
                                </div>
                                <div id="capatcha-code-img">
                                    <img src="/user/captcha" alt="Captcha Image">
                                    <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                                        placeholder="Captcha..." required>
                                </div>
                                <input type="submit" name="sbmit-form" class="submit-nxt" value="Send">
                            </form>
                        </div>
                    @else
                        <p
                            style="text-align: center; background-color: rgb(255, 239, 238); padding: 8px; border: none; margin-bottom: 16px; box-sizing: border-box; border-radius: 5px; color: rgb(90, 8, 1); font-family:Verdana, Geneva, Tahoma, sans-serif;">
                            Two-Factor Authentication is Disabled, add your public pgp key that match your public
                            name
                            and check
                            the `enable 2FA box` to enable 2FA! for you to continue!!! <br><br>Sorry Mate ༼ つ ◕_◕ ༽つ
                        </p>

                    @endif
                @elseif (!$user->waiver && \App\Models\MarketFunction::where('name', 'waiver')->first()->enable == 0)
                <p
                style="text-align: center; background-color: rgb(255, 239, 238); padding: 8px; border: none; margin-bottom: 16px; box-sizing: border-box; border-radius: 5px; color: rgb(90, 8, 1); font-family:Verdana, Geneva, Tahoma, sans-serif;">
                Stores waiver is currently disable by admin
                <br><br>Sorry Mate ༼ つ ◕_◕ ༽つ
            </p>
                @else
                    <p>You've already created a store waiver, it {{ $user->waiver->status }}</p>
                @endif
            </div>
        </div>
    </div>
    @include('User.footer')
</body>

</html>
