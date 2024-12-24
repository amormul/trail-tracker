<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Cancel Button -->
            <div class="mt-4 mb-3 text-start">
                <form action="<?= app\core\Route::url('index', 'show') ?>" method="get">
                    <input type="hidden" name="trip_id" value="<?= $tripId ?>">
                    <button type="submit" class="btn btn-primary text-white">Cancel</button>
                </form>
            </div>
            <h2 class="text-center mt-4 mb-4">Add Photo</h2>
            <div class="row shadow p-4 rounded bg-light">
                <!-- Photo upload -->
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <form action="<?= app\core\Route::url('gallery', 'savePhoto') ?>" method="post" enctype="multipart/form-data" class="w-100">
                        <input type="hidden" name="trip_id" value="<?= $tripId ?>">
                        <div class="form-group mb-3">
                            <label for="photoFile" class="form-label">Photo File</label>
                            <div class="mb-3">
                                <img id="photoPreview" src="#" alt="Your Photo" class="img-thumbnail d-none" style="max-width: 100%; max-height: 300px;">
                            </div>
                            <input type="file" id="photoFile" name="file" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="photoComment">Comment</label>
                                <textarea id="photoComment" name="comment" class="form-control" rows="6" placeholder="Tell about your trip"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Add Photo</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>