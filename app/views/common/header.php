<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand text-white" href="<?= app\core\Route::url() ?>">AllHikes</a>
        <?php if (!empty($login)): ?>
            <a href="<?= app\core\Route::url('user', 'logout') ?>" class="btn btn-outline-light btn-sm ml-auto"><i class="fa fa-user-o mr-2"></i><?= $login ?> <i class="fas fa-sign-out-alt ml-3"></i></a>
        <?php else: ?>
            <a href="<?= app\core\Route::url('user', 'login') ?>" class="btn btn-outline-light btn-sm ml-auto">Log in</a>
        <?php endif; ?>
    </div>
</nav>
