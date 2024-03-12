<h1 class="notifications-h1" style="margin:0; padding:0px;;">_Messages_</h1>
<p class="notifications-p">Read messages older than 10 days will be deleted autmatically!</p>
@foreach ($storeConversations->sortByDesc('updated_at') as $storeConversation)
    @foreach ($conversations->where('id', $storeConversation->conversation_id) as $conversation)
        <a href="/store/{{ $store->store_name }}/show/messages/{{ $conversation->created_at->timestamp }}/{{ $conversation->id }}"
            class="notification-container">
            @php
                $latestMessage = $conversation
                    ->messages()
                    ->latest()
                    ->first();
            @endphp
            @if ($latestMessage)
                <img src="data:image/jpeg;base64,
                @if ($latestMessage->message_type == 'dispute') {{ $icon['dispute'] }}
                @elseif ($latestMessage->message_type == 'ticket')
                {{ $icon['plane-tickets'] }}
                @else
                {{ $icon['mail'] }} @endif 
                "
                    alt="" class="icon-filter" width="40">
            @else
                <img src="data:image/jpeg;base64,{{ $icon['mail'] }}"alt="" class="icon-filter" width="40">
            @endif
            <div class="notification-content">
                <div style="display: flex;">
                    <span>{{ $conversation->topic }}</span>
                    <span>Reference #WM{{ $conversation->created_at->timestamp }}</span>
                    <span class="notification-time">{{ $conversation->created_at->diffForHumans() }}</span>
                </div>
                <p class="notification-message">
                    @if ($latestMessage)
                        {{ Str::limit($latestMessage->content, 50, '...') }}
                    @else
                        No message send yet to this conversation...
                    @endif
                    @php
                        $unread_message_counter = 0;
                    @endphp
                    @foreach ($conversation->messages as $message)
                            @php
                                $unread_message_counter += $message->status
                                    ->where('user_id', $storeUser->id)
                                    ->where('is_read', 0)
                                    ->count();
                            @endphp
                    @endforeach
                    @if ($unread_message_counter > 0)
                        <span class="count-unread-messages">{{ $unread_message_counter }}</span>
                    @endif
                </p>
            </div>
        </a>
    @endforeach
@endforeach

