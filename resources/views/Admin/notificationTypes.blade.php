
<h1>Notifications Type</h1>
<div class="notific-container">
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
<div style="margin-bottom: 1em;">
    <form action="" method="post" style="text-align: center;">
        @csrf

        @if (session('edit'))
            @php
                $notificationType = session('notificationType');
            @endphp
            <input type="text" class="form-input"
                style="background-color: var(--white-background-color); color: var(--dark-color-text)"
                placeholder="Write here your rule that users and store should do or shouldnt do..." name="name"
                value="{{ $notificationType->name }}"><br><br>
                <textarea name="content" id="" cols="30" rows="10" style="width: 100%;"
                placeholder="Write here notification Type content">{{ $notificationType->content }}</textarea>
            <input type="hidden" name="notificationType_id" value="{{ $notificationType->id }}">
            <button type="submit" class="submit-nxt" name="save_edit">Save Edit</button>
        @endif
    </form>
</div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Content</th>
                <th>Action</th>
                <th>Icon</th>
                <th>Do</th>
            </tr>
        </thead>
        <tbody>
            @foreach (\App\Models\NotificationType::all() as $notificationType)
                <tr>
                    <td>{{ $notificationType->name }}</td>
                    <td>{{ $notificationType->content }}</td>
                    <td>{{ $notificationType->action }}</td>
                    <td>{{ $notificationType->icon }}</td>
                    <td>
                    <form action="" method="post" style="text-align: left;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $notificationType->id }}">
                        <input type="submit" name="edit" value="Edit"
                                style="color: green; cursor: pointer;"> 
                    </form>
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>


</div>
