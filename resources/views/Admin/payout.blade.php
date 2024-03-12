<h1>PayOut Logs</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>User Role</th>
            <th>Amount</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse (\App\Models\Pay::all() as $pay)
            <tr>
                <td>{{ $pay->id }}</td>
                <td>{{ $pay->user->public_name }}</td>
                <td class="{{ $pay->user->role }}">{{ $pay->user->role }}</td>
                <td>{{ $pay->amount }}</td>
                <td>{{ $pay->created_at->DiffForHumans() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No payee history...</td>
            </tr>
        @endforelse
    </tbody>
</table>