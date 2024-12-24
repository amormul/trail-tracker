<form action="<?= \app\core\Route::url('inventory', 'update') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $inventory['id']?>">
    <div class="container mt-5 pt-4 mb-5">
        <div class="row">
            <div class="col-12 bg-light shadow rounded py-4 px-4">
                <h1 class="mb-4">Edit Inventory Item</h1>
                <!-- Item Photo -->
                <div class="form-group d-flex">
                    <div class="border bg-white d-flex align-items-center justify-content-center mr-3"
                         style="width: 200px; height: 230px; position: relative; cursor: pointer;"
                         onclick="document.getElementById('itemPhoto').click();">
                        <?php if (!empty($inventory['photo'])): ?>
                            <img src="/storage/imageInventar/<?= $inventory['photo'] ?>"
                                 alt="Item Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <span class="text-secondary font-weight-bold" style="font-size: 48px;">+</span>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="photo" class="form-control-file d-none" id="itemPhoto"/>
                    <div class="w-100">
                        <!-- Item Name -->
                        <div class="form-group">
                            <label for="itemName">Name</label>
                            <input type="text" name="name" class="form-control" id="itemName"
                                   placeholder="Enter item name"
                                   value="<?= $inventory['name'] ?>" />
                        </div>

                        <!-- Item Description -->
                        <div class="form-group">
                            <label for="itemDescription">Description</label>
                            <textarea name="description" class="form-control" id="itemDescription"
                                      rows="4" placeholder="Enter item description"><?= $inventory['description']?></textarea>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                    <a href="<?= \app\core\Route::url('inventory', 'index') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
