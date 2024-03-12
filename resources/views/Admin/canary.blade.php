
<div class="notific-container">
    <h1 style="margin: 0px;">Canary's</h1>
    <p class="notifications-p">Canary's will be updated every 30 days!</p>


    <div style="text-align: center; padding:0px; margin:0px;">
        <form action="/whales/admin/{{ $user->public_name }}/update/canary" method="post" style="margin: .3em;">
            @csrf
            @if (session('update_canary'))
                <textarea name="canary_message" id="" cols="30" rows="10" style="width: 100%;" placeholder="Enter here your sign canary message">{{ $user->canary->message_sign ?? null }}</textarea><br><br>
                <button type="submit" class="submit-nxt">Save</button>
            @else
                <button type="submit" class="input-listing" name="update_canary">Update Your Canary</button>
            @endif
        </form>

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
    </div>

    @forelse (\App\Models\MarketKey::all() as $canary)
    <div class="canary-div">
        <p class="{{ $canary->user->role ?? 'pending' }}">{{ $canary->user->role ?? 'System' }}/{{ $canary->user->public_name ?? "Market" }} -> Canary ~ Sign message - <span style="font-style: italic;">Last Updated: {{ $canary->updated_at->DiffForHumans() }}</span> </p><hr>
        <p style="white-space: pre-wrap;">{{ $canary->message_sign }}</p><hr>
    </div>
    @empty
        There are no canary yet.
    @endforelse
</div>
</div>

<style>
h1{
text-align: center;
color: var(--main-color);
}


.canary-div {
margin-bottom: 1.4em;
border-radius: 8px;
box-shadow: var(--shadow);
color: var(--dark-color-text);
border: 1px solid grey;
border-radius: .5rem;
}

p{
color: var(--dark-color-text);
margin-left: 2em;
margin-bottom: 1em;
word-wrap: break-word;

}


</style>