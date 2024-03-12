<h1>Market Unauthorizes Access ({{ \App\Models\Unauthorize::all()->count() }})</h1>
<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Search Term</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/senior/staff/{{ $user->public_name }}/show/unauthorizes/search" method="get" style="text-align: center">
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
            <th>User</th>
            <th>Title</th>
            <th>URL</th>
            <th>Content</th>
            <th>User Role</th>
            <th>Total</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php
         $unauthorizes = session('unauthorizes') ?? \App\Models\Unauthorize::orderByDesc('created_at')->paginate(50);
        @endphp
        @forelse ($unauthorizes as $unauthorize)
            <tr>
                <td>#{{ $unauthorize->id }}</td>
                <td>{{ $unauthorize->user->public_name }}</td>
                <td>{{ $unauthorize->title }}</td>
                <td>/{{ $unauthorize->url }}</td>
                <td>{{ $unauthorize->content }}</td>
                <td>{{ $unauthorize->role }}</td>
                <td>{{ $unauthorizes->where('user_id', $unauthorize->user_id)->count() }}</td>
                <td>{{ $unauthorize->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No unauthorized access found....</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $unauthorizes->render('vendor.pagination.custom_pagination') }}


