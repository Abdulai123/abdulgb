<h1>Servers({{ \App\Models\Server::all()->count() }})</h1>

<form action="" method="post" style="text-align: center; margin-bottom:1em;">
    @csrf

    @if (session('new_server'))
        <input type="text" name="ip" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Ip addr..."><br>

        <input type="text" name="port" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Port..."><br>

        <input type="text" name="user_name" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="User Name...."><br>

        <input type="text" name="password" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Password..."><br>

        <input type="text" name="type" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Type....[wallet, daemon, api]"><br>

        <input type="text" name="extra_user" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Extra user..."><br>

        <input type="text" name="extra_pass" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Extra pass...."><br>

        <button type="submit" class="submit-nxt" name="save">Save</button>
    @elseif (session('edit_server'))
        @php
            $server = session('server');
        @endphp
        <input type="text" name="ip" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Ip addr..." value="{{ $server->ip }}"><br>

        <input type="text" name="port" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Port..." value="{{ $server->port }}"><br>

        <input type="text" name="user_name" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="User Name...." value="{{ $server->username }}"><br>

        <input type="text" name="password" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Password..." value="{{ $server->password }}"><br>

        <input type="text" name="type" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Type....[wallet, daemon, api]" value="{{ $server->type }}"><br>

        <input type="text" name="extra_user" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Extra user..." value="{{ $server->extra_user }}"><br>

        <input type="text" name="extra_pass" class="form-input"
            style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Extra pass...." value="{{ $server->extra_pass }}"><br>

        <input type="hidden" name="id" value="{{ $server->id }}">
        <button type="submit" class="submit-nxt" name="save_edit">Save Edit</button>
    @else
        <input type="submit" name="new_server" value="Add New Server" class="input-listing">
    @endif
</form>

<table>
    <thead>
        <tr>
            <th>IP</th>
            <th>Port</th>
            <th>User Name</th>
            <th>Password </th>
            <th>is_tor</th>
            <th>Type</th>
            <th>Extra User</th>
            <th>Extra Pass</th>
            <th>Last Updated</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $servers = session('servers') ?? \App\Models\Server::all();
        @endphp
        @forelse ($servers as $server)
            <tr>
                <td>{{ $server->ip }}</td>
                <td>{{ $server->port }}</td>
                <td>{{ $server->username }}</td>
                <td>No pass</td>
                <td>{{ $server->is_tor }}</td>
                <td>{{ $server->type }}</td>
                <td>{{ $server->extra_user }}</td>
                <td>No pass</td>
                <td>{{ $server->updated_at->DiffForHumans() }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="id" id="" value="{{ $server->id }}">
                        <input type="submit" name="delete" id="" value="Delete"
                            style="color:red; cursor:pointer">
                        <input type="submit" style="color: darkgreen; cursor:pointer;" name="edit" value="Edit">
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No server found...</td>
            </tr>
        @endforelse
    </tbody>
</table>
