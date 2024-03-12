<style>
    form {
        text-align: center;
        background-color: inherit;
    }

    h1 {
        color: var(--mian-color);
        font-size: 2em;
        margin-bottom: 1em;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .accpet-and-continue {
        background-color: #0b3996;
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
        color: #fff;
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


<div class="notific-container">
    <h1>STORE RULES</h1>
    <div class="s-rules">
        <ol>
            @foreach (\App\Models\StoreRule::all() as $rule)
                @unless ($rule->is_xmr)
                    <li>{{ $rule->name }}</li>
                @endunless
            @endforeach
        </ol>
    </div>
</div>
