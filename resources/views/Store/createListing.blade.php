@if (session('success') != null)
    <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
        {{ session('success') }}</p>
@endif
<div class="alert-box-div">
<div class="listing-type">
    <legend>Create a Listing
    </legend>
    <p style="text-align: center; color: #007bff; margin:0px; padding:0px;">Number of products remaining for today: 
        <span style="color: #dc3545;">
            ({{ 10 - $store->products()->whereDate('created_at', \Carbon\Carbon::today())->count() }}/10)
        </span>
    </p>
    <h3>?
        <hr>
    </h3>
    <a href="/store/{{ $store->store_name }}/show/create/listing/physical">Physical Listing</a>
    <a href="/store/{{ $store->store_name }}/show/create/listing/digital">Digital listing</a>
    <a href="{{ url()->previous() }}">Back</a>
</div>
</div>
