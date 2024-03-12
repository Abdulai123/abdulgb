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
    .two-ver-btns {
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
            @include('Store.verify')
            @else
                <form action="/store/{{ $store->store_name }}/do/pgp" method="post">
                    @csrf
                    <h1>PGP KEY [2FA]</h1>
                    <textarea name="public_key" id="" cols="60" rows="10"
                        placeholder="-----BEGIN PGP PUBLIC KEY BLOCK----- ...,                    The User-ID must be the same as your Public Name...">{{ $store->user->pgp_key ?? $store->user->pgp_key }}</textarea><br><br>
                    <input type="number" name="secret_code" maxlength="6" minlength="6"
                        placeholder="Enter Secret code..." required><br><br>
                    <div class="two-btns">
                        <input type="submit" class="save-pgp" value="Update">
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
