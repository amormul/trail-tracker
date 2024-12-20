<div class="container mt-5 pt-4 mb-5">
    <!-- Back Button -->
    <a href="<?= \app\core\Route::url("gallery", "photo", [$photo['id']]) ?>" class="btn btn-primary text-white btn-sm mr-2">Back</a>
    <!-- Form -->
    <form action="<?= \app\core\Route::url("gallery", "savePhoto", [$photo['id']]) ?>" method="POST" enctype="multipart/form-data" class="row mt-4 bg-light shadow rounded p-4">
        <!-- Left Part: Photo -->
        <div class="col-md-6 text-center">
            <div class="form-group">
                <img src="<?= $photo['photo'] ?>" alt="Current Photo" class="img-fluid rounded shadow mb-3">
            </div>
            <div class="form-group">
                <label for="photoFile" class="form-label">New Photo:</label>
                <input type="file" id="photoFile" name="file" class="form-control" accept="image/*">
            </div>
        </div>
        <!-- Right Part: Description -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="photoDescription" class="form-label">Description</label>
                <textarea id="photoDescription" name="description" class="form-control" rows="8"><?= $photo['comment'] ?></textarea>
            </div>
        </div>
        <!-- Save Button -->
        <div class="col-12 text-right mt-3">
            <button type="submit" class="btn btn-success text-white btn-sm">Save</button>
        </div>
    </form>
</div>