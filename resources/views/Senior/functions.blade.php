<h1>
    Market Functions</h1>
    <p style="text-align: center; color:red;">Admins, Moderatores can still login even if the system is disable!</p>

<div>
    @if (session('success') != null)
        <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
            {{ session('success') }}</p>
    @endif
    @if ($errors->any)
        @foreach ($errors->all() as $error)
            <p style="padding: 10px; margin: 10px; border-radius: .5rem; background-color: #dc3545">
                {{ $error }}
            </p>
        @endforeach
    @endif
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Enable</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach (\App\Models\MarketFunction::all() as $function)
            <tr>
                <td>#{{ $function->id }}</td>
                <td>{{ $function->name }}</td>
                <td>
                    @if ($function->enable == 1)
                        <span style="color:green; font-weight:900;">True</span>
                    @else
                        <span style="color:red; font-weight:900;">False</span>
                    @endif
                </td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="id" id="" value="{{ $function->id }}">
                        @if ($function->enable == 1)
                            <input type="submit" name="disbale"
                                style="background-color:darkred; color:#f1f1f1; cursor:pointer;" id=""
                                value="Disable">
                        @else
                            <input type="submit" name="enabale"
                                style="background-color:darkgreen; color:#f1f1f1; cursor:pointer;" id=""
                                value="Enable">
                        @endif
                    </form>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
