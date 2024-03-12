<h1 style="text-align: center;">Conversations({{ $conversations->count() }})</h1>

<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/conversations/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="archive" {{ old('sort_by') == 'archive' ? 'selected' : '' }}>All Archived</option>
                        <option value="highest_messages" {{ old('sort_by') == 'highest_messages' ? 'selected' : '' }}>Highest Messages</option>
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
            <th>Total Messages</th>
            <th>Participants Count</th>
            <th>Participants</th>
            <th>Archived By</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $conversations = session('conversations') ?? $conversations;
        @endphp
        @forelse ($conversations as $conversation)
            <tr class="self-container">
                <td>#{{ $conversation->id }}</td>
                <td>{{ $conversation->topic }}</td>
                <td>{{ $conversation->messages->count() }}</td>
               <td>{{ $conversation->participants->count() }}</td>
               <td>
                @foreach ($conversation->participants as $key)
                    <span>/{{ $key->user->role }}/{{ $key->user->public_name }}</span>
                @endforeach
            </td>
            
               <td>{{ $conversation->participants->where('is_hidden', 1)->count() }}</td>
               <td>{{ $conversation->created_at->DiffForHumans() }}</td>
               <td><form action="" method="post"> @csrf <input type="hidden" name="id" value="{{ $conversation->id }}"><input type="submit" value="Delete" name="detele" style="color:red; cursor:pointer;"></form></td>
            </tr>
        </form>
    @empty

        <tr>
            <td colspan="10">
                <span class="no-notification">
                    Conversations is currently empty.
                </span>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $conversations->render('vendor.pagination.custom_pagination') }}
