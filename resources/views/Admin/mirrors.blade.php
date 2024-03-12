<h1>Priavte Mirrors</h1>
<form action="" method="post" style="text-align: center; margin-bottom: 1em;">
    @csrf
    @if (session('new_mirror'))
    <input type="text" name="link" class="form-input" placeholder="New Mirror link">
    <label for="" class="form-label">
        Mirror Type:
        <select name="type" id="">
            <option value="user">User</option>
            <option value="store">Store</option>
            <option value="junior">Junior</option>
            <option value="senior">Senior</option>
        </select>
    </label>
    <input type="submit" name="save" class="submit-nxt" value="Save">
        
    @else
    <input type="submit" name="new_mirror" class="input-listing" value="New Mirror">
        
    @endif
</form>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>URL</th>
            <th>Action</th>
        </tr>
    </thead>
<tbody>
    @forelse (\App\Models\Mirror::all() as $mirror)
        <tr>
            <td>{{ $mirror->id }}</td>
            <td>{{ $mirror->type }}</td>
            <td>{{ $mirror->link }}</td>
            <td><form action="" method="post">@csrf <input type="hidden" name="id" value="{{ $mirror->id }}"> <input type="submit" name="delete" value="Delete" style="color:red"></form></td>
        </tr>
    @empty
        <tr>
            <td colspan="3">No mirros found yet</td>
        </tr>
    @endforelse
</tbody>
</table>