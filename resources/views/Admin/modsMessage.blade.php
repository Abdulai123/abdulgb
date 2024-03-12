<h1 class="notifications-h1" style="margin:0; padding:0px;;">_New Message For ModMail_</h1>
<form action="" method="post" class="support-form" style="max-width: 60%; border: 1px solid #ddd; padding: 10px;">
    @if ($errors->any)
        @foreach ($errors->all() as $error)
            <p style="color: red; text-align:cenetr;">{{ $error }}</p>
        @endforeach
    @endif
    @if (session('success'))
        <p style="text-align: center; color: green;">{{ session('success') }}</p>
    @endif
    @csrf
    <label for="sender" class="subject-label" style="width: fit-content;">Receiver:
        <select name="receiver" id="">
            <option value="general">General</option>
            @foreach ($staffs as $staff)
                <option value="{{ encrypt($staff->id) }}" class="{{ $staff->role }}">{{ $staff->public_name }}({{ $staff->role }})</option>
            @endforeach
        </select>
    </label>
    <label for="receiver" class="subject-label" style="width: fit-content;">Subject: <input type="text"
            name="subject" class="subject" style="border: none; font-size: 1rem;" placeholder="Message Subject..."
            required> </label>
    <textarea name="contents" placeholder="Write your message here... max 5K characters!" cols="30" rows="10"
        required></textarea>
    <input type="submit" class="submit-nxt" value="Send">
</form>
