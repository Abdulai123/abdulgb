<h1>Market Users({{ \App\Models\User::all()->where('role', 'user')->count() }})</h1>
<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Role</th>
            <th>Status</th>
            <th>Search Term</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/users/search" method="get" style="text-align: center">
            @csrf
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
                    <select name="role" id="">
                        <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="user" {{ old('status') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="store" {{ old('status') == 'store' ? 'selected' : '' }}>Store</option>
                        <option value="share" {{ old('status') == 'share' ? 'selected' : '' }}>Share</option>
                        <option value="junior" {{ old('status') == 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="senior" {{ old('status') == 'senior' ? 'selected' : '' }}>Senior</option>
                        <option value="admin" {{ old('status') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </td>
                <td>
                    <select name="status" id="">
                        <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="search_term" id="search_term" class="form-input"
                        placeholder="Type here user name...." value="{{ old('search_term') }}">
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
            <th>Public Name</th>
            <th>Balance</th>
            <th>Total Orders</th>
            <th>Member Since</th>
            <th>Last Seen</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $users = session('users') ?? \App\Models\User::orderByDesc('created_at')->paginate(50);
    @endphp
    
    
        @forelse ($users as $user)
        <tr>
            <td>#{{ $user->id }}</td>
            <td>{{ $user->public_name }}</td>
            <td>${{ $user->wallet->balance }}</td>
            <td>{{ $user->total_orders }}</td>
            <td>{{ $user->created_at->DiffForHumans() }}</td>
            <td>{{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</td>
            <td class="{{ $user->status }}">{{ $user->status }}</td>
            <td>
                <a href="/whales/admin/show/user/{{ $user->created_at->timestamp }}/{{ $user->id }}"
                    style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="8">No user found...</td>
            </tr>
        @endforelse
        
    </tbody>
</table>
{{ $users->render('vendor.pagination.custom_pagination') }}

