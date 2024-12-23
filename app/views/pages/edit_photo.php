<div class="container mt-5 pt-4 mb-5">
    <!-- Back Button -->
    <a href="#" class="btn btn-primary text-white btn-sm mr-2">Back</a>
    <!-- Form -->
    <form action="<?= app\core\Route::url('gallery', 'update') ?>" method="POST" enctype="multipart/form-data" class="row mt-4 bg-light shadow rounded p-4">
        <input type="hidden" name="id" value="<?= $photo['id'] ?>">
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
        <!-- Right Part: Comment -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="photoComment" class="form-label">Comment:</label>
                <textarea id="photoComment" name="comment" class="form-control" rows="8"><?= $photo['comment'] ?></textarea>
            </div>
        </div>
        <!-- Save Button -->
        <div class="col-12 text-right mt-3">
            <button type="submit" class="btn btn-success text-white btn-sm">Save</button>
        </div>
    </form>
</div>