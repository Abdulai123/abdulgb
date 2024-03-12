<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $user->public_name }} > Messages</title>
    <link rel="stylesheet" href="{{ asset('market.css') }}">

    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    @include('User.navebar')

    <div class="main-div">
        <div class="notific-container">
            <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Messages_</h1>
            <p class="notifications-p">Messages older than 15 days will be automatically deleted. Please note that only
                individual messages within the conversation, not the entire conversation itself, mark conversations as
                archive to leave the conversation.</p>
            @if ($errors->any)
                @foreach ($errors->all() as $error)
                    <p style="color: red; text-align:cenetr;">{{ $error }}</p>
                @endforeach
            @endif
            @if (session('success'))
                <p style="color: green; text-align:center;">{{ session('success') }}</p>
            @endif
            <table>
                <thead>
                    <tr>
                        <th>Sort By</th>
                        <th>Number Of Rows</th>
                        <th>Message Type</th>
                        <th>Action Button</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="/{{ $user->public_name }}/messages/search" method="get" style="text-align: center">
                        @csrf
                        <tr>
                            <td>
                                <select name="sort_by" id="sort_by">
                                    <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest
                                    </option>
                                    <option value="oldest" {{ old('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest
                                    </option>
                                </select>
                            </td>
                            <td>
                                <select name="number_of_rows" id="number_of_rows">
                                    <option value="50" {{ old('number_of_rows') == '50' ? 'selected' : '' }}>50
                                    </option>
                                    <option value="100" {{ old('number_of_rows') == '100' ? 'selected' : '' }}>100
                                    </option>
                                    <option value="150" {{ old('number_of_rows') == '150' ? 'selected' : '' }}>150
                                    </option>
                                    <option value="250" {{ old('number_of_rows') == '250' ? 'selected' : '' }}>250
                                    </option>
                                </select>
                            </td>
                            <td>
                                <select name="message_type" id="">
                                    <option value="all" {{ old('message_type') == 'all' ? 'selected' : '' }}>All
                                        Messages</option>
                                    <option value="message" {{ old('message_type') == 'message' ? 'selected' : '' }}>
                                        Messages</option>
                                    <option value="ticket" {{ old('message_type') == 'ticket' ? 'selected' : '' }}>
                                        Support Tickets</option>
                                    <option value="dispute" {{ old('message_type') == 'dispute' ? 'selected' : '' }}>
                                        Dispute Messages</option>
                                    <option value="mass" {{ old('message_type') == 'mass' ? 'selected' : '' }}>
                                        Favorite Stores News Letters</option>
                                </select>
                            </td>
                            <td style="text-align: center; margin:0px; padding:0px;">
                                <input type="submit" class="submit-nxt"
                                    style="width: max-content; margin:0px; padding:.5em;" value="Search">
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
            @php
                $AllConversations = session('conversations') ?? $conversations;
                $AllParticipants = session('participants') ?? $userConversations->sortByDesc('updated_at');
            @endphp
            @forelse ($AllParticipants->where('is_hidden', 0) as $userConversation)
                @foreach ($AllConversations->where('id', $userConversation->conversation_id) as $conversation)
                    @php
                        $latestMessage = $conversation
                            ->messages()
                            ->latest()
                            ->first();
                    @endphp

                    @php
                        $isDispute = $latestMessage && $latestMessage->message_type == 'dispute';
                        $iconType = 'mail'; // Default icon type

                        if ($isDispute) {
                            $iconType = 'dispute';
                        } elseif ($latestMessage && $latestMessage->message_type == 'ticket') {
                            $iconType = 'plane-tickets';
                        } elseif ($latestMessage && $latestMessage->message_type == 'mass') {
                            $iconType = 'news_letter';
                        }
                    @endphp

                    <a href="{{ $isDispute ? '/order/' . $conversation->dispute->order->created_at->timestamp . '/' . $conversation->dispute->order->id : '/messages/' . $conversation->created_at->timestamp . '/' . $conversation->id }}"
                        class="notification-container">
                        @if ($latestMessage)
                            <img src="data:image/jpeg;base64,{{ $icon[$iconType] }}" alt="" class="icon-filter"
                                width="40">
                        @else
                            <img src="data:image/jpeg;base64,{{ $icon['mail'] }}" alt="" class="icon-filter"
                                width="40">
                        @endif

                        <div class="notification-content">
                            <div style="display: flex;">
                                <span>{{ $conversation->topic }}</span>
                                <span>Reference #WM{{ $conversation->created_at->timestamp }}</span>
                                <span class="notification-time">{{ $conversation->created_at->diffForHumans() }}</span>
                                @if ($latestMessage->message_type == 'mass' || $latestMessage->message_type == 'message')
                                    <form
                                        action="/conversation/archive/{{ $conversation->created_at->timestamp }}/{{ $conversation->id }}"
                                        method="post">
                                        @csrf
                                        <input type="submit" name="read" id="" class="pending"
                                            value="Mark as archive" style="cursor: pointer;">
                                    </form>
                                @elseif ($latestMessage->message_type == 'dispute')
                                    @php
                                        $dispute = \App\Models\Dispute::where('conversation_id', $conversation->id)->first();
                                    @endphp
                                    @if ($dispute->status == 'closed')
                                        <form
                                            action="/conversation/archive/{{ $conversation->created_at->timestamp }}/{{ $conversation->id }}"
                                            method="post">
                                            @csrf
                                            <input type="submit" name="read" id="" class="pending"
                                                value="Mark as archive" style="cursor: pointer;">
                                        </form>
                                    @endif
                                @endif
                            </div>
                            <p class="notification-message">
                                @if ($latestMessage)
                                    {{ Str::limit($latestMessage->content, 50, '...') }}
                                @else
                                    No message sent yet to this conversation...
                                @endif
                                @php
                                    $unreadMessageCounter = 0;
                                @endphp

                                @foreach ($conversation->messages as $message)
                                    @php
                                        $unreadMessageCounter += $message->status
                                            ->where('user_id', $user->id)
                                            ->where('is_read', 0)
                                            ->count();
                                    @endphp
                                @endforeach

                                @if ($unreadMessageCounter > 0)
                                    <span class="count-unread-messages">{{ $unreadMessageCounter }}</span>
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            @empty
                No conversation found, for the search, or you do not have any conversations...
            @endforelse

        </div>

    </div>
    @include('User.footer')
</body>

</html>
