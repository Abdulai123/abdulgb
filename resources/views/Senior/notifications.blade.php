<div class="notific-container">

    <h1 class="notifications-h1">__Notifications</h1>
    <p class="notifications-p">Notification older than 30 days will be deleted autmatically!</p>

    <table>
        <thead>
            <tr>
                <th>Sort By</th>
                <th>Number Of Rows</th>
                <th>Status</th>
                <th>Action Button</th>
                <th>D0</th>
            </tr>
        </thead>
        <tbody>
            <form action="/senior/staff/{{ $user->public_name }}/notifications/show/search" method="get" style="text-align: center">
                <tr>
                    <td>
                        <select name="sort_by" id="sort_by">
                            <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ old('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </td>
                    <td>
                        <select name="number_of_rows" id="number_of_rows">
                            <option value="50" {{ old('number_of_rows') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ old('number_of_rows') == '100' ? 'selected' : '' }}>100</option>
                            <option value="150" {{ old('number_of_rows') == '150' ? 'selected' : '' }}>150</option>
                            <option value="250" {{ old('number_of_rows') == '250' ? 'selected' : '' }}>250</option>
                        </select>
                    </td>
                    <td>
                        <select name="status" id="">
                            <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="read" {{ old('status') == 'read' ? 'selected' : '' }}>Read</option>
                            <option value="unread" {{ old('status') == 'unread' ? 'selected' : '' }}>Un Read</option>
                        </select>
                    </td>
                    <td>
                        <select name="action" id="">
                            <option value="show" {{ old('status') == 'all' ? 'selected' : '' }}>Show</option>
                            <option value="read_all" {{ old('status') == 'read_all' ? 'selected' : '' }}>Mark all as
                                read</option>
                            <option value="delete" {{ old('status') == 'delete' ? 'selected' : '' }}>Delete All
                            </option>
                        </select>
                    </td>
                    <td style="text-align: center; margin:0px; padding:0px;">
                        <input type="submit" class="submit-nxt" style="width: max-content; margin:0px; padding:.5em;"
                            value="Perform">
                    </td>
                </tr>
            </form>
        </tbody>
    </table>
    @php
        $notifications = session('notifications') ?? $user->notifications->sortByDesc('created_at');
    @endphp
    @forelse ($notifications as $notification)
        <div class="notification-container {{ $notification->is_read ? 'read' : '' }}">
            {{-- $notification->notificationType->icon --}}
            <img src="data:image/jpeg;base64,{{ $icon[$notification->notificationType->icon] }}" class="icon-filter"
                alt="" width="30">
            <div class="notification-content">
                <div style="display: flex;">
                    <span>{{ $notification->user->public_name }}</span>
                    <span>{{ $notification->notificationType->name }}</span>
                    <span>Reference #WM{{ $notification->created_at->timestamp }}</span>
                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                    <form action="/senior/staff/{{ $user->public_name }}/notification/{{ $notification->created_at->timestamp }}/{{ $notification->id }}"
                        method="post">
                        @csrf
                        @if (!$notification->is_read)
                            <input type="submit" name="read" id="" class="active" value="Mark as read"
                                style="cursor: pointer;">
                        @endif
                        <input type="submit" name="delete" class="delete" id="" value="Delete"
                            style="cursor: pointer;">
                    </form>
                </div>
                <p class="notification-message" style="margin-top: 0px; font-size: .8rem">
                    {{ $notification->notificationType->content }}
                    @if ($notification->notificationType->icon == 'store' && $user->show_key == true)
                        <br>
                        {{ $user->store_key }}
                    @endif
                    @if ($notification->notificationType->icon == 'order')
                        <a href="/order/{{ $notification->order->created_at->timestamp }}/{{ $notification->option_id }}"
                            style="font-size: 1rem; text-decoration:underline;">see order details here</a>
                    @endif
                </p>
            </div>
        </div>
    @empty
        <p style="text-align: center; font-size: 1.2rem;">You don't have any notification.</p>
    @endforelse
</div>
