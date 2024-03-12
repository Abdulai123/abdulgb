<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('market.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <h1>Canary's</h1>
                <p class="notifications-p">Canary's will be updated every 30 days!</p>

                @forelse (\App\Models\MarketKey::all() as $canary)
                <div class="canary-div">
                    <p class="{{ $canary->user->role ?? 'pending' }}">{{ $canary->user->role ?? 'System' }}/{{ $canary->user->public_name ?? "Market" }} -> Canary ~ Sign message - <span style="font-style: italic;">Last Updated: {{ $canary->updated_at->DiffForHumans() }}</span></p><hr>
                    <p style="white-space: pre-wrap;">{{ $canary->message_sign }}</p><hr>
                </div>
                @empty
                    There are no canary yet.
                @endforelse
            </div>
        </div>
    </div>
    </div>
    <style>
        h1{
            text-align: center;
            color: var(--main-color);
        }


.canary-div {
  margin-bottom: 1.4em;
  border-radius: 8px;
  box-shadow: var(--shadow);
  color: var(--dark-color-text);
  border: 1px solid grey;
  border-radius: .5rem;
}

p{
  color: var(--dark-color-text);
  margin-left: 2em;
  margin-bottom: 1em;
  word-wrap: break-word;

}


    </style>
</body>

</html>
