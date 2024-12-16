<main class="container mt-5 pt-5 mb-5">
    <div class="row mb-3">
        <div class="col-7 d-flex justify-content-between align-items-center">
            <a href="<?= \app\core\Route::url('index', 'create') ?>" class="btn btn-primary">Create Trip</a>
            <h2 class="text-center">All Trips</h2>
        </div>
    </div>
    <div class="row">
        <!-- Hike Card 1 -->
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="row no-gutters">
                    <div
                        class="col-md-3 d-flex align-items-center justify-content-center p-3">
                        <span class="text-secondary font-weight-bold">PHOTO</span>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title">Mountain Adventure</h5>
                            <p class="card-text">
                                A breathtaking hike through the hills.
                            </p>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Author:</strong> John Doe</p>
                                    <p><strong>Start Date:</strong> 2024-05-01</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Status:</strong> Completed</p>
                                    <p><strong>Difficulty:</strong> Moderate</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-outline-danger btn-sm">
                                        &#x2764; 12
                                    </button>
                                </div>
                                <a href="<?= app\core\Route::url('index', 'show') ?>" class="btn btn-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hike Card 2 -->
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="row no-gutters">
                    <div
                        class="col-md-3 d-flex align-items-center justify-content-center p-3">
                        <span class="text-secondary font-weight-bold">PHOTO</span>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title">Forest Trail</h5>
                            <p class="card-text">
                                Explore the wonders of the deep forest.
                            </p>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Author:</strong> Jane Smith</p>
                                    <p><strong>Start Date:</strong> 2024-06-10</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Status:</strong> Planned</p>
                                    <p><strong>Difficulty:</strong> Easy</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-outline-danger btn-sm">
                                        &#x2764; 12
                                    </button>
                                </div>
                                <a href="#" class="btn btn-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hike Card 3 -->
        <div class="col-md-12 mb-5">
            <div class="card shadow">
                <div class="row no-gutters">
                    <div
                        class="col-md-3 d-flex align-items-center justify-content-center p-3">
                        <span class="text-secondary font-weight-bold">PHOTO</span>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title">River Walk</h5>
                            <p class="card-text">A serene walk along the riverbank.</p>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Author:</strong> Alex Johnson</p>
                                    <p><strong>Start Date:</strong> 2024-07-15</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Status:</strong> In Progress</p>
                                    <p><strong>Difficulty:</strong> Hard</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-outline-danger btn-sm">
                                        &#x2764; 12
                                    </button>
                                </div>
                                <a href="#" class="btn btn-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
