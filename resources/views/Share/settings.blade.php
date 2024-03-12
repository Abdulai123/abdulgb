
<legend style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 2rem; text-align: center;">Store Settings (Edit)</legend>

@if (session('success'))
<p style="color:green; text-align:center;">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p style="color: red; text-align:center;">{{ session('error') }}</p>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color: red; text-align:center;">{{ $error }}</p>
    @endforeach
@endif
<div>
    <form action="/store/{{ $store->store_name }}/update/settings" method="post" class="form-container" enctype="multipart/form-data">
        @csrf
         <label for="store_description" class="form-label">Store Description (Bio):</label>
         <textarea id="store_description" class="form-textarea" name="store_description" required placeholder="Store bio...">{{ $store->store_description }}</textarea>
     
         <label for="store_description" class="form-label">Store PGP key:</label>
         <textarea id="store_pgp" name="store_pgp"  class="form-textarea" required placeholder="-----BEGIN PGP PUBLIC KEY BLOCK-----        -----END PGP PUBLIC KEY BLOCK-----">{{ $store->store_pgp }}</textarea>
     
         <label for="store_description" class="form-label">Selling:</label>
         <textarea id="store_pgp" name="selling"  class="form-textarea" required placeholder="Selling">{{ $store->selling }}</textarea>
     
         <label for="store_description" class="form-label">Ship From:</label>
         <textarea id="store_pgp" name="ship_from"  class="form-textarea" required placeholder="Ship from default 'unkwonn'">{{ $store->ship_from }}</textarea>
     
         <label for="store_description" class="form-label">Ship To:</label>
         <textarea id="store_pgp" name="ship_to"  class="form-textarea" required placeholder="Ship to default 'Worldwide'">{{ $store->ship_to}}</textarea>
     
         <label for="store_description" class="form-label">Store Status:</label>
         <select name="status" class="form-select" id="">
             <option value="{{ $store->status }}" style="text-transform: capitalize;">{{ $store->status }}</option>
             <option value="{{ ($store->status == 'active' ? 'vacation' : 'active') }}">{{ $store->status == 'active' ? 'Vacation' : 'Activate' }}</option>
         </select>
         <input type="hidden" name="store_id" value="{{ Crypt::encrypt($store->id) }}">
         <label for="image_path3" class="form-label">Store Avater:</label>
         <input type="file" id="avater" class="form-input" name="avater"
             accept="image/png, image/jpeg, image/jpg"><br>

         <input type="number" id="security-code" class="form-input" name="security_code" placeholder="Enter security code" required>
         
         <div style="display: flex; justify-content:space-between;">
            <input type="submit" class="submit-nxt"  name="save_profile" value="Save Changes">
            <span>Page 1/1</span>
        </div>
     </form>
</div>
