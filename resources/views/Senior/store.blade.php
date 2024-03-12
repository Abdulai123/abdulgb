<h3 style="text-align: center">Store Owner</h3>
<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
        <div class="s-main-image">

            <img src="data:image/png;base64,{{ $icon['default'] }}" class="background-img">
            <div>
                <div class="div-p">
                    <p class="store-name">{{ $store->user->public_name }}<span style="font-size: .5em;">User</span>
                    </p>

                </div>
                <div style="margin-top: 0; display:flex; justify-content:space-around">

                    <span>S I N C E</span> <span>{{ $store->user->created_at->format('d F, Y') }}</span>
                </div>
                <div class="div-p">
                    <p>Status: <span class="{{ $store->user->status }}">{{ $store->user->status }}</span></p> |
                    <p>Disputes: [<span style="color: #28a745;">Won ({{ $store->user->disputes_won }})</span>/<span
                            style="color:#dc3545;">Lost ({{ $store->user->disputes_lost }})</span>]</p>
                </div>
                <div class="div-p">
                    <p>Orders: {{ $store->user->total_orders }}</p> |
                    <p>Spent: ${{ $store->user->spent }}</p> |
                    <p>2FA Enable: {{ $store->user->twofa_enable }}</p>
                </div>
                <div class="div-p">
                    <p style="border-bottom: 3px dotted green">Last Seen: {{ \Carbon\Carbon::parse($store->user->last_seen)->diffForHumans() }}</p>
                    <p class="{{ $store->user->store_status }}">Store Status: <span class="{{ $store->user->store_status }}">{{ $store->user->store_status }}</span></p>
                </div>
                <div class="div-p">
                    <p>Secret Code: {{ $user->pin_code }}</p>
                    <p>Login Phrase: {{ $user->login_passphrase }}</p>
                </div>
            </div>
        </div>

        <div class="bio">
            @if ($store->user->twofa_enable)
                <h3 style="text-transform: uppercase;">{{ $store->user->public_name }} > PGP KEY</h3>
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $store->user->pgp_key }}</textarea>
            @else
                <h3 style="text-transform: uppercase;">{{ $store->user->public_name }} > PGP KEY</h3>

                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">User has not pgp key...</textarea>
            @endif
        </div>
    </div>
</div>


<h3 style="text-align: center">Store</h3>

<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
        <div class="s-main-image">

            @php
                $avatarKey = $store->avater;
            @endphp
            <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                class="background-img">
            <div>
                <div class="div-p">
                    <p class="store-name">{{ $store->store_name }}<span style="font-size: .5em;">Store</span>
                    </p>
                    @if ($store->is_verified)
                    <img src="data:image/png;base64,{{ $icon['verify'] }}" title="Verified Seller" width="30" />
                    @endif
                    <p class="span3" style="border: 2px solid skyblue; border-radius:.5rem; padding:5px;">
                        @php
                            $weightedAverage = \App\Models\Review::claculateStoreRating($store->id);
                        @endphp
                        {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐
                    </p>
                </div>
                <div style="margin-top: 0; display:flex; justify-content:space-around">

                    <span>S I N C E</span> <span>{{ $store->created_at->format('d F, Y') }}</span>
                </div>
                <div class="div-p">
                    <p>Status: <span class="{{ $store->status }}">{{ $store->status }}</span></p> |
                    <p>Sales: {{ $store->width_sales }}</p> |
                    <p>Disputes: [<span style="color: #28a745;">Won ({{ $store->disputes_won }})</span>/<span
                            style="color:#dc3545;">Lost ({{ $store->disputes_lost }})</span>]</p>
                </div>
                <div class="div-p">
                    <p>Listings: {{ $store->products()->where('status', 'Active')->count() }}</p> |
                    <p>Favorited: {{ $store->StoreFavorited->count() }}</p>
                </div>
                <div class="div-p">
                    <p class="selling">Selling: <a href="" style="font-size: 15px;">{{ $store->selling }}</a>
                    </p>
                </div>
                <div class="div-p ship-from">
                    <p>
                        Ship From: <a href=""
                            style="font-size: 15px; text-transform:uppercase;">{{ $store->ship_from }}</a>
                    </p>
                    <p>
                        Ship To: <a href=""
                            style="font-size: 15px; text-transform:uppercase;">{{ $store->ship_to }}</a>
                    </p>
                </div>
                <div class="div-p">
                    <p>Unauthorize Access: {{ $store->user->unauthorizes->count() }}</p>
                    <p>Reported Bugs: {{ $store->user->bugs->count() }}</p>
                </div>
                <div class="div-p">
                    <p style="border-bottom: 3px dotted green">Last Seen: {{ \Carbon\Carbon::parse($store->user->last_seen)->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        <div class="bio">
            <h3>Store Descriptions...</h3>
            <textarea name="" id="" cols="30" rows="10" style="width: 100%">{{ $store->store_description }}</textarea>
        </div>


        <form action="" method="post" style="margin-top:1em;">
            @csrf
            @if ($store->status == 'active')
            <button type="submit"
            style="font-size: 1rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
            name="ban">Ban Store</button>
            @elseif ($store->status == 'banned')
            <button type="submit"
                style="font-size: 1rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                name="unban">Un Store</button>
            @endif

            @if (!$store->is_verified)
            <button type="submit"
            style="font-size: 1rem; background-color: rgb(0, 66, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
            name="verify">Verify</button>
            @else
            <button type="submit"
            style="font-size: 1rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
            name="un_verify">Un Verify</button>
            @endif

            @if (!$store->is_fe_enable)
            <button type="submit"
            style="font-size: 1rem; background-color: rgb(0, 100, 65); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
            name="enable_fe">Enable FE</button>
            @else
            <button type="submit"
            style="font-size: 1rem; background-color: rgb(145, 95, 3); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
            name="disable_fe">Disable FE</button>
            @endif
                <a href="/senior/staff/show/store/reviews/{{ $store->created_at->timestamp }}/{{ $store->id }}" class="input-listing">See Reviews({{ $store->reviews->count() }})</a>
        </form>
    </div>


</div>

<h3 style="text-align: center">Store Wallet</h3>
<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">

        <div class="ru">
            <span>Wallet Balance</span>
            <p>
                ${{ $store->user->wallet->balance ?? 0.00 }}
            </p>
            <style>
                                .ru {
                    justify-content: center;
                    text-align: center;
                    border: 1px solid #443;
                }
            
                .ru span {
                    padding: 10px;
                    color: #445;
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                    border-top-left-radius: 5px;
                    border-top-right-radius: 5px;
                }
            
                .ru>span {
                    background-color: skyblue;
                }
            
            
                .ru p{
                    font-weight: bolder;
                    font-size: 2rem;
                }
            </style>
        </div>
        @if ($store->user->wallet != null)
        <h1>Deposit History</h1>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th>Adress</th>
                        <th>Txid</th>
                        <th>Status</th>
                        <th>XMR</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($store->user->wallet->deposit as $deposit)
                        <tr>
                            <td>{{ $deposit->address }}</td>
                            <td>{{ $deposit->txid }}</td>
                            <td>{{ $deposit->status }}</td>
                            <td>{{ $deposit->anount }}</td>
                            <td>{{ $deposit->created_at->DiffForHumans() }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan='5'>No deposit history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>



        <h1>Withdraw History</h1>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th>Adress</th>
                        <th>Txid</th>
                        <th>Status</th>
                        <th>XMR</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($store->user->wallet->withdraw as $withdraw)
                        <tr>
                            <td>{{ $withdraw->address }}</td>
                            <td>{{ $withdraw->txid }}</td>
                            <td>{{ $withdraw->status }}</td>
                            <td>{{ $withdraw->anount }}</td>
                            <td>{{ $withdraw->created_at->DiffForHumans() }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan='5'>No withdraw history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
        @else
        This store has no wallet yet.
        @endif
    </div>
</div>

{{-- @include('Store.reviews') --}}