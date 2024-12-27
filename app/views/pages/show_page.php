<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <img
                        src="<?= $inventory['photo'] ? '/' . $inventory['photo'] : 'https://via.placeholder.com/600x400' ?>"
                        class="card-img-top"
                        alt="<?= $inventory['name'] ?>"
                        id="inventoryImage"
                        style="cursor: pointer;"
                        data-toggle="modal"
                        data-target="#imageModal">
                <div class="card-body">
                    <h5 class="card-title"><?= $inventory['name'] ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($inventory['description'])) ?></p>
                    <a href="<?= \app\core\Route::url('inventory', 'index') ?>" class="btn btn-secondary btn-sm">Back to Inventory</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <img
                        src="<?= $inventory['photo'] ? '/' . $inventory['photo'] : 'https://via.placeholder.com/600x400' ?>"
                        class="w-100"
                        alt="<?= $inventory['name'] ?>">
            </div>
        </div>
    </div>
</div>
