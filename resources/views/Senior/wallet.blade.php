
<div class="alert-box-div">
    <div class="listing-type">
        <legend>{{ $user->public_name }} > Wallet</legend>
        <h3>?
            <hr>
    </h3>
    <a href="/senior/staff/{{ $user->public_name }}/show/deposit">Deposit</a>
    <a href="/senior/staff/{{ $user->public_name }}/show/withdraw">Withdraw</a>
    <a href="{{ url()->previous() }}">Back</a>
</div>
</div>
