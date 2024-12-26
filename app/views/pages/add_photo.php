<main class="container mt-5 pt-3 mb-5">
    <div class="content-section card shadow bg-light p-4 mt-2">
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <!-- Back Button -->
                <form action="<?= app\core\Route::url('index', 'show') ?>" method="get" class="d-inline">
                    <input type="hidden" name="trip_id" value="<?= $tripId ?>">
                    <button type="submit" class="btn btn-primary">Cancel</button>
                </form>
                <h2 class="text-center">Add Photo</h2>
            </div>
        </div>
        <form action="<?= app\core\Route::url('gallery', 'savePhoto') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="trip_id" value="<?= $tripId ?>" />
            <div class="row">
                <!-- Left Column: Photo Upload -->
                <div class="col-md-4">
                    <!-- Photo File -->
                    <label for="photoFile" class="d-block mt-2">Photo File:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <label for="photoFile" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="photoPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="/images/add.png" alt="Add" class="img-fluid w-50" />
                                </span>
                            </div>
                        </label>
                        <input type="file" id="photoFile" name="file" class="d-none" accept="image/*" required />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted photo-name" id="photoName"></small>
                        <button class="btn btn-sm btn-danger d-none ml-2 clear-btn" id="photoClear">
                            Clear
                        </button>
                    </div>
                </div>
                <!-- Right Column: Comment -->
                <div class="col-md-8">
                    <div class="form-group mt-3">
                        <label for="photoComment">Comment:</label>
                        <textarea
                                class="form-control"
                                id="photoComment"
                                name="comment"
                                rows="5"
                                placeholder="Tell about your trip"></textarea>
                    </div>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="row mt-4 mb-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        Add Photo
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
