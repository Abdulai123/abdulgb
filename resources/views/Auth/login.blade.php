<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('filter.css') }}">
    <link rel="stylesheet" href="{{ asset('auth.css') }}">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <title>Grand Bazaar | Auth > LogIn</title>
</head>


<body class="bg-gray-50 dark:bg-gray-900 p-5">
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
                        Grand Bazaar Login
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
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                Password</label>
                            <input <input type="password" name="password" placeholder="••••••••••••••••"
                                class="bg-gray-50 border cursor-pointer border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required>
                        </div>

                        <div>
                            <label for="timer"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose a duration
                                for your login session</label>
                            <select id="timer" name="session_timer"
                                class="bg-gray-50 border cursor-pointer border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="30">30 minutes</option>
                                <option value="1">1 Hour</option>
                                <option value="2">2 Hours</option>
                                <option value="3">3 Hours</option>
                                <option value="4">4 Hours</option>
                                <option value="5">5 Hours</option>
                                <option value="6">6 Hours</option>
                                <option value="7">7 Hours</option>
                                <option value="8">8 Hours</option>
                                <option value="9">9 Hours</option>
                                <option value="10">10 Hours</option>
                                <option value="11">11 Hours</option>
                                <option value="12">12 Hours</option>
                            </select>
                        </div>

                        <div>
                        {{-- <div class="flex row-span-2 justify-around">
                            {{ \App\Http\Controllers\GeneralController::captchaGen() }}

                            <input type="text" id="captcha" maxlength="5" minlength="5" name="captcha"
                                placeholder="Captcha..." required>

                        </div> --}}
                    </div>

                        <div
                            class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <button type="submit"
                                class="relative cursor-pointer inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                                <span
                                    class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    LogIn
                                </span>
                            </button>
                        </div>


                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                            Do not have an account? <a href="/auth/signup"
                                class="font-medium text-primary-600 underline dark:text-primary-50">Create New Account
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
</body>

</html>
