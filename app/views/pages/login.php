<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">AllHikes</h2>
                    <h5 class="text-center mb-3">Welcome back!</h5>
                    <form method="POST" action="/user/login" onsubmit="return validateLoginForm()">
                        <div class="mb-3">
                            <label for="login-username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="login-username" name="username" placeholder="Username...">
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="login-password" name="password" placeholder="Password...">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Log in</button>
                    </form>
                    <p class="text-center mt-3">Don't have an account? <a href="/user/register">Register here.</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateLoginForm() {
        const username = document.getElementById('login-username').value;
        const password = document.getElementById('login-password').value;

        if (!username || !password) {
            alert('Username and password are required!');
            return false;
        }
        return true;
    }
</script>