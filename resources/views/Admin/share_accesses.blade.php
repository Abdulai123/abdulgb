<h1>Share Access(0)</h1>

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
            <th># ID</th>
            <th>Name</th>
            <th>Total Permissions</th>
            <th>Permissions</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($shares as $share)
            <tr>
                <td>#{{ $share->id }}</td>
                <td style="text-transform: uppercase;">{{ $share->user->public_name }}</td>
                <td>{{ $share->sharePermission->count() }}/12</td>
                <td>
                    <select name="" id="">
                        @foreach ($share->sharePermission as $permission)
                        <option value="">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="{{ $share->status }}">{{ $share->status }}</td>
                <td><form action="" method="post">
                    @csrf
                    <input type="hidden" name="user" value="{{ Crypt::encrypt($share->user_id) }}" id="">
                    <button type="submit"
                    style="font-size: .8rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="edit">Edit Permissions</button>
                <button type="submit"
                    style="font-size: .8rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="revoke">Revoke</button>
                    </form></td>
            </tr>
            @empty
            <tr>
                <td colspan="5">You don't have any shared access.</td>
            </tr>
            @endforelse

    </tbody>
</table>