<style>
    body {
        margin: 0;
    }

    .cont {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2em;
    }

    form {
        max-width: 500px;
        text-align: center;
    }

    h1 {
        font-size: 2em;
        margin-bottom: 1em;
        color: #fff;
    }

    textarea {
        width: 100%;
        height: 10em;
        padding: 0.5em;
        border: 2px solid #0b3996;
        margin-bottom: 1em;
        border-radius: 0.5rem;
        box-sizing: border-box;
        background-color: rgb(2, 21, 40);
        color: #fff;
    }

    input[type=text] {
        background-color: rgb(2, 21, 40);
        color: #fff;
        border-radius: .5rem;
    }

    input[type=text]:focus {
        outline-color: none;
        border: none;
    }

    textarea:focus {
        outline-color: #238c4b;
    }

    .two-btns {
        display: flex;
        justify-content: space-between;
        margin-top: 2em;
    }

    input[type=submit] {
        background-color: #2f9b5a;
        color: var(--secondary-white-bg);
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
        background-color: rgb(2, 21, 40);
        color: #fff;
        cursor: text;
        /* Add text cursor for better indication it's editable */
        white-space: pre-wrap;
        /* Preserve white spaces and wrap lines */
        user-select: all;
        /* Make the content selectable */

    }
</style>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WHALES MARKET - ADD PGP KEY</title>
    <style>
        html {
            background-color: rgb(43, 43, 64);
            /*  rgb(43, 43, 64) rgba(0, 0, 0, 0) rgb(239, 242, 245)*/
        }
    </style>

<body>
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
                    title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
                    style="color: #ccc;">Enable 2FA? <span style="color:red">*</span>
                    <input type="checkbox"
                        title="If you enable 2FA when loggin you need to decrept a pgp sign message to log in!"
                        class="enable2fa" name="enable2fa" checked>
                </label>
                <div class="two-btns">
                    <input type="text" name="pgp_token" minlength="10" maxlength="10"
                        placeholder="Enter your token..." required>
                    <input type="submit" name="save_key" value="Verify">
                </div>
            </form>
        </div>
    @else
        <div class="cont">
            <form action="/pgp" method="post">
                @csrf
                <h1>ADD PGP PUBLIC KEY<span style="color: red;">*</span></h1>

                <div style="margin: 10px;">

                    @if ($errors->any)
                        @foreach ($errors->all() as $error)
                            <p style="color: red; text-align:cenetr;">{{ $error }}</p>
                        @endforeach
                    @endif
                </div>

                <textarea name="public_key" id="" cols="60" rows="10"
                    placeholder="-----BEGIN PGP PUBLIC KEY BLOCK----- ...,                    The User-ID must be the same as your Public Name...">{{ $user->pgp_key != null ? $user->pgp_key : null }}</textarea>
                <div class="two-btns">
                    <input type="submit" name="skip" value="Skip">
                    <input type="submit" name="save_key" value="Save">
                </div>
            </form>
        </div>

    @endif
</body>
