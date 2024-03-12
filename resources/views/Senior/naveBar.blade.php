<div class="nav-bar">
    <div class="start-menus">
        <div class="logo">
            <a href="/senior/staff/{{ $user->public_name }}/show">
                <img src="data:image/png;base64,{{ $icon['whale'] }}" alt="">
            </a>
        </div>
        <div class="name">
            <a href="/senior/staff/{{ $user->public_name }}/show"><span class="w">WHALES</span> <span
                    class="m">/S/MOD</span></a>
        </div>
    </div>
    <div class="end-menus">
        <div class="notification">
            <a href="/senior/staff/{{ $user->public_name }}/show/messages" target="" rel="noopener noreferrer"> <img
                    src="data:image/png;base64,{{ $icon['mail'] }}" class="icon-filter" alt="Messages" width="25">
                @php
                    $unread_messages = App\Models\MessageStatus::where('user_id', auth()->user()->id)
                        ->where('is_read', 0)
                        ->count();
                @endphp
                @if ($unread_messages > 0)
                    <span class="new-notification">{{ $unread_messages }}</span>
                @endif
            </a>
        </div>
        <div class="notification">
            <a href="/senior/staff/{{ $user->public_name }}/show/notifications" target="" rel="noopener noreferrer"> <img
                    src="data:image/png;base64,{{ $icon['notification'] }}" class="icon-filter" alt="Notification"
                    width="25">
                @if ($user->notifications->where('is_read', 0)->count() > 0)
                    <span class="new-notification">{{ $user->notifications->where('is_read', 0)->count() }}</span>
                @endif
                </span>
            </a>
        </div>
        <div class="notification">
            <a href="/senior/staff/{{ $user->public_name }}/show/settings" target="" rel="noopener noreferrer"> <img
                    src="data:image/png;base64,{{ $icon['settings'] }}" class="icon-filter" alt="Setting"
                    width="25"></a>
        </div class="notification">
        <div>
            <a href="/logout"> <img src="data:image/png;base64,{{ $icon['logout'] }}"
                    class="icon-filter" alt="LogOut" width="25"></a>
        </div>
    </div>
</div>
