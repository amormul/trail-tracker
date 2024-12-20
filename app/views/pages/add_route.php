<main class="container mt-5 pt-3 mb-5">
    <div class="content-section card shadow bg-light p-4 mt-2">
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <a href="<?= app\core\Route::url('index','index') ?>" class="btn btn-primary">Back</a>
                <h2 class="text-center">Create Route</h2>
            </div>
        </div>
        <form action="<?= \app\core\Route::url('route', 'store') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="trip_id" value="<?=$trip_id?>"/>
            <div class="row">
                <!-- Left Column for Photos -->
                <div class="col-md-4">
                    <!-- Travel Path Photo -->
                    <label for="travelPathPhoto" class="d-block mt-2">Route map:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <label for="travelPathPhoto" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="travelPathPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="/images/add.png" alt="Add" class="img-fluid w-50" />
                                </span>
                            </div>
                        </label>
                        <input type="file" id="travelPathPhoto" name="route_photo" class="d-none" accept="image/*" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted photo-name" id="travelPathName"></small>
                        <button class="btn btn-sm btn-danger d-none ml-2 clear-btn" id="travelPathClear">
                            Clear
                        </button>
                    </div>
                </div>
                <!-- Right Column for Form -->
                <div class="col-md-8">
                    <div class="form-group mt-3">
                        <label for="routeDescription">Enter route description:</label>
                        <textarea
                            class="form-control"
                            id="routeDescription"
                            name="route_description"
                            rows="5"
                            placeholder="Enter some description here...   Travel path: Sosnytsa > Mena > Berezna > Chernihiv"
                            required></textarea>
                    </div>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="row mt-4 mb-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
