<div class="cont">
    <form action="/whales/admin/{{ $user->public_name }}/verify/pgp" method="post">
        @csrf
        <h1>VERIFY PGP TOKEN</h1>
        <pre style="text-align: left; word-wrap: break-word;" contenteditable="true">{!! session('encrypted_message') !!}</pre>
        <label for="2fa"
            title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
            style="color: #ccc;">Enable 2FA? <span style="color:red">*</span>
            <input type="checkbox"
                title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
                class="enable2fa" name="enable2fa" checked>
        </label>
        <div class="two-ver-btns">
            <input type="text" name="pgp_token" minlength="10" maxlength="10"
                placeholder="Enter your token..." required>
            <input type="submit" name="save_key" value="Verify">
        </div>
    </form>
</div>