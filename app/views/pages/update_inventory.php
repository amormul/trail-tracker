<!-- Main Content -->
<div class="container mt-5 pt-4 mb-5">
    <div class="row">
        <div class="col-12 bg-light shadow rounded py-4 px-4">
            <h1 class="mb-4">Edit Inventory Item</h1>
            <form>
            <!-- Item Photo -->
            <div class="form-group d-flex">
                <div class="border bg-white d-flex align-items-center justify-content-center mr-3" style="width: 200px; height: 230px; position: relative; cursor: pointer;" onclick="document.getElementById('itemPhoto').click();">
                    <span class="text-secondary font-weight-bold" style="font-size: 48px;">+</span>
                </div>
                <input type="file" class="form-control-file d-none" id="itemPhoto"/>
                <div class="w-100">
                <!-- Item Name -->
                <div class="form-group">
                    <label for="itemName">Name</label>
                    <input type="text" class="form-control" id="itemName" placeholder="Enter item name" value="Tent"/>
                </div>

                <!-- Item Description -->
                <div class="form-group">
                    <label for="itemDescription">Description</label>
                    <textarea class="form-control" id="itemDescription" rows="4" placeholder="Enter item description">A durable two-person tent for hiking adventures.</textarea>
                </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                <a href="#" class="btn btn-secondary">Cancel</a>
            </div>
            </form>
        </div>
    </div>
</div>
