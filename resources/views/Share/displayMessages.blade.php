<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $store->store_name }} > Messages</title>
    <link rel="stylesheet" href="{{ @asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
</head>

<body>
    @include('Store.navebar')

    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <p class="message-heading"> Reference:
                    {{ '#MWM_' . $conversation->created_at->timestamp }}
                </p>
                <p style="font-size: .8rem; color: #acacac; text-align:center; margin: .3em 0px;"> <span>Subject:
                    </span>
                    {{ $conversation->topic }}</p>
                @foreach ($conversation->messages as $message)
                    @if ($message->message_type == 'ticket')
                        <p style="text-transform:capitalize; text-align:center; margin: .3em 0px;"
                            class="{{ $conversation->support->status }}">Status:
                            {{ $conversation->support->status }}</p>
                    @break
                @endif
            @endforeach
            @if ($errors->any)
                @foreach ($errors->all() as $error)
                    <p style="color: red; text-align:cenetr;">{{ $error }}</p>
                @endforeach
            @endif
            @if (session('ticket_closed'))
                <p style="color: red; text-align:center;">The ticket you are replying to has been closed!!!</p>
            @endif
            @if (session('new_message'))
                <div>
                    <form
                        action="/store/{{ $store->store_name }}/do/messages/{{ $conversation->created_at->timestamp }}/{{ $conversation->id }}"
                        method="post" class="message-reply-form">
                        @csrf
                        <textarea name="contents" class="support-msg" placeholder="Write your reply here... max 5K characters!" cols="30"
                            rows="10" required></textarea>
                        <input type="hidden" name="message_type"
                            @php $latestMessage = $conversation->messages()->latest()->first(); @endphp
                            value="{{ $latestMessage->message_type }}">
                        <input type="submit" class="submit-nxt" value="Send">
                    </form>
                </div>
            @else
                <div style="text-align: center; margin-bottom: 1em;">
                    <form
                        action="/store/{{ $store->store_name }}/do/messages/{{ $conversation->created_at->timestamp }}/{{ $conversation->id }}"
                        method="post">
                        @csrf
                        <input type="submit" name="new_message" value="New Reply" class="input-listing">
                    </form>
                </div>
            @endif

            <div class="message-div">
                @foreach ($conversation->messages->sortByDesc('created_at') as $message)
                    @if ($message->user_id != null)
                        <div
                            class="chat-message @if ($message->user->id === $user->id) {{ 'message-right' }} @else {{ 'message-left' }} @endif">
                            <p>{{ $message->content }}</p>
                            <p class="owner "> <span
                                    class="{{ $message->user->role == 'store' ? 'storem' : $message->user->role }}"
                                    style="margin-right:1em">
                                    /@if ($message->user->role == 'junior' || $message->user->role == 'senior')
                                        {{ $message->user->role . ' mod' }}
                                    @else
                                        {{ $message->user->role }}
                                    @endif/{{ $message->user->public_name }} </span>

                                @foreach ($message->status as $status)
                                    @if ($status->user_id != $user->id && $status->user_id != $message->user->id)
                                        <span
                                            class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                            {{ $status->is_read == 1 ? 'read' : 'unread' }}], </span>
                                    @elseif ($status->user_id == $user->id && $status->user_id != $message->user->id)
                                        <span
                                            class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                            {{ $status->is_read == 1 ? 'read' : 'unread' }}], </span>
                                    @endif
                                @endforeach
                                sent {{ $message->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @else
                        <div class="chat-message message-left">
                            <p>{{ $message->content }}</p>
                            <p class="owner"> <span class="senior" style="margin-right:1em">/mod/System Mod</span>
                                @foreach ($message->status as $status)
                                    <span
                                        class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                        {{ $status->is_read == 1 ? 'read' : 'unread' }}], </span>
                                @endforeach
                                sent {{ $message->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>


    @include('Store.footer')
</body>

</html>
