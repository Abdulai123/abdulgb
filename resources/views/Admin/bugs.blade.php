<h1>Bugs Reports</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Reporter</th>
            <th>Reporter Role</th>
            <th>Bug Type</th>
            <th>Status</th>
            <th>Report</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach (\App\Models\Bug::all() as $bug)
            <tr>
                <td>{{ $bug->id }}</td>
                <td>{{ $bug->user->public_name }}</td>
                <td>{{ $bug->user->role }}</td>
                <td>{{ $bug->type }}</td>
                <td>{{ $bug->status }}</td>
                <td><textarea name="" id="" cols="30" rows="10">{{ $bug->content }}</textarea></td>
                <td><form action="" method="post">
                    @csrf
                    <input type="hidden" name="bug" value="{{ $bug->id }}">
                    <input type="submit" name="valid" value="Valid" style="color:green; cursor:pointer;">
                    <input type="submit" name="invalid" value="In Valid" style="color:red;  cursor:pointer;">
                    </form></td>
            </tr>
        @endforeach
    </tbody>
</table>