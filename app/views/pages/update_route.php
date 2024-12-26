<main class="container mt-5 pt-3 mb-5">
    <div class="content-section card shadow bg-light p-4 mt-2">
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <a href="<?= app\core\Route::url('index','edit') ?>" class="btn btn-primary">Back</a>
                <h2 class="text-center">Update Route</h2>
            </div>
        </div>
        <form action="<?= \app\core\Route::url('route', 'update') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="trip_id" value="<?=$route['trip_id']?>"/>
            <div class="row">
                <!-- Left Column for Photos -->
                <div class="col-md-4">
                    <!-- Travel Path Photo -->
                    <label for="travelPathPhoto" class="d-block mt-2">Route map:</label>
                    <div class="photo-section mb-2 text-center border border-secondary rounded p-3">
                        <input type="hidden" name="current_photo" id="travelCurrentPhoto"
                               value="<?php if(!empty($route['photo'])):?><?=$route['photo']?><?php endif;?>">
                        <label for="travelPathPhoto" class="d-flex justify-content-center align-items-center photo-label">
                            <div id="travelPathPreview">
                                <span class="d-block text-center mb-2">
                                    <img src="<?php if(!empty($route['photo'])):?><?=$route['photo']?><?php endif;?>"
                                         alt="<?php if(!empty($route['photo'])):?><?=$route['photo']?><?php endif;?>"
                                         class="img-fluid max-width: 100%;" /> </span>
                            </div>
                        </label>
                        <input type="file" id="travelPathPhoto" name="route_photo" class="d-none" accept="image/*" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted photo-name" id="travelPathName"><?php if($exist_photo):?> <?=$route['photo']?> <?php endif;?></small>
                            <button type="button" class="btn btn-sm btn-danger ml-2 clear-btn <?php if(!$exist_photo): ?>d-none<?php endif;?>" id="travelPathClear">
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
                            required><?php if(!empty($route['description'])):?><?=$route['description']?><?php endif;?></textarea>
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
    <?php if (isset($errors)): ?>
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <h3 class="text-center">Error</h3>
            </div>
        </div>
        <ul>
            <?php if(is_array($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><?=$errors?></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</main>

