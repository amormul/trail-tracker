<div class="container mt-5 pt-4 mb-5">
    <div class="row">
        <div class="col-12 bg-light shadow rounded py-4 px-4">
            <div class="d-flex justify-content-between mb-3">
                <h1>Inventory</h1>
                <a href="<?= \app\core\Route::url('inventory', 'create')?>" class="btn btn-success btn-sm">Add Item</a>
            </div>

            <!-- Inventory Cards -->
            <div class="row">
                <?php foreach ($inventories as $inventory): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?= $inventory['photo'] ? $inventory['photo'] : 'https://via.placeholder.com/300x200' ?>" class="card-img-top" alt="<?= $inventory['name'] ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= $inventory['name'] ?></h5>
                                <p class="card-text"><?= $inventory['description'] ?></p>
                                <div class="mt-auto d-flex justify-content-end">
                                    <a href="/inventory/edit/<?= $inventory['id'] ?>" class="btn btn-primary btn-sm mr-2">Update</a>
                                    <form action="/inventory/delete" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $inventory['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" >Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
