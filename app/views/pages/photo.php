<div class="container mt-5 pt-4 mb-5">
    <div class="d-flex mb-3">
        <a href="#" class="btn btn-primary text-white btn-sm mr-2">Back</a>
        <form action="<?= app\core\Route::url('gallery', 'editPhoto') ?>" method="post">
            <input type="hidden" name="id" value="<?= $photo['id'] ?>">
            <input type="submit" class="btn btn-warning text-black btn-sm mr-2" value="Edit">
        </form>
        <button type="submit" class="btn btn-danger text-black btn-sm">Delete</button>
    </div>
    <div class="row bg-light shadow rounded py-4 px-4">
        <!-- Left part -->
        <div class="col-md-4 text-center">
            <img src="<?= $photo['photo'] ?>" alt="Photo" class="img-fluid rounded shadow" style="width: 400px; height: auto; display: block; margin: 0 auto;">
        </div>
        <!-- Right part -->
        <div class="col-md-8 d-flex flex-column justify-content-start">
            <!-- Author -->
            <h3 class="text-primary"><?= $photo['author'] ?></h3>
            <!-- Description -->
            <p class="font-weight-bold">Comment:</p>
            <p><?= $photo['comment'] ?></p>
            <div class="d-flex align-items-center mt-2">
                <form action="<?= app\core\Route::url('gallery', 'like') ?>" method="post" class="d-flex align-items-center">
                    <input type="hidden" name="photo_id" value="<?= $photo['id'] ?>">
                    <input type="submit" class="btn btn-outline-danger btn-sm me-2" value="&#x2764;">
                </form>
                <span>Likes: <?= $photo['likes'] ?></span>
            </div>
        </div>
    </div>
</div>
