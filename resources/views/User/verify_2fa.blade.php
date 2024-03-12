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
        color: var(--dark-color-text);
    }

    textarea {
        width: 100%;
        height: 10em;
        padding: 0.5em;
        border: 2px solid #0b3996;
        margin-bottom: 1em;
        border-radius: 0.5rem;
        box-sizing: border-box;
        background-color: var(--secondary-bg);
        color: var(--dark-color-text);
    }

    input[type=text] {
        background-color: var(--secondary-bg);
        color: var(--dark-color-text);
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
        box-shadow: var(--shadow)
        box-sizing: border-box;
    }

    input[type=submit]:hover {
        background-color: #238c4b;
    }

    input[name=skip] {
        background-color: var(--secondary-bg);
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
        background-color: var(--secondary-bg);
        color: var(--dark-color-text);
        cursor: text;
        /* Add text cursor for better indication it's editable */
        white-space: pre-wrap;
        /* Preserve white spaces and wrap lines */
        user-select: all;
        /* Make the content selectable */

    }
</style>

<title>VERIFY PGP 2FA TOKEN</title>
<div class="cont">
    <form action="" method="post">
        @csrf
        <h1>VERIFY PGP 2FA TOKEN</h1>

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

        <div class="two-btns">
            <input type="text" name="pgp_token" minlength="10" maxlength="10" placeholder="Enter your token..."
                required>
            <input type="submit" name="save_key" value="Verify">
        </div>
    </form>
</div>
