<legend style="text-align: center;">{{ $store->store_name }} > Coupons </legend>

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

@if (session('new_coupon'))
    <div style="text-align: center; margin: 1em;">
        <form action="" method="post" class="form-container">
            @csrf
            <select name="product" id="" class="form-select" required>
                <option value="">---Select Product---</option>
                @foreach ($store->products as $product)
                    <option value="{{ Crypt::encrypt($product->id) }}">{{ $product->product_name }} (${{ $product->price  }})</option>
                @endforeach
            </select>
            <select name="type" id="" class="form-select" required>
                <option value="">---Select Coupon Type---</option>
                <option value="fixed">Fixed</option>
                <option value="percentage">Percentage</option>
            </select>
            <input type="text" class="form-input" name="code" minlength="4" placeholder="Coupon code name (min length 4)..."
                required>
            <input type="text" class="form-input" name="discount" id="" placeholder="Discount..." required>
            <label for="" class="form-label">
                Expired Date: <input type="date" class="form-input" name="expired_date" id="" required>
            </label>
            <input type="text" class="form-input" name="usage_limit" placeholder="Usage limit eg(100.. )" required>
            <div id="capatcha-code-img">
                <img src="/user/store/captcha" alt="Captcha Image">
                <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                    placeholder="Captcha..." required>
            </div>
            <input type="submit" name="save" value="Save" class="submit-nxt">
        </form>
    </div>

@else
    <div style="text-align: center; margin: 1em;">
        <form action="" method="post">
            @csrf
            <input type="submit" name="new_coupon" value="Create New Coupon" class="input-listing">
        </form>
    </div>


@endif
<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Coupon Code</th>
            <th>Type</th>
            <th>Discount</th>
            <th>Expired Date</th>
            <th>Usage Limit</th>
            <th>Time Used</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($store->coupons as $promo)
            <tr>
                <td>
                    <img src="data:image/png;base64,{{ !empty($product_image[$promo->product->image_path1]) ? $product_image[$promo->product->image_path1] : $icon['default'] }}" width="35">
                </td>
                <td>{{ $promo->code }}</td>
                <td>{{ $promo->type }}</td>
                <td>{{ $promo->type == 'fixed' ? '$' . $promo->discount : $promo->discount . '%' }}</td>
                <td>{{ $promo->expiration_date }}</td>
                <td>{{ $promo->usage_limit }}</td>
                <td>{{ $promo->times_used }}</td>
                <td {{ $promo->status == 'expired' ? 'style=color:red;' : 'class=active' }}>{{ $promo->status }}</td>
                <td>{{ $promo->updated_at->DiffForHumans() }}</td>
                <td>
                    <form action="" method="post" style="display: flex; gap:.5em;">
                        @csrf
                        <input type="hidden" name="promo_id" value="{{ Crypt::encrypt($promo->id) }}">
                        <input type="submit" style="font-size: .7rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                            name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        @empty
            <td colspan="8">You do not have any coupons code yet....</td>
        @endforelse
    </tbody>
</table>
