<style>

    form {
        text-align: center;
    }

    input[type="number"],
    input[type="password"],
    input[type="text"] {
        padding: 15px;
        width: calc(100% - 20%);
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 20px;
        color: var(--dark-color-text);
        outline: none;
        background-color: var(--white-background-color);
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 1px 1px 0 rgba(48, 48, 48, 0.3),
            0 1px 3px 1px rgba(48, 48, 48, 0.15);
        box-sizing: border-box;
    }

    .two-btns {
        display: flex;
        justify-content: center;
        margin-top: 2em;
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
        box-shadow: 0 1px 1px 0 rgba(48, 48, 48, .30), 0 1px 3px 1px rgba(48, 48, 48, .15);
        box-sizing: border-box;
    }

    .save-pgp:hover {
        background-color: #238c4b;
    }
</style>
<div class="main-div">
    <div class="notific-container">
        <h1>Set Your Default Fiat Currency</h1>
        <div style="text-align: center">
<form action="" method="post">
    @csrf
    <select id="currencies">
        <option value="USD">United States Dollar (Default: USD) - $</option>
        <option value="EUR">Euro (EUR) - €</option>
        <option value="JPY">Japanese Yen (JPY) - ¥</option>
        <option value="GBP">British Pound Sterling (GBP) - £</option>
        <option value="CHF">Swiss Franc (CHF) - CHF</option>
        <option value="CAD">Canadian Dollar (CAD) - $</option>
        <option value="AUD">Australian Dollar (AUD) - A$</option>
        <option value="CNY">Chinese Yuan (Renminbi) (CNY) - ¥</option>
        <option value="SEK">Swedish Krona (SEK) - kr</option>
        <option value="NZD">New Zealand Dollar (NZD) - NZ$</option>
    </select>
</form>
        </div>
    </div>
</div>
