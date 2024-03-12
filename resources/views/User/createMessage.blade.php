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

    <link rel="stylesheet" href="{{ asset('filter.css') }}">
</head>

<body>
    @include('User.navebar')

    <div class="container">
        <div class="main-div">
            <div class="notific-container">
            <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Creating New Message_</h1>
            <form action="" method="post" class="support-form"
                style="max-width: 60%; border: 1px solid #ddd; padding: 10px;">
                @if ($errors->any)
                    @foreach ($errors->all() as $error)
                        <p style="color: red; text-align:cenetr;">{{ $error }}</p>
                    @endforeach
                @endif
                @if (session('success'))
                    <p style="text-align: center; color: green;">{{ session('success') }}</p>
                @endif
                @csrf
                <label for="sender" class="subject-label" style="width: fit-content;">Receiver: <input type="text"
                        name="" id="" class="subject" style="border: none; font-size: 1rem;"
                        value="{{ $store->store_name }}" style="cursor: text" disabled></label>
                        <input type="hidden" name="message_type" value="message">
                <label for="receiver" class="subject-label" style="width: fit-content;">Subject: <input type="text"
                        name="subject" class="subject" style="border: none; font-size: 1rem;"
                        placeholder="Message Subject..." required> </label>
                <textarea name="contents" placeholder="Write your message here... max 5K characters!" cols="30"
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
