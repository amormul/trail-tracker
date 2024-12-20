<div class="container mt-5 pt-4 mb-5">
    <a href="<?= \app\core\Route::url("gallery", "photo", [$photo['id']]) ?>" class="btn btn-primary text-white btn-sm mr-2">Back</a>
    <form action="<?= \app\core\Route::url("gallery", "savePhoto", [$photo['id']]) ?>" method="POST" enctype="multipart/form-data">
        <!-- For photo -->
        <div class="form-group mt-3">
            <label for="photoFile">Change Photo</label>
            <input type="file" id="photoFile" name="file" class="form-control" accept="image/*">
        </div>
        <!-- For description -->
        <div class="form-group mb-3">
            <label for="photoDescription">Description</label>
            <textarea id="photoDescription" name="description" class="form-control" rows="4"><?= $photo['comment'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-success text-white btn-sm mr-2">Save</button>
    </form>
</div>

