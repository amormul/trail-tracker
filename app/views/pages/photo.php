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
            <img src="<?= $photo['photo'] ?>" alt="Photo" class="img-fluid rounded shadow">
        </div>
        <!-- Right part -->
        <div class="col-md-8 d-flex flex-column justify-content-start">
            <!-- Author -->
            <h3 class="text-primary"><?= $photo['author'] ?></h3>
            <!-- Description -->
            <p class="font-weight-bold">Description:</p>
            <p><?= $photo['comment'] ?></p>
            <div class="mt-auto">
                <button class="btn btn-outline-danger btn-sm">❤</button>
                <span>10 Likes</span>
            </div>
        </div>
    </div>
</div>
