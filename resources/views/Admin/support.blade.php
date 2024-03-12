<h1>Support Tickets({{ \App\Models\Support::all()->count() }})</h1>
<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Status</th>
            <th>Search Term</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/supports/search" method="get" style="text-align: center">
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
                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="search_term" id="search_term" class="form-input"
                        placeholder="Type here sender name...." value="{{ old('search_term') }}">
                </td>
                <td style="text-align: center; margin:0px; padding:0px;">
                    <input type="submit" class="submit-nxt" style="width: max-content; margin:0px; padding:.5em;"
                        value="Perform">
                </td>
            </tr>
        </form>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Topic</th>
            <th>Sender Name</th>
            <th>Sender Role</th>
            <th>Saff</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $supports = session('supports') ?? $supports;
        @endphp
        @forelse ($supports as $ticket)

            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->conversation->topic }}</td>
                <td>{{ $ticket->user->public_name }}</td>
                <td>{{ $ticket->user->role }}</td>
                <td class="{{ $ticket->staff_id != null ? $ticket->staff->role : '' }}">{{ $ticket->staff_id != null ? $ticket->staff->public_name : 'No staff yet' }}</td>
                <td class="{{ $ticket->status }}">{{ $ticket->status }}</td>
                <td>{{ $ticket->created_at->DiffForHumans() }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="support_id" value="{{ Crypt::encrypt($ticket->id) }}">
                            <a href="/whales/admin/{{ $user->public_name }}/show/messages/{{ $ticket->conversation->created_at->timestamp }}/{{ $ticket->conversation_id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>

                        @if ($ticket->staff_id == null)
                        <button type="submit"
                        style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                        name="join_support">Join</button>
                        @endif
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No support ticket found...</td>
            </tr>
            @endforelse
    </tbody>
</table>
{{ $supports->render('vendor.pagination.custom_pagination') }}