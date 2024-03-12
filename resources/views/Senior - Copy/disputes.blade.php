<p style="text-align: center">Market Disputes ({{ $disputes->where('status', '!=', 'closed')->count() }})</p>
<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Order ID</th>
            <th>Amount</th>
            <th>Moderator</th>
            <th>Status</th>
            <th>Start At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($disputes as $dispute)
            <tr>
                <td>#{{ $dispute->id }}</td>
                <td>{{ $dispute->order_id }}</td>
                <td>$0.00</td>
                <td>{{ $dispute->mediator_id != null ? $dispute->moderator->public_name : 'No moderator join yet.' }}</td>
                <td class="{{ $dispute->status }}">{{ $dispute->status }}</td>
                <td>{{ $dispute->created_at->DiffForHumans() }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="store_id" value="{{ Crypt::encrypt($dispute->id) }}">

                        @if ($dispute->status != 'closed')
                            <a href="/senior/staff/show/store/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                        @endif

                        @if ($dispute->mediator_id == null)
                        <button type="submit"
                        style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                        name="join">Join</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
