<legend style="text-align: center;">{{ $store->store_name }} > Support Tickets </legend>
<p style="text-align: center; color: #007bff; margin:0px; padding:0px;">Number of support tickets remaining for today:
    <span style="color: #dc3545;">
        ({{ 5 -$store->user->supports()->whereDate('created_at', \Carbon\Carbon::today())->count() }}/5)
    </span>
</p>

<div style="text-align: center; margin: 1em;">
    @if ($errors->any)
        @foreach ($errors->all() as $error)
            <p style="color: red; text-align:cenetr;">{{ $error }}</p>
        @endforeach
    @endif
    @if (session('success'))
        <p style="color: green; text-align:center;">{{ session('success') }}</p>
    @endif
    @if (session('new_ticket'))
        <div>
            <form action="/store/{{ $store->store_name }}/do/support" method="post" class="support-form">
                @csrf
                <label for="receiver" class="subject-label" style="width: fit-content;">Subject: <input type="text"
                        name="subject" class="subject" style="border: none; font-size: 1rem;"
                        placeholder="Support Subject..." required> </label>
                <input type="hidden" name="message_type" value="ticket">
                <textarea name="contents" placeholder="Write your request message here... max 5K characters!" cols="30"
                    rows="10" required></textarea>

                    <div id="capatcha-code-img">
                        <img src="/user/store/captcha" alt="Captcha Image">
                        <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                            placeholder="Captcha..." required>
                    </div>
                    
                <input type="submit" class="submit-nxt" value="Send">
            </form>
        </div>
    @else
        <div style="text-align: center; margin-bottom: 1em;">
            <form action="/store/{{ $store->store_name }}/do/support" method="post">
                @csrf
                <input type="submit" name="new_ticket" value="Create New Ticket" class="input-listing">
            </form>
        </div>
    @endif
</div>
<table>
    <thead>
        <tr>
            <th>Ticket ID</th>
            <th>Staff</th>
            <th>Status</th>
            <th>Action</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($storeUser->supports()->paginate(50)->sortByDesc('updated_at'); as $ticket)
            <tr>
                <td><a
                        href="/store/{{ $store->store_name }}/show/messages/{{ $ticket->conversation->created_at->timestamp }}/{{ $ticket->conversation_id }}">#TWM_{{ $ticket->created_at->timestamp }}</a>
                </td>
                <td class="{{ $ticket->staff_id != 0 ? $ticket->staff->role : '' }}">
                    {{ $ticket->staff_id != 0 ? $ticket->staff->public_name : 'No Staff Yet' }}</td>
                <td class="{{ $ticket->status }}">{{ $ticket->status }}</td>
                <td><a
                        href="/store/{{ $store->store_name }}/show/messages/{{ $ticket->conversation->created_at->timestamp }}/{{ $ticket->conversation_id }}">View</a>
                </td>
                <td>{{ $ticket->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan='4'>No support ticket found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $store->supports()->paginate(50)->render('vendor.pagination.custom_pagination') }}
