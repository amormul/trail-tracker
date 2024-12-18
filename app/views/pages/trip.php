<div class="container mt-5 pt-4 mb-5">
    <div class="row">
        <div class="col-12 bg-light shadow rounded py-4 px-4">
            <div class="row">
                <!-- Left Photo Section -->
                <div class="col-md-3">
                    <div class="photo-section">
                        <img src="<?= $trip['photo'] ?>" alt="<?= $trip['name'] ?>" class="img-fluid rounded">
                    </div>
                    <!-- Likes -->
                    <div class="d-flex justify-content-between mt-2">
                        <form action="<?= app\core\Route::url('index', 'like') ?>" method="post">
                            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                            <input type="submit" class="btn btn-outline-danger btn-sm" value="&#x2764;">
                        </form>
                        <span><?= $trip['likes'] ?> Likes</span>
                    </div>
                </div>

                <!-- Right Content Section -->
                <div class="col-md-9">
                    <!-- Buttons Split Across the Row -->
                    <div class="d-flex justify-content-between mb-3">
                        <a href="<?= \app\core\Route::url() ?>" class="btn btn-primary btn-sm">Back</a>
                        <div>
                            <button class="btn btn-primary btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </div>
                    </div>

                    <!-- Hike Properties -->
                    <div>
                        <h1><?= $trip['name'] ?></h1>
                        <p>
                            <?= $trip['description'] ?>
                        </p>
                        <!-- Two Columns for Properties -->
                        <div class="row">
                            <div class="col-7">
                                <p><strong>Author:</strong> John Doe</p>
                                <p><strong>Hike period:</strong> <?= $trip['start_date'] ?> - <?= $trip['end_date'] ?></p>
                            </div>
                            <div class="col-5">
                                <p><strong>Status:</strong> <?= $trip['status'] ?></p>
                                <p><strong>Difficulty:</strong> <?= $trip['difficulty'] ?></p>
                            </div>
                        </div>
                        <p><strong>Inventory:</strong></p>
                        <button class="btn btn-primary btn-sm mb-2">
                            All inventory
                        </button>
                        <table class="table table-bordered table-sm text-center">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Item</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-middle">
                                        <img
                                            src="https://via.placeholder.com/50"
                                            class="img-thumbnail inventory-photo"
                                            data-toggle="modal"
                                            data-target="#photoModal"
                                            data-photo="https://via.placeholder.com/300"
                                            alt="Tent" />
                                    </td>
                                    <td class="align-middle">Tent</td>
                                    <td class="align-middle">
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <img
                                            src="https://via.placeholder.com/50"
                                            class="img-thumbnail inventory-photo"
                                            data-toggle="modal"
                                            data-target="#photoModal"
                                            data-photo="https://via.placeholder.com/300"
                                            alt="Sleeping Bag" />
                                    </td>
                                    <td class="align-middle">Sleeping Bag</td>
                                    <td class="align-middle">
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Travel Map -->
                    <p class="font-weight-bold">Travel path: Sosnytsa > Mena > Berezna > Chernihiv</p>
                    <div class="travel-map">
                        <span class="text-secondary font-weight-bold">TRAVEL MAP</span>
                    </div>
                    <!-- Likes -->
                    <div class="d-flex justify-content-between mt-2 mb-4">
                        <button class="btn btn-outline-danger btn-sm">&#x2764;</button>
                        <span>30 Likes</span>
                    </div>

                    <!-- Photo Gallery -->
                    <div class="row text-left ml-1 mb-3">
                        <a href="#" class="btn btn-primary btn-sm">Add photo</a>
                    </div>
                    <div class="row mb-5">
                        <div class="col-6 col-md-3 mb-4">
                            <img
                                src="https://via.placeholder.com/300x200"
                                class="img-thumbnail"
                                alt="Photo 1" />
                            <div class="text-center mt-1">
                                <span class="d-block">15 Likes</span>
                                <button class="btn btn-primary btn-sm w-100">View</button>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-4">
                            <img
                                src="https://via.placeholder.com/300x200"
                                class="img-thumbnail"
                                alt="Photo 2" />
                            <div class="text-center mt-1">
                                <span class="d-block">20 Likes</span>
                                <button class="btn btn-primary btn-sm w-100">View</button>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-4">
                            <img
                                src="https://via.placeholder.com/300x200"
                                class="img-thumbnail"
                                alt="Photo 3" />
                            <div class="text-center mt-1">
                                <span class="d-block">10 Likes</span>
                                <button class="btn btn-primary btn-sm w-100">View</button>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-4">
                            <img
                                src="https://via.placeholder.com/300x200"
                                class="img-thumbnail"
                                alt="Photo 4" />
                            <div class="text-center mt-1">
                                <span class="d-block">25 Likes</span>
                                <button class="btn btn-primary btn-sm w-100">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div
    class="modal fade"
    id="photoModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="photoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Inventory Photo</h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img
                    id="modalPhoto"
                    src=""
                    class="img-fluid rounded"
                    alt="Inventory Item" />
            </div>
        </div>
    </div>
</div>
