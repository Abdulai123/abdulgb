<p style="text-align: center">Market Stores ({{ $stores->count() }})</p>
<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Since</th>
            <th>Last Seen</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stores as $store)
            <tr>
                <td>#{{ $store->id }}</td>
                <td>{{ $store->store_name }}</td>
                <td>{{ $store->created_at->DiffForHumans() }}</td>
                <td>{{ $store->updated_at->DiffForHumans() }}</td>
                <td class="{{ $store->status }}">{{ $store->status }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="store_id" value="{{ Crypt::encrypt($store->id) }}">

                        @if ($store->status == 'active')
                            <a href="/senior/staff/{{ $store->store_name }}/show/store/{{ $store->created_at->timestamp }}/{{ $store->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>

                            <button type="submit"
                                style="font-size: .7rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="ban">Ban</button>

                        @elseif($store->status == 'banned')
                            <a href="/senior/staff/{{ $store->store_name }}/show/store/{{ $store->created_at->timestamp }}/{{ $store->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
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
