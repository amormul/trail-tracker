<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="d-flex justify-content-between align-items-center mb-2">
<!--        <div class="col-7 d-flex justify-content-between align-items-center mb-2">-->
            <a class="navbar-brand text-white" href="<?= app\core\Route::url() ?>">AllHikes</a>
            <div class="container">
                <div ><i class="fa fa-user-o" aria-hidden="true"></i><?php if(!empty($login)):?><?= $login ?> <?php else:?>no register <?php endif;?></div>
                <a href="<?= app\core\Route::url('user', 'login') ?>" class="btn btn-outline-light btn-sm ml-auto">Log in</a>
                <a href="<?= app\core\Route::url('user', 'logout') ?>" class="btn btn-outline-light btn-sm ml-auto">Log out</a>
            </div>
<!--        </div>-->
    </div>
</nav>
