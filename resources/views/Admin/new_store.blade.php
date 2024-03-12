
<h3 style="text-align: center">Store Owner</h3>
<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
        <div class="s-main-image">

            <img src="data:image/png;base64,{{ $icon['default'] }}" class="background-img">
            <div>
                <div class="div-p">
                    <p class="store-name">{{ $new_store->user->public_name }}<span style="font-size: .5em;">User</span>
                    </p>
                </div>
                <div style="margin-top: 0; display:flex; justify-content:space-around">

                    <span>S I N C E</span> <span>{{ $new_store->user->created_at->format('d F, Y') }}</span>
                </div>
                <div class="div-p">
                    <p>Status: <span class="{{ $new_store->user->status }}">{{ $new_store->user->status }}</span></p> |
                    <p>Disputes: [<span style="color: #28a745;">Won ({{ $new_store->user->disputes_won }})</span>/<span
                            style="color:#dc3545;">Lost ({{ $new_store->user->disputes_lost }})</span>]</p>
                </div>
                <div class="div-p">
                    <p>Orders: {{ $new_store->user->total_orders }}</p> |
                    <p>Spent: ${{ $new_store->user->spent }}</p> |
                    <p>2FA Enable: {{ $new_store->user->twofa_enable }}</p>
                </div>
                <div class="div-p">
                    <p style="border-bottom: 3px dotted green">Last Seen:
                        {{ \Carbon\Carbon::parse($new_store->user->last_seen)->diffForHumans() }}</p>
                    <p>Store Status: {{ $new_store->user->store_status }}</p>
                </div>
            </div>
        </div>

        <div class="bio">
            @if ($new_store->user->twofa_enable)
                <h3 style="text-transform: uppercase;">{{ $new_store->user->public_name }} > PGP KEY</h3>
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $new_store->user->pgp_key }}</textarea>
            @else
                <h3 style="text-transform: uppercase;">{{ $new_store->user->public_name }} > PGP KEY</h3>

                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">User has not pgp key...</textarea>
            @endif
        </div>
    </div>
</div>


<h3 style="text-align: center">Store Request</h3>

<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
        <div class="s-main-image">

            @php
                $avatarKey = $new_store->avater;
            @endphp
            <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                class="background-img">
            <div>
                <div class="div-p">
                    <p class="store-name">{{ $new_store->store_name }}<span style="font-size: .5em;">Store</span>
                    </p>
                </div>
                <div style="margin-top: 0; display:flex; justify-content:space-around">

                    <span>S I N C E</span> <span>{{ $new_store->created_at->format('d F, Y') }}</span>
                </div>
                <div class="div-p">
                    <p>Status: <span class="{{ $new_store->status }}">{{ $new_store->status }}</span></p>
                </div>
                <div class="div-p">
                    <p class="selling">Selling: <a href=""
                            style="font-size: 15px;">{{ $new_store->selling }}</a>
                    </p>
                </div>
                <div class="div-p ship-from">
                    <p>
                        Ship From: <a href=""
                            style="font-size: 15px; text-transform:uppercase;">{{ $new_store->ship_from }}</a>
                    </p>
                    <p>
                        Ship To: <a href=""
                            style="font-size: 15px; text-transform:uppercase;">{{ $new_store->ship_to }}</a>
                    </p>
                </div>
            </div>
        </div>
        @if ($new_store->sell_on != 'none')
            <h3>I was selling on</h3>
            <div>
                {{ $new_store->sell_on }}
            </div>
        @endif
        <h3>Products Proof</h3>
        <div class="sub-pics">
            <img src="data:image/png;base64,{{ $upload_image[$new_store->proof1] }}">
            <img src="data:image/png;base64,{{ $upload_image[$new_store->proof2] }}">
            <img src="data:image/png;base64,{{ $upload_image[$new_store->proof3] }}">
        </div>
        <div class="bio">
            <h3>Store Descriptions...</h3>
            <textarea name="" id="" cols="30" rows="10" style="width: 100%">{{ $new_store->store_description }}</textarea>
        </div>


        <form action="" method="post" style="margin-top:1em;">
            @csrf
            <input type="hidden" name="new_store_id" value="{{ Crypt::encrypt($new_store->id) }}">


            <button type="submit"
                style="font-size: 1rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                name="decline">Decline Store</button>


            <button type="submit"
                style="font-size: 1rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                name="approve">Approve Store</button>

        </form>
    </div>


</div>
