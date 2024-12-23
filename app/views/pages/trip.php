<div class="container mt-5 pt-4 mb-5">
    <div class="row">
        <div class="col-12 bg-light shadow rounded py-4 px-4">
            <div class="row">
                <!-- Left Photo Section -->
                <div class="col-md-3">
                    <div class="photo-section">
                        <img src="<?= $trip['photo'] ?>" alt="<?= $trip['name'] ?>" class="img-fluid rounded">
                    </div>
                    <!-- Likes -->
                    <div class="d-flex justify-content-between mt-2">
                        <form action="<?= app\core\Route::url('index', 'like') ?>" method="post">
                            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                            <input type="submit" class="btn btn-outline-danger btn-sm" value="&#x2764;">
                        </form>
                        <span><?= $trip['likes'] ?> Likes</span>
                    </div>
                </div>

                <!-- Right Content Section -->
                <div class="col-md-9">
                    <!-- Buttons Split Across the Row -->
                    <div class="d-flex justify-content-between mb-3">
                        <a href="<?= \app\core\Route::url() ?>" class="btn btn-primary btn-sm">Back</a>
                        <div class="d-flex align-items-center">
                            <form action="<?= app\core\Route::url('index', 'edit') ?>" method="post">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <input type="submit" class="btn btn-primary btn-sm mr-3" value="Edit">
                            </form>
                            <form action="<?= app\core\Route::url('index', 'delete') ?>" method="post">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                            </form>
                        </div>
                    </div>

                    <!-- Hike Properties -->
                    <div>
                        <h1><?= $trip['name'] ?></h1>
                        <p>
                            <?= $trip['description'] ?>
                        </p>
                        <!-- Two Columns for Properties -->
                        <div class="row">
                            <div class="col-7">
                                <p><strong>Author:</strong> <?= $trip['user'] ?></p>
                                <p><strong>Hike period:</strong> <?= $trip['start_date'] ?> - <?= $trip['end_date'] ?></p>
                            </div>
                            <div class="col-5">
                                <p><strong>Status:</strong> <?= $trip['status'] ?></p>
                                <p><strong>Difficulty:</strong> <?= $trip['difficulty'] ?></p>
                            </div>
                        </div>
                        <!-- Inventory Section -->
                        <div class="row mb-3">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <p><strong>Inventory:</strong></p>
                                <div class="d-flex align-items-center">
                                    <a href="<?= app\core\Route::url('inventory') ?>" class="btn btn-primary btn-sm">All inventory</a>
                                </div>
                            </div>
                        </div>
                        <?php if (empty($inventories)): ?>
                            <div class="d-flex flex-column align-items-center">
                                <p class="font-weight-bold text-center">No inventory items available</p>
                                <div class="d-flex justify-content-center">
                                    <form action="<?= app\core\Route::url('index', 'edit') ?>" method="post">
                                        <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                        <input type="submit" class="btn btn-primary btn-sm mr-3" value="Add inventory">
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Item</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inventories as $inventory): ?>
                                        <tr>
                                            <td class="align-middle">
                                                <img
                                                    src="<?= $inventory['photo'] ?>"
                                                    class="img-thumbnail inventory-photo"
                                                    data-toggle="modal"
                                                    data-target="#photoModal"
                                                    data-photo="<?= $inventory['photo'] ?>"
                                                    alt="<?= $inventory['name'] ?>" />
                                            </td>
                                            <td class="align-middle"><?= $inventory['name'] ?></td>
                                            <td class="align-middle">
                                                <form action="<?= app\core\Route::url('index', 'deleteInventory') ?>" method="post">
                                                    <input type="hidden" name="inventory_id" value="<?= $inventory['id'] ?>">
                                                    <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                                    <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <!-- Route Section -->
                        <p class="mt-4"><strong>Route:</strong></p>
                        <?php if (!$route): ?>
                            <div class="d-flex flex-column align-items-center">
                                <p class="font-weight-bold text-center">No route yet</p>
                                <form action="<?= app\core\Route::url('route', 'add_route') ?>" method="post">
                                    <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Add route">
                                </form>
                            </div>
                        <?php else: ?>
                            <p class="text-center font-weight-bold">Route: <?= $route['description'] ?></p>
                            <div class="travel-map text-center">
                                <span class="text-secondary font-weight-bold"><?= $route['photo'] ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Photo Gallery Section -->
                        <p class="mt-4"><strong>Photo Gallery:</strong></p>
                        <div class="d-flex flex-column align-items-center mb-4">
                            <form action="<?= app\core\Route::url('gallery', 'addPhoto') ?>" method="post">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <input type="submit" class="btn btn-primary btn-sm" value="Add photo">
                            </form>
                        </div>
                        <?php if (empty($photos)): ?>
                            <p class="font-weight-bold text-center">No photos available</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($photos as $photo): ?>
                                    <div class="col-6 col-md-3 mb-4">
                                        <img src="<?= $photo['photo'] ?>" class="img-thumbnail" alt="Photo" />
                                        <div class="text-center mt-1">
                                            <form action="<?= app\core\Route::url('gallery', 'viewPhoto') ?>" method="post">
                                                <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                                                <input type="submit" class="btn btn-primary btn-sm w-100" value="View">
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once self::VIEWS_DIR . 'modals' . DIRECTORY_SEPARATOR . 'inv_photo.php' ?>
