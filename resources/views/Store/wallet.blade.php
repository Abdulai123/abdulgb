
<div class="alert-box-div">
    <div class="listing-type">
        <legend>{{ $store->store_name }} > Wallet</legend>
        <h3>?
            <hr>
    </h3>
    <a href="/store/{{ $store->store_name }}/show/deposit">Deposit</a>
    <a href="/store/{{ $store->store_name }}/show/withdraw">Withdraw</a>
    <a href="{{ url()->previous() }}">Back</a>
</div>
</div>
