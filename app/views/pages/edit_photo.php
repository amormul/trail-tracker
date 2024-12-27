<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Cancel Button -->
            <div class="mt-4 mb-3 text-start">
                <form action="<?= app\core\Route::url('gallery', 'viewPhoto') ?>" method="post">
                    <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                    <button type="submit" class="btn btn-primary text-white">Cancel</button>
                </form>
            </div>
            <h2 class="text-center mt-4 mb-4">Edit Photo</h2>
            <form action="<?= app\core\Route::url('gallery', 'update') ?>" method="POST" enctype="multipart/form-data" class="row shadow p-4 rounded bg-light">
                <input type="hidden" name="id" value="<?= $photo['id'] ?>">

                <!-- Left Column: Current and New Photo -->
                <div class="col-md-6">
                    <!-- Current Photo -->
                    <label class="d-block mt-2">Current Photo:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <div>
                            <img src="<?= $photo['photo'] ?>" alt="Current Photo" class="img-fluid rounded">
                        </div>
                    </div>

                    <!-- New Photo -->
                    <label for="photoFile" class="d-block mt-3">New Photo:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <label for="photoFile" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="photoPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="/images/add.png" alt="Add" class="img-fluid w-50" />
                                </span>
                            </div>
                        </label>
                        <input type="file" id="photoFile" name="file" class="d-none" accept="image/*">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted photo-name" id="photoName"></small>
                        <button type="button" class="btn btn-sm btn-danger d-none ml-2 clear-btn" id="photoClear">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Right Column: Comment -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="photoComment">Comment:</label>
                        <textarea id="photoComment" name="comment" class="form-control" rows="8" placeholder="Update your comment here..."><?= $photo['comment'] ?></textarea>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
