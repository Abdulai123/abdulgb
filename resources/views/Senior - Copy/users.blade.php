<p style="text-align: center">Market Users ({{ $users->where('role', 'user')->count() }})</p>
<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Public Name</th>
            <th>Since</th>
            <th>Last Seen</th>
            <th>Spent</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users->where('role', 'user') as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td>{{ $user->public_name }}</td>
                <td>{{ $user->created_at->DiffForHumans() }}</td>
                <td>{{ $user->updated_at->DiffForHumans() }}</td>
                <td>${{ $user->spent }}</td>
                <td class="{{ $user->status }}">{{ $user->status }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Crypt::encrypt($user->id) }}">

                        @if ($user->status == 'active')
                            <a href="/senior/staff/{{ $user->public_name }}/show/user/{{ $user->created_at->timestamp }}/{{ $user->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="accept">Escalate</button>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="ban">Ban</button>
                        @elseif($user->status == 'escalated')
                            <a href="/senior/staff/{{ $user->public_name }}/show/user/{{ $user->created_at->timestamp }}/{{ $user->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="de_escalate">De Escalate</button>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="ban">Ban</button>
                        @elseif($user->status == 'banned')
                            <a href="/senior/staff/{{ $user->public_name }}/show/user/{{ $user->created_at->timestamp }}/{{ $user->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="de_escalate">Escalate</button>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="un_ban">Un Ban</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
