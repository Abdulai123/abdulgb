<div class="first-container">
    <legend style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 2rem; text-align: center;">Digital
        Product Information</legend>

</div>
<p style="font-weight: bold; text-align: center; color: red;">Remove all meta data from your images!!!</p>
<p style="text-align: center; color: #007bff; margin:0px; padding:0px;">Number of products remaining for today: 
    <span style="color: #dc3545;">
        ({{ 10 - $store->products()->whereDate('created_at', \Carbon\Carbon::today())->count() }}/10)
    </span>
</p>

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
<form action="/store/{{ $store->store_name }}/do/create/listing/digital" method="post" class="form-container" enctype="multipart/form-data">

    @csrf

    @if (session('next-form'))


    <table>
        <tr>
            <th>Shipping Extra/Options (You can edit the first one the change the default price or name)</th>
            <th>Cost</th>
        </tr>
    
        <tr>
            <td><input type="text" class="form-input" name="shipping_method1" placeholder="Shipping Option/Extra Option" value="Default" required></td>
            <td><input type="number" class="form-input" name="shipping_cost1" min="0.00" placeholder="Price" value="0.00" required></td>
        </tr>
        
        @for ($i = 2; $i <= 10; $i++)
            <tr>
                <td><input type="text" class="form-input" name="shipping_method{{ $i }}" placeholder="Shipping Option/Extra Option"></td>
                <td><input type="number" class="form-input" name="shipping_cost{{ $i }}" min="0" placeholder="Price"></td>
            </tr>
        @endfor
    
        <input type="hidden" name="product_id" value="{{ session('product_id') }}">
    </table>
        <div style="display: flex; justify-content:space-between;">
            <input type="submit" name="extra_set" class="submit-nxt" value="Save">
            <span>Page 2/2</span>
        </div>
    @else
        <label for="product_name" class="form-label">Product Name:</label>
        <input type="text" id="product_name" class="form-input" name="product_name"
            placeholder="Product name should be descriptive"  value="{{ old('product_name') }}" required>

        <span style="text-align: center; color: darkred;">--Sellers are advised to make good documentation for their
            item ( with photo timestames: include a piece of paper with Whale Market, Store Name and date in the
            pictures)</span>

        <label for="product_description" class="form-label">Product Description:</label>
        <textarea id="product_description" class="form-textarea" name="product_description" value="{{ old('product_description') }}"
            placeholder="Describe your product well here... your white space will remain as it is." required></textarea>

        <label for="price" class="form-label">Price USD:</label>
        <input type="text" id="price" class="form-input" name="price" value="{{ old('price') }}" placeholder="$0.00" required>

        <label for="quantity" class="form-label">Quantity:</label>
        <input type="number" id="quantity" class="form-input" name="quantity" min="1"
            placeholder="eg, 10000000" value="{{ old('quantity') }}" required>

        <label for="ship_to" class="form-label">Ship To:
            <select name="ship_to" id="">
                <option>World Wide</option>
                @include('User.countries')
            </select>
        </label>
        
        <label for="payment_type" class="form-label">Payment System Type:
            <select name="payment_type" class="form-select" id="" required>
                <option value="Escrow">Escrow</option>
                @if ($store->is_fe_enable)
                    <option value="FE">Finalize Early</option>
                @endif
            </select>
        </label>
        <label for="parent_category" class="form-label">Parent Category:
            <select name="parent_category_id" class="form-select" id="" required>
                <option value="">----Select----</option>
                @foreach ($categories->where('parent_category_id', null) as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </label>
        <input type="hidden" name="product_type" value="digital">
        <label for="parent_category" class="form-label">Sub Category ( parent category > sub category ):
            <select name="sub_category_id" class="form-select" required="required"
                title="Please seletct the right sub category, base on your parent category.">
                <option value="">----Select----</option>
                @foreach ($categories->where('parent_category_id', '!=', null) as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->category }} > {{ $category->name }}
                    </option>
                @endforeach
            </select></label>
        <label for="image_path1" class="form-label">First Image (required)</label>
        <input type="file" class="form-input" id="image_path1" name="image_path1"
            accept="image/png, image/jpeg, image/jpg" required value="{{ old('image_path1') }}"><br>

        <label for="image_path2" class="form-label">Second Image:</label>
        <input type="file" id="image_path2" class="form-input" name="image_path2" value="{{ old('image_path2') }}"
            accept="image/png, image/jpeg, image/jpg"><br>

        <label for="image_path3" class="form-label">Third Image:</label>
        <input type="file" id="image_path3" class="form-input" name="image_path3" value="{{ old('image_path3') }}"
            accept="image/png, image/jpeg, image/jpg"><br>

        <label for="auto_delivery_content" class="form-label">Auto Delivery (Automatic Dispatch) Content
            (Optional):</label>
        <textarea id="auto_delivery_content" class="form-textarea" name="auto_delivery_content" value="{{ old('auto_delivery_content') }}"
            placeholder="Optional: Enter the digital product to be sent to users upon successful purchase. This content will be shared with all buyers."></textarea>

        <label for="return_policy" class="form-label">Condition in which return is allow (Optional):</label>
        <input type="text" class="form-input" name="return_policy" value="{{ old('return_policy') }}"
            placeholder="Write it here this product return policy... your white space will remain as it is">
        <input type="hidden" name="store_id" value="{{ $store->id }}">

        <div id="capatcha-code-img">
            <img src="/user/store/captcha" alt="Captcha Image">
            <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                placeholder="Captcha..." required>
        </div>
        
        <div style="display: flex; justify-content:space-between;">
            <input type="submit" name="next" class="submit-nxt" value="Next">
            <span>Page 1/2</span>
        </div>

    @endif
</form>
