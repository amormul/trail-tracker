<main class="container mt-5 pt-3 mb-5">
    <div class="content-section card shadow bg-light p-4 mt-2">
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <a href="<?= app\core\Route::url() ?>" class="btn btn-primary">Back</a>
                <h2 class="text-center">Create Trip</h2>
            </div>
        </div>
        <form action="<?= \app\core\Route::url('index', 'store') ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Left Column for Photos -->
                <div class="col-md-4">
                    <!-- Trip Photo -->
                    <label for="coverPhoto" class="d-block">Trip photo:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <label for="coverPhoto" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="coverPhotoPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="/images/add.png" alt="Add" class="img-fluid w-50" />
                                </span>
                            </div>
                        </label>
                        <input type="file" id="coverPhoto" name="trip_photo" class="d-none" accept="image/*" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted photo-name" id="coverPhotoName"></small>
                        <button type="button" class="btn btn-sm btn-danger d-none ml-2 clear-btn" id="coverPhotoClear">
                            Clear
                        </button>
                    </div>

                    <!-- Travel Path Photo -->
                    <label for="travelPathPhoto" class="d-block mt-2">Route map:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <label for="travelPathPhoto" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="travelPathPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="/images/add.png" alt="Add" class="img-fluid w-50" />
                                </span>
                            </div>
                        </label>
                        <input type="file" id="travelPathPhoto" name="route_photo" class="d-none" accept="image/*" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted photo-name" id="travelPathName"></small>
                        <button type="button" class="btn btn-sm btn-danger d-none ml-2 clear-btn" id="travelPathClear">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Right Column for Form -->
                <div class="col-md-8">
                    <!-- Hike Name and Description -->
                    <div class="form-group">
                        <label for="hikeName">Trip name:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="hikeName"
                            name="name"
                            placeholder="Enter trip name..."
                            required />
                    </div>
                    <div class="form-group">
                        <label for="hikeDescription">Trip description:</label>
                        <textarea
                            class="form-control"
                            id="hikeDescription"
                            name="description"
                            rows="3"
                            placeholder="Enter some description here..."
                            required></textarea>
                    </div>

                    <!-- Difficulty Section -->
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="difficulty">Difficulty:</label>
                            <select class="form-control" id="difficulty" name="difficulty_id">
                                <option disabled selected>Choose difficulty</option>
                                <?php foreach ($difficulties as $difficulty): ?>
                                    <option value="<?= $difficulty['id'] ?>"><?= $difficulty['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
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
                                    <option value="<?= $status['id'] ?>"><?= $status['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="customStatus">Enter your own status:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="customStatus"
                                name="status"
                                placeholder="Enter status..." />
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
                                required />
                        </div>
                        <div class="col-md-6">
                            <label for="endDate">Choose end date:</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="endDate"
                                name="end_date"
                                required />
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="routeDescription">Enter route description:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="routeDescription"
                            name="route_description"
                            placeholder="Route description..."
                            required />
                    </div>
                </div>
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
                            <option value="<?= $inventory['id'] ?>"><?= $inventory['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="#" class="btn btn-primary btn-block">Add new inventory</a>
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
<div
    class="modal fade"
    id="difficultyModal"
    tabindex="-1"
    aria-labelledby="difficultyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="difficultyModalLabel">
                    Add difficulty
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modalDifficultyName">Difficulty name:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="modalDifficultyName"
                            name="name"
                            placeholder="Enter difficulty name..."
                            required />
                    </div>
                    <div class="form-group">
                        <label for="modalDifficultyDescription">Description:</label>
                        <textarea
                            class="form-control"
                            id="modalDifficultyDescription"
                            name="description"
                            rows="3"
                            placeholder="Enter description..."
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
