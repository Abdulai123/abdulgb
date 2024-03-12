<div class="first-container">
    <legend style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 2rem; text-align: center;">Digital
        Product Information</legend>

</div>
@if (session('success') != null)
    <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
        {{ session('success') }}</p>
@endif
<div>
    @if ($errors->any())
        <ul style="margin: auto; list-style-type: none; padding: 0; text-align: center;">
            @foreach ($errors->all() as $error)
                <li style="color: red;">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

</div>
<form action="/store/{{ $store->store_name }}/do/product-edit/{{ $product->created_at->timestamp }}/{{ $product->id }}" method="post" class="form-container" enctype="multipart/form-data">

    @csrf

    @if (session('next-form'))

    @php
    $extraOption_counts = $product->extraShipping->count();
@endphp

<table>
    <tr>
        <th>Shipping Extra/Options (Blank is free)</th>
        <th>Cost</th>
    </tr>

    @for ($i = 0; $i < 10; $i++)
        @if ($i < $extraOption_counts)
            @php
                $extraOption = $product->extraShipping[$i]; // Assuming extraShipping is a collection
            @endphp
            <tr>
                <td><input type="text" class="form-input" name="shipping_method{{ $i }}" placeholder="Shipping Option/Extra Option" value="{{ $extraOption->name }}"></td>
                <td><input type="number" class="form-input" name="shipping_cost{{ $i }}" min="0" placeholder="Price" value="{{ $extraOption->cost }}"></td>
            </tr>
        @else
            <tr>
                <td><input type="text" class="form-input" name="shipping_method{{ $i }}" placeholder="Shipping Option/Extra Option"></td>
                <td><input type="number" class="form-input" name="shipping_cost{{ $i }}" min="0" placeholder="Price"></td>
            </tr>
        @endif
    @endfor

    <input type="hidden" name="product_id" value="{{ session('product_id') }}">
</table>

        <div style="display: flex; justify-content:space-between;">
            <input type="submit" name="extra_set" class="submit-nxt" value="Save">
            <input type="submit" name="skip" class="submit-nxt" value="Skip">
            <span>Page 2/2</span>
        </div>
    @else
        <label for="product_name" class="form-label">Product Name (Not Editable):</label>
        <input type="text" class="form-input" value="{{ $product->product_name }}" disabled>

        <span style="text-align: center; color: darkred;">--Sellers are advised to make good documentation for their
            item ( with photo timestames: include a piece of paper with Whale Market, Store Name and date in the
            pictures)</span>

        <label for="product_description" class="form-label">Product Description:</label>
        <textarea id="product_description" class="form-textarea" name="product_description"
            placeholder="Describe your product well here... " required> {{ $product->product_description }}</textarea>

        <label for="price" class="form-label">Price USD:</label>
        <input type="text" id="price" class="form-input" name="price" placeholder="$0.00"
            value="{{ $product->price }}" required>

        <label for="quantity" class="form-label">Quantity:</label>
        <input type="number" id="quantity" class="form-input" name="quantity" min="1"
            placeholder="eg, 10000000" value="{{ $product->quantity }}" required>

        <label for="ship_to" class="form-label">Ship From:
            <input type="text" class="form-input" id="ship_from" name="ship_from"
                placeholder="eg. canada, germany.... Default: yout default country "
                value="{{ $product->ship_from }}">
        </label>

        <label for="ship_to" class="form-label">Ship To:
            <input type="text" class="form-input" id="ship_to" name="ship_to"
                placeholder="eg. canada, germany.... Default: World Wide " value="{{ $product->ship_to }}">
        </label>
        <input type="hidden" name="product_type" value="{{ $product->product_type }}">


        @if ($product->image_path2 == null)
            <label for="image_path2" class="form-label">Second Image:</label>
            <input type="file" id="image_path2" class="form-input" name="image_path2"
                accept="image/png, image/jpeg, image/jpg"><br>
        @endif

        @if ($product->image_path3 == null)
            <label for="image_path3" class="form-label">Third Image:</label>
            <input type="file" id="image_path3" class="form-input" name="image_path3"
                accept="image/png, image/jpeg, image/jpg"><br>
        @endif

        @if ($product->auto_delivery_content != null && $product->product_type == 'digital')
            <label for="auto_delivery_content" class="form-label">Auto Delivery (Automatic Dispatch) Content
                (Optional):</label>
            <textarea id="auto_delivery_content" class="form-textarea" name="auto_delivery_content"
                placeholder="Optional: Enter the digital product to be sent to users upon successful purchase. This content will be shared with all buyers.">{{ $product->auto_delivery_content }}</textarea>
        @endif

        <label for="return_policy" class="form-label">Condition in which return is allow (Optional):</label>
        <input type="text" class="form-input" name="return_policy"
            placeholder="Write it here this product return policy..." value="{{ $product->return_policy }}">

        <div style="display: flex; justify-content:space-between;">
            <input type="submit" name="save_next" class="submit-nxt" value="Save and Next">
            <input type="submit" name="skip_next" class="submit-nxt" value="Skip and Next">
            <span>Page 1/2</span>
        </div>

    @endif
</form>
