
<legend style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 2rem; text-align: center;">Settings (Edit)</legend>

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
    <form action="/senior/staff/{{ $user->public_name }}/update/settings" method="post" class="form-container" enctype="multipart/form-data">
        @csrf   
         <label for="store_description" class="form-label">Senior Mod Status:</label>
         <select name="status" class="form-select" id="">
             <option value="{{ $user->status }}" style="text-transform: capitalize;">{{ $user->status }}</option>
             <option value="{{ ($user->status == 'active' ? 'vacation' : 'active') }}">{{ $user->status == 'active' ? 'Vacation' : 'Activate' }}</option>
         </select>
         <label for="image_path3" class="form-label">Senior Mod Avater:</label>
         <input type="file" id="avater" class="form-input" name="avater"
             accept="image/png, image/jpeg, image/jpg"><br>

         <input type="number" id="security-code" class="form-input" name="security_code" placeholder="Enter security code" required>
         
         <div style="display: flex; justify-content:space-between;">
            <input type="submit" class="submit-nxt"  name="save_profile" value="Save Changes">
            <span>Page 1/1</span>
        </div>
     </form>
</div>
