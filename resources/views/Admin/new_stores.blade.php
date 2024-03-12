
<h1>Market New Stores ({{ \App\Models\NewStore::all()->count() }})</h1>
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
        <form action="/whales/admin/{{ $user->public_name }}/show/newstores/search" method="get" style="text-align: center">
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
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="in_active" {{ old('status') == 'in_active' ? 'selected' : '' }}>In Active</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="search_term" id="search_term" class="form-input"
                        placeholder="Type here owner name...." value="{{ old('search_term') }}">
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
            <th>Store Name</th>
            <th>Owner Name</th>
            <th>Created At</th>
            <th>Owner Last Seen</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $new_stores = session('newStores') ?? \App\Models\NewStore::orderByDesc('created_at')->paginate(50);
        @endphp
        @forelse ($new_stores as $new_store)

            <tr>
                <td>#{{ $new_store->id }}</td>
                <td>{{ $new_store->store_name }}</td>
                <td>{{ $new_store->user->public_name }}</td>
                <td>{{ $new_store->created_at->DiffForHumans() }}</td>
                <td>{{ \Carbon\Carbon::parse($new_store->user->last_seen)->diffForHumans() }}</td>
                <td class="{{ $new_store->user->store_status }}">{{ $new_store->user->store_status }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="new_store_id" value="{{ Crypt::encrypt($new_store->id) }}">

                            <a href="/whales/admin/show/new store/{{ $new_store->created_at->timestamp }}/{{ $new_store->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">Review</a>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No new store found....</td>
            </tr>
            @endforelse
    </tbody>
</table>
{{ $new_stores->render('vendor.pagination.custom_pagination') }}
