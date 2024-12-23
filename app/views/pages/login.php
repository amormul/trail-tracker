<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">AllHikes</h2>
                    <h5 class="text-center mb-3">Welcome back!</h5>
                    <form method="POST" action="/user/processLogin">
                        <div class="mb-3">
                            <label for="login-username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="login-username" name="username" placeholder="Username...">
                            <?php if (!empty($errors['username'])): ?>
                                <div class="text-danger"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="login-password" name="password" placeholder="Password...">
                            <?php if (!empty($errors['password'])): ?>
                                <div class="text-danger"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($errors['login'])): ?>
                            <div class="alert alert-danger"><?= $errors['login'] ?></div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary w-100">Log in</button>
                    </form>
                    <p class="text-center mt-3">Don't have an account? <a href="/user/register">Register here.</a></p>
                </div>
            </div>
        </div>
    </div>
</div>