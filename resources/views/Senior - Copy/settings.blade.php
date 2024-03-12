<legend>SM Settings (Edit)</legend>
<form action="" method="post">
    <label for="store_name">SM Name:</label>
    <input type="text" id="store_name" name="store_name" placeholder="Store name" value="" required>

    <label for="store_description">SM Description (Bio):</label>
    <textarea id="store_description" name="store_description" required placeholder="Store bio..."></textarea>
    <label for="store_image">SM Image:</label>
    <input type="file" id="store_image" name="store_image" accept="image/png, image/jpeg, image/jpg" required><br>
    <label for="store_description">SM PGP key:</label>
    <textarea id="store_pgp" name="store_pgp" required placeholder="Public pgp key"></textarea>
    <label for="store_description">SM Status:</label>
    <select name="to-whom" id="">
        <option value="store-active">Active</option>
        <option value="store-vaccation">Vaccation</option>
        <option value="store-deactivation">Deactivate</option>
    </select>
    <input type="number" id="security-code" name="security-code" placeholder="Enter security code" required>
    <input type="submit" class="submit-nxt" name="" value="Save Changes">
</form>