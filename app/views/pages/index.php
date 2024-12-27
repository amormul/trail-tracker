<main class="container mt-5 pt-5 mb-5">
    <div class="row mb-3">
        <div class="col-7 d-flex justify-content-between align-items-center">
            <?php if ($login): ?>
                <a href="<?= \app\core\Route::url('index', 'create') ?>" class="btn btn-primary">Create Trip</a>
            <?php endif; ?>
            <h2 class="text-center">All Trips</h2>
        </div>
    </div>
    <div class="row">
        <?php if (!empty($trips)): ?>
            <!-- Hike Cards -->
            <?php foreach ($trips as $trip): ?>
                <div class="col-md-12 mb-4">
                    <div class="card shadow">
                        <div class="row no-gutters">
                            <div class="col-md-3 d-flex align-items-center justify-content-center p-3">
                                <img src="<?= $trip['photo'] ?>" alt="<?= $trip['name'] ?>" class="img-fluid rounded">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $trip['name'] ?></h5>
                                    <p class="card-text">
                                        <?= $trip['description'] ?>
                                    </p>
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Author:</strong> <?= $trip['user'] ?></p>
                                            <p><strong>Start Date:</strong> <?= $trip['start_date'] ?></p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>Status:</strong> <?= $trip['status'] ?></p>
                                            <p><strong>Difficulty:</strong> <?= $trip['difficulty'] ?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <form action="<?= app\core\Route::url('index', 'like') ?>" method="post">
                                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                                <input type="submit" class="btn btn-outline-danger btn-sm" value="&#x2764; <?= $trip['likes'] ?>">
                                            </form>
                                        </div>
                                        <form action="<?= app\core\Route::url('index', 'show') ?>" method="post">
                                            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                            <input type="submit" class="btn btn-primary btn-sm" value="Details">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <h2 class="ml-5 mt-5">No trips yet, create new one</h2>
            </div>
        <?php endif; ?>
    </div>
</main>
