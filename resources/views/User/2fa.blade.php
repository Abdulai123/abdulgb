<style>
    .container-pgp {
        display: flex;
        justify-content: center;
        border-radius: .5rem;
    }

    form {
        max-width: 500px;
        text-align: center;
    }

    h1 {
        color: var(--dark-color-text);
        font-size: 1.2em;
        margin-bottom: 1em;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    textarea {
        width: 100%;
        height: 50vh;
        padding: 0.5em;
        border: none;
        margin-bottom: 1em;
        border-radius: 0.5rem;
        box-sizing: border-box;
        outline: none;
        border-radius: 0.5rem;
        box-shadow: var(--shadow);
        box-sizing: border-box;
        background-color: var(--white-background-color);
        color: var(--dark-color-text);
    }

    textarea:focus {
        border: 1px solid gray;
        outline: none;
    }

    input[type="number"] {
        padding: 15px;
        width: calc(100% - 20%);
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 20px;
        outline: none;
        background-color: var(--white-background-color);
        color: var(--dark-color-text);
        border: none;
        border-radius: 0.5rem;
        box-shadow: var(--shadow);
        box-sizing: border-box;
    }

    .two-btns {
        display: flex;
        justify-content: center;
        margin-top: 2em;
    }

    label {
        color: var(--dark-color-text);
    }

    .save-pgp {
        background-color: #2f9b5a;
        color: var(--dark-color-text);
        padding: 0.5em 1em;
        border: none;
        border-radius: 5px;
        font-size: 1.2em;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
        border-radius: 0.5rem;
        box-shadow: var(--shadow);
        box-sizing: border-box;
    }

    .save-pgp:hover {
        background-color: #238c4b;
    }

    .close:hover {
        background-color: rgba(224, 8, 8, 0.801);
    }
    .two-btns-prv {
        display: flex;
        justify-content: space-between;
        margin-top: 2em;
    }

    input[type=submit] {
        background-color: #2f9b5a;
        color: #fff;
        padding: 0.5em 1em;
        border: none;
        border-radius: 5px;
        font-size: 1.2em;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
        border-radius: 0.5rem;
        box-shadow: 0 1px 1px 0 rgba(48, 48, 48, .30), 0 1px 3px 1px rgba(48, 48, 48, .15);
        box-sizing: border-box;
    }

    input[type=submit]:hover {
        background-color: #238c4b;
    }

    input[name=skip] {
        background-color: #eee;
        color: #444;
    }

    input[name=skip-for-now]:hover {
        background-color: #ccc;
    }

    pre {
        width: 100%;
        height: fit-content;
        padding: 0.5em;
        border: 2px solid #0b3996;
        margin-bottom: 1em;
        border-radius: 0.5rem;
        box-sizing: border-box;
        background-color: var(--white-background-color);
        color: var(--dark-color-text);
        cursor: text;
        /* Add text cursor for better indication it's editable */
        white-space: pre-wrap;
        /* Preserve white spaces and wrap lines */
        user-select: all;
        /* Make the content selectable */

    }
</style>


<div class="main-div">
    <div class="notific-container">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p style="color: red; text-align:center;">{{ $error }}</p>
            @endforeach
        @endif

        @if (session('success'))
            <p style="color: green; text-align: center;">{{ session('success') }}</p>
        @endif

        <div class="container-pgp">

            @if (session('encrypted_message_verify'))

                <div class="cont">
                    <form action="/pgp" method="post">
                        @csrf
                        <h1>VERIFY PGP TOKEN</h1>

                        <div style="margin: 10px;">

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <p style="color: red; text-align:center;">{{ $error }}</p>
                                @endforeach
                            @endif

                            @if (session('success'))
                                <p style="color: green; text-align: center;">{{ session('success') }}</p>
                            @endif
                        </div>
                        <pre style="text-align: left; word-wrap: break-word;" contenteditable="true">{!! session('encrypted_message') !!}</pre>
                        <label for="2fa"
                            title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!">Enable
                            2FA?
                            <input type="checkbox"
                                title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
                                class="enable2fa" name="enable2fa" {{ $user->twofa_enable == true ? 'checked' : '' }}>
                        </label>
                        <div class="two-btns-prv">
                            <input type="text" name="pgp_token" minlength="10" maxlength="10"
                                placeholder="Enter your login token..." required>
                            <input type="submit" name="save_key" value="Verify">
                        </div>
                    </form>
                </div>
            @else
                <form action="" method="post">
                    @csrf
                    <h1>PGP KEY [2FA]</h1>
                    <textarea name="public_key" id="" cols="60" rows="10"
                        placeholder="-----BEGIN PGP PUBLIC KEY BLOCK----- ...,                    The User-ID must be the same as your Public Name...">{{ $user->pgp_key ?? $user->pgp_key }}</textarea><br><br>
                    <input type="number" name="secret_code" maxlength="6" minlength="6"
                        placeholder="Enter Secret code..." required><br><br>
                    <br>
                    <div id="capatcha-code-img">
                        <img src="/user/captcha" alt="Captcha Image">
                        <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                            placeholder="Captcha..." required>
                    </div>
                    <div class="two-btns">
                        <input type="submit" class="save-pgp" value="Update">
                    </div>
                </form>

            @endif
        </div>
    </div>
</div>
