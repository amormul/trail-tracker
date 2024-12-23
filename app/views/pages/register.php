<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">AllHikes</h2>
                    <h5 class="text-center mb-3">Registration</h5>
                    <form method="POST" action="/user/processRegister">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username..." value="<?= $old['username'] ?? '' ?>">
                            <?php if (!empty($errors['username'])): ?>
                                <div class="text-danger"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email..." value="<?= $old['email'] ?? '' ?>">
                            <?php if (!empty($errors['email'])): ?>
                                <div class="text-danger"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone number..." value="<?= $old['phone'] ?? '' ?>">
                            <?php if (!empty($errors['phone'])): ?>
                                <div class="text-danger"><?= $errors['phone'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password...">
                            <?php if (!empty($errors['password'])): ?>
                                <div class="text-danger"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm Password...">
                            <?php if (!empty($errors['confirm_password'])): ?>
                                <div class="text-danger"><?= $errors['confirm_password'] ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($errors['register'])): ?>
                            <div class="alert alert-danger"><?= $errors['register'] ?></div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="/user/login">Log in here.</a></p>
                </div>
            </div>
        </div>
    </div>
</div>