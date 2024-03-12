<div class="main-div">
    {{-- style="background-color: #85c1e9; padding: 15px; border-radius: 5px;" --}}
    <div class="notific-container">
        <h1 class="notification-h1">Wallet > Deposit</h1>

        <div>
            <div class="warning-message">
                <strong style="color: #FF3333;">🔒 Single Use Warning:</strong>
                <span>Use this subaddress for a single transaction to enhance your security. If you attempt multiple
                    transactions, your second deposit may be declined or forfeited.</span>
            </div>

            <div class="expiration-message">
                <strong style="color: #FF3333;">⏰ Expiration Notice:</strong>
                <span>This subaddress will expire in 1 hour. Please ensure to use it within this timeframe.</span>
            </div>

            <div class="transaction-timing">
                <p>
                    Typically, this should not take more than 20 minutes. However, during periods of high network
                    traffic, it may take longer.
                </p>
                <p>
                    Monero wallets require at least 10 confirmations for any transaction before being able to send or
                    swap.
                </p>
            </div>

            <div class="deposit-instructions">
                <span class="important-note">Please ensure that you initiate your XMR deposit within the specified time
                    frame and only deposit once to avoid any inconveniences.</span>
                <span class="support-message">If you have any questions, feel free to reach out to our support team. by
                    clicking <a href="/ticket">here</a> or go to "Settings > Support > Ticket"</span>
            </div>

            <div class="deposit-instructions">
                <p class="important-note">
                    To obtain your Monero deposit address, simply click the "Generate Monero Address" button below.
                </p>
            </div>

            <div style="text-align: center;">
                <a href="/{{ $user->public_name }}/generate/new/xmr/address" class="input-listing">Generate Monero
                    Address</a><br><br>
            </div>

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
            @if (session('address'))
                <div class="deposit-info-container">
                    <div class="qrcode-container">
                        <p class="qrcode-image">
                            {!! session('qrcode') !!}
                        </p>
                    </div>
                    <div class="deposit-details">
                        <p class="deposit-info-header">Deposit XMR to your account wallet</p>
                        <div class="deposit-address">
                            <p class="deposit-address-label">Deposit Address</p>
                            <p class="deposit-address-value">{{ session('address') }}</p>
                        </div>
                        <p class="address-expiry">This address expires in {{ $user->wallet->address->last()->created_at->DiffForHumans(now()->subHours(1)) }}</p>
                        <p class="confirmation-info">
                            After 1 confirmation, there will be a notification that an incoming transaction is detected.
                            After sending your monero please wait for 5 minutes and refresh the page.
                            The transaction is considered finalized after 10 confirmations.
                        </p>
                    </div>
                </div>
            @endif


        </div>

        <h3>Deposit History</h3>
        <table>
            <thead>
                <tr>
                    <th>Txid</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Confirmations</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $depositHistory[] = \App\Http\Controllers\WalletController::show();
                     // Add this line for debugging
                @endphp
                {{-- {{ dd($depositHistory) }} --}}
        
                @if (is_array($depositHistory) && !empty($depositHistory) && !isset($depositHistory[0]['message']))
                        <tr>
                            <td>{{ $depositHistory['0']['txid'] }}</td>
                            <td>{{ $depositHistory['0']['amount'] }} XMR</td>
                            <td>{{ $depositHistory['0']['status'] }}</td>
                            <td class="active">{{ $depositHistory['0']['confirmations'] <= 10 ? $depositHistory['0']['confirmations'].'/10' : '' }}</td>
                            <td>{{ $depositHistory['0']['date'] }}</td>
                        </tr>

                @elseif (is_array($depositHistory) && !empty($depositHistory) && isset($depositHistory[0]['message']))
                <tr>
                    
                    @if ($depositHistory[0]['message'] == 'null')
                        @php
                            $histories = $user->wallet->deposit;
                        @endphp
                        
                        @forelse ($histories->sortByDesc('created_at') as $history)
                        <tr>
                            <td>{{ $history->txid }}</td>
                            <td>{{ $history->amount }} XMR</td>
                            <td>Confirmed</td>
                            <td></td>
                            <td>{{ $history->created_at->DiffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No deposit history found.</td>
                        </tr>
                        @endforelse
                    @else
                    <tr>
                        <td colspan="5">{{ $depositHistory[0]['message'] }}</td>
                    </tr>
                    @endif
                </tr>
                @else
                    <tr>
                        <td colspan="5">No deposit history found.</td>
                    </tr>
                @endif
            </tbody>
        </table>         
    </div>
</div>
