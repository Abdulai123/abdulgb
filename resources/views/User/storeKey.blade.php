

<style>
    form {
        text-align: center;
        background-color: inherit;
    }

    h1 {
        color: var(--main-color);
        font-size: 2em;
        margin-bottom: 1em;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .accpet-and-continue {
        background-color: #0b3996;
        color: var(--dark-color-text);
        padding: 0.5em 1em;
        border: none;
        border-radius: 5px;
        font-size: 1.2em;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
        border-radius: 0.5rem;
        box-shadow: 0 1px 1px 0 rgba(48, 48, 48, .30), 0 1px 3px 1px rgba(48, 48, 48, .15);
        box-sizing: border-box;
        overflow-wrap: break-word;
        margin: 10px;
    }

    ol {
        text-align: left;
        color: var(--dark-color-text);
        font-family: Arial, Helvetica, sans-serif;
    }

    ol>li {
        line-height: 1.8;
        overflow-wrap: break-word;
    }

    .complete label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        color: #0b3996;
    }

    .complete input[type="checkbox"] {
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #0b3996;
        outline: none;
        cursor: pointer;
    }

    .complete input[type="checkbox"]:checked::before {
        content: "\2714";
        display: block;
        text-align: center;
        font-size: 1.5rem;
        line-height: 1;
        color: var(--dark-color-text);
        background-color: #555;
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }

    .complete input[type="checkbox"]:focus-visible {
        box-shadow: 0 0 0 2px #ddd;
    }

    .help-area {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .help-area>span {
        font-size: 1rem;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        line-height: 2;
        color: var(--dark-color-text);
    }

    .help-area>span>a {
        font-size: 1rem;
    }
</style>

<div class="main-div">
    <div class="notific-container">
        <form action="" method="post">
            @csrf
            <h1>STORE RULES</h1>
            <p>
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li style="color:red;">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </p>
            <p style="color: green; text-align: center;">{{ session('success') }}</p>
            <div class="s-rules">
                <ol>
                    @foreach (\App\Models\StoreRule::all() as $rule)
                        @unless ($rule->is_xmr)
                            <li>{{ $rule->name }}</li>
                        @endunless
                    @endforeach
                </ol>
            </div>
            @if ($user->twofa_enable == 'yes')
            <div class="complete">
                <label>
                    <input type="checkbox" name="complete" required>
                    <span>I have read everything and understood, and I agree to the rules.</span>
                </label>
               
                        <input type="submit" class="accpet-and-continue" name="accetp-and-Continue"
                            value="Pay {{ \App\Models\StoreRule::where('is_xmr', true)->first()->name }} XMR & Continue" style="color: var(--text-color-for-blue-bg);">

            </div>
            <div class="help-area">
                <span>You need a support? <a href="/ticket">[enter here]</a></span>
                <span>Looking for store waiver? <a href="/store/waiver">[enter here]</a> </span>
            </div>
            @else
            <p style="color: red;">PLease Enable 2FA to continue...</p>
            @endif
        </form>
    </div>
</div>
