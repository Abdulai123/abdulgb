
<h1>Market Disputes ({{ \App\Models\Dispute::all()->where('status', '!=', 'closed')->count() }})</h1>
<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Status</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/senior/staff/{{ $user->public_name }}/show/disputes/search" method="get" style="text-align: center">
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
                        <option value="Full Refund" {{ old('status') == 'Full Refund' ? 'selected' : '' }}>Full Refund</option>
                        <option value="Partial Refund" {{ old('status') == 'Partial Refund' ? 'selected' : '' }}>Partial Refund</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
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

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Amount</th>
            <th>Mediator</th>
            <th>Mediator Requested</th>
            <th>Status</th>
            <th>Start At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $disputes = session('disputes') ?? \App\Models\Dispute::orderByDesc('created_at')->paginate(50);
    @endphp

        @forelse ($disputes as $dispute)
            
            <tr>
                <td>#{{ $dispute->order_id }}</td>
                <td>${{ $dispute->escrow->fiat_amount ?? 0.00 }}</td>
                <td class="{{ $dispute->mediator_id != null ?  $dispute->moderator->role : '' }}">{{ $dispute->mediator_id != null ? $dispute->moderator->public_name : 'No staff join yet.' }}</td>
                <td class="{{ $dispute->mediator_request == 1 ?  'cancelled' : 'pending' }}">{{ $dispute->mediator_request == 1 ? 'Yes' : 'No' }}</td>
                <td class="{{ $dispute->status }}">{{ $dispute->status }}</td>
                <td>{{ $dispute->created_at->DiffForHumans() }}</td>
                <td>
                    <form action="/senior/staff/{{ $user->public_name }}/do/dispute/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}" method="post">
                        @csrf
                        @if (($dispute->mediator_id == null) || $dispute->mediator_id == $user->id)
                            <a href="/senior/staff/{{ $user->public_name }}/show/dispute/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                        @endif

                        @if ($dispute->mediator_id == null && $dispute->mediator_request == 1)
                        <button type="submit"
                        style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                        name="join_dispute">Join</button>
                        @endif
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No dispute found....</td>
            </tr>
            @endforelse
    </tbody>
</table>
{{ $disputes->render('vendor.pagination.custom_pagination') }}
