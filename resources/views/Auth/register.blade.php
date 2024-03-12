<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('auth.css') }}">
    <title>Grand Bazaar | Auth > LogIn</title>
    <style>
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 p-5">
    {{-- @if (session('encrypted_message_verify')) --}}

    <div class="cont">
        <form action="" method="post">
            @csrf
            <h1>VERIFY PGP TOKEN</h1>

            <div style="margin: 10px;">

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p style="color: red; text-align:center;">{{ $error }}</p>
                    @endforeach
                @endif

                @if (session('success'))
                    <p style="color: green; text-align: center;">{{ session('success') }}</p>
                @endif
            </div>
            <pre style="text-align: left; word-wrap: break-word;" contenteditable="true">{!! session('encrypted_message') !!}</pre>
            <label for="2fa"
                title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
                style="color: #ccc;">Enable 2FA? <span style="color:red">*</span>
                <input type="checkbox"
                    title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
                    class="enable2fa" name="enable2fa" checked>
            </label>
            <div class="two-btns">
                <input type="text" name="pgp_token" minlength="10" maxlength="10"
                    placeholder="Enter your token..." required>
                <input type="submit" name="save_key" value="Verify">
            </div>
        </form>
    </div>
{{-- @else
    <div class="flex items-center flex-col">
        <div class=" animate-bounce w-32">
            <a href="/"><img src="data:image/png;base64,{{ $icon['gb'] }}"></a>
        </div>
        <div
            class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">

            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white cursor-nw-resize">
                        Grand Bazaar SignUp
                    </h1>

                    <form action="{{ url()->current() }}" class="space-y-4 md:space-y-6" method="POST">
                        @if (session('success'))
                            <span style="color:green;">{{ session('success') }}</span>
                        @endif
                        @if ($errors->any())
                            <ul style="list-style-type: none; padding: 0;">
                                @foreach ($errors->all() as $error)
                                    <li style="color: red;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @csrf

                        <div>
                            <label for="private_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your Login
                                Name</label>
                            <input type="text" name="private_name"
                                class="bg-gray-50 border border-gray-300 cursor-pointer text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Login Name" required value="{{ old('private_name') }}">
                        </div>

                        <div>
                            <label for="referral_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your referral public name if you have.</label>
                            <input type="text" name="referred_link"
                                class="bg-gray-50 border border-gray-300 cursor-pointer text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Referral Public Name (optional)" required value="{{ old('referred_link') }}">
                        </div>

                        <div>
                            <label for="login_passphrase"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">The distinctive
                                phrase for site verification, ensuring you're on the official and legitimate
                                platform</label>
                            <input type="text" name="login_passphrase"
                                class="bg-gray-50 border border-gray-300 cursor-pointer text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Site display phrase" required value="{{ old('login_passphrase') }}">
                        </div>


                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                Password</label>
                            <input type="password" name="password" placeholder="••••••••••••••••"
                                class="bg-gray-50 border cursor-pointer border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label for="confirm_password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm your
                                password</label>
                            <input type="password" name="confirm_password" placeholder="••••••••••••••••"
                                class="bg-gray-50 border cursor-pointer border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label for="pgp key"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your pgp key: this is required to create account and 2FA is enable for all account, your public name will be extracted from your pgp key! you can share that name to refers other here.</label>
                            <textarea name="pgp_key" id="" cols="30" rows="10"
                            class="bg-gray-50 border border-gray-300 cursor-pointer text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required placeholder="PGP KEY"></textarea>
                        </div>

                        <div
                            class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <button type="submit"
                                class="relative cursor-pointer inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                                <span
                                    class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    SignUp
                                </span>
                            </button>
                        </div>


                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                            Already have an account? <a href="/auth/login"
                                class="font-medium text-primary-600 underline dark:text-primary-50">LogIn
                                here</a>
                        </p>
                </div>
            </div>
        </div>
        <div class="flex gap-8 justify-between text-white">
            <div class="font-medium text-primary-600 underline dark:text-primary-500">
                <a href="/reset-password">Reset Password</a>
            </div>
            <div class="font-medium text-primary-600 underline dark:text-primary-500">
                <a href="/canary.txt">Canary & Keys</a>
            </div>
        </div>
    </div>
@endif --}}

</body>

{{-- <div class="login-div">
            <h3>SignUp Page</h3>
            <hr>
            <form action="" method="POST">
                @if ($errors->any())
                <ul style="list-style-type: none; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li style="color: red">{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                @csrf

                <input type="text" name="public_name" class="publicName" placeholder="Public Name*" required value="{{ old('public_name') }}"><br>
                <span class="cls-1 publicName-spn">Your public name should be descriptive and unique. NOTE: If you are creating account to become a vendor please use your vendor name here!!!</span><br>
                <input type="text" name="private_name" class="privateName" placeholder="Private Name*" required value="{{ old('private_name') }}"><br>
                <span class="cls-1 privateName-spn">Your private name must be unique.</span><br>
                <input type="text" name="login_passphrase" class="login-phrase" placeholder="Login phrase*"
                    required value="{{ old('login_passphrase') }}"><br>
                <span class="cls-1 login-phrase-spn">Your login phrase to know you are on the legit site!</span><br>
                <input type="number" name="pin_code" class="secretCode" placeholder="Secret code 6 digits*"
                    pattern="[1-9]*" minlength="6" maxlength="6" required value="{{ old('pin_code') }}"><br>
                <span class="cls-1 secretCode-spn">Your secret code must <br> be 6 digits in
                    length.</span><br>
                <input type="text" name="referred_link" class="secretCode"
                    placeholder="Referral Public Name (If you have)" value="{{ old('referred_link') }}"><br><br>
                <input type="password" class="password" name="password" placeholder="Password*" required><br><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password*" required><br><br>
                <div id="capatcha-code-img">
                    {{ \App\Http\Controllers\GeneralController::captchaGen() }}
                    <input type="text" id="captcha" maxlength="5" minlength="5" name="captcha" placeholder="Captcha..." required>
                </div> 
                <div style="margin: 1em 0px">
                    <span style="border-bottom: 2px dashed green; color: #f1f1f1; font-weight:bold; cursor:help"
            title="
                    1. Hover over the image.   
                    2. Identify the character within the circle.
                    3. Begin tracing the line starting from that character.
                    4. The line will guide you to 5 characters.
                    5. Input these 5 characters into the captcha box above.
                    6. If the characters become scattered, refresh (reload) the page to reset.
                    ">How to
                        Solve the captcha? Hover here to know!</span>
                </div>
                <button type="submit" name="signup" class="signup"><img
                        src="data:image/png;base64,{{ $icon['login'] }}" width="40" class="icon-filter"></button>
            </form>
            <p class="no-account">Already have an account? <a href="/auth/login">Enter here</a></p><br>
            <center>
                <p class="cprght">Copyright &copy; 2024 Whales Market. All rights reserved.
                </p>
            </center>
        </div>
    </div> --}}

</html>
