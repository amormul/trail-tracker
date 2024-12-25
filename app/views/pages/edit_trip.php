<main class="container mt-5 pt-3 mb-5">
    <div class="content-section card shadow bg-light p-4 mt-2">
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <a href="<?= app\core\Route::url() ?>" class="btn btn-primary">Back</a>
                <h2 class="text-center">Edit Trip</h2>
            </div>
        </div>
        <form action="<?= \app\core\Route::url('index', 'update') ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Left Column for Photos -->
                <div class="col-md-4">
                    <!-- Trip Photo -->
                    <label for="coverPhoto" class="d-block">Trip photo:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <label for="coverPhoto" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="coverPhotoPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="<?= $old['photo'] ?>" alt="Trip Photo" class="img-fluid w-50" />
                                </span>
                            </div>
                        </label>
                        <input type="file" id="coverPhoto" name="photo" class="d-none" accept="image/*" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if (isset($errors['photo'])): ?>
                            <small class="text-danger"><?= $errors['photo'] ?></small>
                        <?php endif; ?>
                        <small class="text-muted photo-name" id="coverPhotoName"></small>
                        <button type="button" class="btn btn-sm btn-danger d-none ml-2 clear-btn" id="coverPhotoClear">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Right Column for Form -->
                <div class="col-md-8">
                    <!-- Hike Name and Description -->
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?= $old['id'] ?>">
                        <label for="hikeName">Trip name:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="hikeName"
                            name="name"
                            value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                            placeholder="Enter trip name..." />
                        <?php if (isset($errors['name'])): ?>
                            <small class="text-danger"><?= $errors['name'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="hikeDescription">Trip description:</label>
                        <textarea
                            class="form-control"
                            id="hikeDescription"
                            name="description"
                            rows="3"
                            placeholder="Enter some description here..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <small class="text-danger"><?= $errors['description'] ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- Difficulty Section -->
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="difficulty">Difficulty:</label>
                            <select class="form-control" id="difficulty" name="difficulty_id">
                                <option disabled selected>Choose difficulty</option>
                                <?php foreach ($difficulties as $difficulty): ?>
                                    <option value="<?= $difficulty['id'] ?>" <?= (isset($old['difficulty_id']) && $old['difficulty_id'] == $difficulty['id']) ? 'selected' : '' ?>><?= $difficulty['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['difficulty_id'])): ?>
                                <small class="text-danger"><?= $errors['difficulty_id'] ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label>Add your own difficulty:</label>
                            <button
                                type="button"
                                class="btn btn-primary btn-block"
                                data-toggle="modal"
                                data-target="#difficultyModal">
                                Add difficulty
                            </button>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="status">Status:</label>
                            <select class="form-control" id="status_id" name="status_id">
                                <option disabled selected>Choose status</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= $status['id'] ?>" <?= (isset($old['status_id']) && $old['status_id'] == $status['id']) ? 'selected' : '' ?>><?= $status['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['status_id'])): ?>
                                <small class="text-danger"><?= $errors['status_id'] ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="customStatus">Add your own status:</label>
                            <button
                                type="button"
                                class="btn btn-primary btn-block"
                                data-toggle="modal"
                                data-target="#statusModal">
                                Add status
                            </button>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="startDate">Choose start date:</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="startDate"
                                name="start_date"
                                value="<?= $old['start_date'] ?? '' ?>" />
                            <?php if (isset($errors['start_date'])): ?>
                                <small class="text-danger"><?= $errors['start_date'] ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate">Choose end date:</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="endDate"
                                name="end_date"
                                value="<?= $old['end_date'] ?? '' ?>" />
                            <?php if (isset($errors['end_date'])): ?>
                                <small class="text-danger"><?= $errors['end_date'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Route Section -->
            <p class="mt-4"><strong>Route:</strong></p>
            <div class="d-flex flex-column align-items-center">
                <a href="<?= app\core\Route::url('route', 'edit_route?trip_id='.$old['id']) ?>"
                             class="btn btn-primary"> Edit route</a>
            </div>

            <p class="text-center font-weight-bold">Route: <?php if (!empty($route)): ?> <?= $route['description'] ?> <?php endif;?></p>
            <div class="travel-map text-center">
                <img src="<?php if (!empty($route)): ?><?= $route['photo'] ?> <?php endif;?>"
                     alt="<?php if (!empty($route)): ?><?= $route['photo'] ?><?php endif;?>" class="photo-route">
            </div>
            <!-- Inventory Section -->
            <div class="form-row">
                <div class="col-md-9">
                    <label for="inventory">Choose inventory:</label>
                    <select
                        class="form-control w-100"
                        id="inventory"
                        name="inventory[]"
                        multiple="multiple">
                        <?php foreach ($inventories as $inventory): ?>
                            <option value="<?= $inventory['id'] ?>"
                                <?= (isset($selectedInventory) && in_array($inventory['id'], $selectedInventory)) ? 'selected' : '' ?>>
                                <?= $inventory['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="<?= app\core\Route::url('inventory', 'create') ?>" class="btn btn-primary btn-block">Add new inventory</a>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="row mt-4 mb-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>


<!-- Difficulty Modal -->
<?php include_once self::VIEWS_DIR . 'modals' . DIRECTORY_SEPARATOR . 'add_difficulty.php' ?>
<?php include_once self::VIEWS_DIR . 'modals' . DIRECTORY_SEPARATOR . 'add_status.php' ?>
