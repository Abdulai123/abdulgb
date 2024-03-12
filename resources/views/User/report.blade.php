<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $name }} > {{ $action }}</title>
    @if ($user->theme == 'dark')
        <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

    <link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ asset('filter.css') }}">
</head>

<body>
    @include('User.navebar')

    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <form action="" method="post" class="support-form"
                    style="max-width: 60%; border: 1px solid #ddd; padding: 10px;">
                    @if ($errors->any)
                        @foreach ($errors->all() as $error)
                            <p style="color: red; text-align:cenetr;">{{ $error }}</p>
                        @endforeach
                    @endif
                    @if (session('success'))
                        <p style="color: green;">{{ session('success') }}</p>
                    @endif
                    @csrf
                    @if ($is_store === 1)
                        <label for="sender" class="support-label" style="width: fit-content;">Reporting Store: <input
                                type="text" name="store_name" id="" class="subject"
                                style="border: none; font-size: 1rem;" value="{{ $store->store_name }}"
                                style="cursor: text" disabled></label>
                    @endif
                    @if ($is_listing === 1)
                        <label for="sender" class="support-label" style="width: fit-content;">Reporting Listing:
                            <input type="text" name="product_name" id="" class="subject"
                                style="border: none; font-size: 1rem;" value="{{ $product->product_name }}"
                                style="cursor: text" disabled></label>
                    @endif
                    <label for="receiver" class="support-label" style="width: fit-content;">Subject: <input
                            type="text" name="subject" class="subject" style="border: none; font-size: 1rem;"
                            placeholder="Report Subject..." required> </label>
                    <textarea name="report" class="support-msg" placeholder="Write your report here... max 5K characters!" cols="30"
                        rows="10" required></textarea>
                        <div id="capatcha-code-img">
                            <img src="/user/captcha" alt="Captcha Image">
                            <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                                placeholder="Captcha..." required>
                        </div>
                    <input type="submit" class="submit-nxt" value="Send">
                </form>
            </div>
        </div>
    </div>

    @include('User.footer')
</body>

</html>
