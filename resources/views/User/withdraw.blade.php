<div class="main-div">
    <div class="notific-container">
        <h1>Wallet > Withdraw</h1>
<div style="text-align: center">
    @if ($errors->any)
    @foreach ($errors->all() as $error)
        <p style="color: red; text-align:cenetr;">{{ $error }}</p>
    @endforeach
@endif
@if (session('success'))
    <p style="color: green; text-align:center;">{{ session('success') }}</p>
@endif
</div>
    
        <div class="wallet-section">
            <fieldset>
                <legend>Withdrawing Money</legend>
                <form action="" method="post" class="wallet-form">
                    @csrf
                    <div class="balance-item">
                        <p style="color:green;"><span>Wallet Balance</span><br><span style="font-weight: 800;">${{ $user->wallet->balance }} / {{ session('xmr') == null ? "xmr api problem open a support ticket." : round($user->wallet->balance/session('xmr'),3) }} XMR</span></p>
                    </div>
                    {{-- <div class="balance-item">
                        <p style="color:red"><span>Locked Balance (Escrow)</span><br><span style="font-weight: 800;">${{ $user->orders-> }} / 0.000 XMR</span></p>
                    </div> --}}

                    <input type="text" id="withdraw-xmr-address" class="input-field" name="address"
                    placeholder="XMR address" required>
                <input type="text" id="withdraw-amount" class="input-field" name="amount"
                    placeholder="Amount USD $0.00" required>
                    <input type="number" id="withdraw-amount" class="input-field" name="pin"
                    placeholder="Secret pin code" required>
                    <div id="capatcha-code-img">
                        <img src="/user/captcha" alt="Captcha Image">
                        <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                            placeholder="Captcha..." required>
                    </div>
                    <input type="submit" class="submit-nxt" value="Submit">
                </form>
            </fieldset>
        </div>
        

        <h3>Withdraw History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Infos</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user->wallet->withdraw->sortByDesc('created_at') as $withdraw)
                        <tr>
                            <td>
                                Address: <input type="text" name="" id="" value="{{ $withdraw->address }}"> <br>
                                Txid: <input type="text" name="" id="" value="{{ $withdraw->txid }}">
                            </td>
                            <td>{{ $withdraw->is_confirm == 0 ? "Pending" : "Completed" }}</td>
                            <td>{{ $withdraw->amount }} XMR</td>
                            <td>{{ $withdraw->created_at->DiffForHumans() }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan='4'>No withdraw history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
</div>

<style>
/* Reset some default styles for better consistency */
 p, fieldset, legend, form {
    margin: 0;
    padding: 0;
}


/* Style for the fieldset */
fieldset {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    margin: 20px auto;
    width: 50vw;
    background-color: var(--white-background-color);
}

/* Style for the legend */
legend {
    font-size: 1.2em;
    font-weight: bold;
    color: var(--main-color);
    margin-bottom: 10px;
}

/* Style for the form */
.wallet-form {
    display: flex;
    flex-direction: column;
}

/* Style for each balance item */
.balance-item {
    background-color: var(--secondary-white-bg);
    border-radius: 5px;
    margin-bottom: 10px;
    padding: 10px;
}

/* Style for the balance item text */
.balance-item p {
    margin: 0;
}

/* Style for the input fields */
.input-field {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Add some style to the XMR address input for better visibility */
#withdraw-xmr-address {
    background-color: var(--secondary-white-bg);
}

/* Add some style to the Amount input for better visibility */
#withdraw-amount {
    background-color: var(--secondary-white-bg);
}

/* Add hover effect on input fields */
.input-field:hover {
    border-color: #555;
}

</style>
