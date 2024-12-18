<main class="container mt-5 pt-5 mb-5">
    <div class="content-section card shadow bg-light p-4 mt-2">
        <div class="row">
            <div class="col-7 d-flex justify-content-between align-items-center mb-2">
                <a href="<?= app\core\Route::url('index','index') ?>" class="btn btn-primary">Back</a>
                <h2 class="text-center">Error</h2>
            </div>
        </div>
        <ul>
            <?php if (isset($message)): ?>
                <?php if(is_array($message)): ?>
                    <?php foreach ($message as $error): ?>
                            <li><?=$error?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><?=$message?></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
</main>
