@if (session('success') != null)
    <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
        {{ session('success') }}</p>
@endif

<div class="listing-type">
    <legend>Create a Listing</legend>
    <h3>?
        <hr>
    </h3>
    <a href="/store/{{ $store->store_name }}/show/create/listing/physical">Physical Listing</a>
    <a href="/store/{{ $store->store_name }}/show/create/listing/digital">Digital listing</a>
</div>
