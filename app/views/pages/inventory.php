<div class="container mt-5 pt-4 mb-5">
    <div class="row">
        <div class="col-12 bg-light shadow rounded py-4 px-4">
            <div class="d-flex justify-content-between mb-3">
            <h1>Inventory</h1>
            <button class="btn btn-success btn-sm">Add Item</button>
            </div>

            <!-- Inventory Cards -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= $inventory['photo'] ?>" alt="<?= $inventory['name'] ?>" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $inventory['name'] ?></h5>
                            <p class="card-text"><?= $inventory['descriptions'] ?></p>
                            <div class="mt-auto d-flex justify-content-end">
                                <button class="btn btn-primary btn-sm mr-2">Update</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
