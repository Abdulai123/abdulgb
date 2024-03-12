<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $user->public_name }} > Market Canary</title>
    @if ($user->theme == 'dark')
        <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

    <link rel="stylesheet" href="{{ asset('filter.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    @include('User.navebar')
    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <header>
                    <h1>Frequently Asked Questions</h1>
                </header>
                <main>
                    <section class="faq-section">
                        @forelse (\App\Models\FAQ::all() as $faq)
                            <details>
                                <summary>{{ $faq->question }} </summary>
                                <p>{{ $faq->answer }}</p>
                                <p>Author: <span
                                        class="{{ $faq->user->role }}">{{ $faq->user->role == 'admin' ? 'Admin/' : 'Senior Mod/' }}{{ $faq->user->public_name }}</span>
                                </p>
                            </details>
                        @empty
                            <p>No F.A.Q is found!</p>
                        @endforelse
                    </section>
                </main>
            </div>
        </div>
    </div>
    <style>
        h1 {
            text-align: center;
            color: var(--main-color);
        }

        /* FAQ-specific styles */
        .faq-section {
            padding: 2em;
            color: var(--dark-color-text);
        }

        details {
            margin-bottom: 1.4em;
            border-radius: 8px;
            box-shadow: var(--shadow);
            color: var(--dark-color-text);
            border: 1px solid grey;
            border-radius: .5rem;
        }

        summary {
            padding: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            color: var(--dark-color-text);
            font-size: 1.2rem;
        }

        summary:hover {
            background-color: var(--white-background-color);
            color: var(--main-color);
        }

        /* Optional accordion-style behavior */
        details[open] summary {
            background-color: var(--white-background-color);
            color: var(--main-color);
        }

        /* details[open] > p {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-in-out;
} */

        p {
            color: var(--dark-color-text);
            margin-left: 2em;
            margin-bottom: 1em;
            word-wrap: break-word;

        }
    </style>
    @include('User.footer')
</body>

</html>
