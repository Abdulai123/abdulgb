<style>
    form {
        text-align: center;
        background-color: inherit;
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

<h1>STORE RULES</h1>
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
    <form action="" method="post">
        @csrf

        @if (session('new_rule'))
            <input type="text" name="rule" class="form-input"
                style="background-color: var(--white-background-color); color: var(--dark-color-text)"
                placeholder="Write here your rule that users and store should do or shouldnt do..."><br><br>
            <button type="submit" class="submit-nxt" name="save">Save</button>
        @elseif (session('edit_rule'))
            @php
                $rule = session('rule');
            @endphp
            <input type="text" class="form-input"
                style="background-color: var(--white-background-color); color: var(--dark-color-text)"
                placeholder="Write here your rule that users and store should do or shouldnt do..." name="rule"
                value="{{ $rule->name }}"><br><br>
            <input type="hidden" name="rule_id" value="{{ $rule->id }}">
            <button type="submit" class="submit-nxt" name="save_edit">Save Edit</button>
        @else
            <input type="submit" name="new_rule" value="Add New Rule" class="input-listing">
        @endif
    </form>
    <div class="s-rules">
        <ol>
            @foreach (\App\Models\StoreRule::all() as $rule)
                <form action="" method="post" style="text-align: left;">
                    @csrf
                    <input type="hidden" name="rule_id" value="{{ $rule->id }}">
                    <li>{{ $rule->name }} <input type="submit" name="edit" value="Edit"
                            style="color: green; cursor: pointer;"> @unless ($rule->is_xmr)
                            <input type="submit" name="delete" id=""
                                style="margin-left:2em; color: red;cursor:pointer;" value="Delete">
                        @endunless
                    </li>
                </form>
            @endforeach
        </ol>
    </div>

</div>
