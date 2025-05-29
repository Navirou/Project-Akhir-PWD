<?php
$pageTitle = 'Login';
$currentPage = 'login';
require_once 'includes/db_connect.php';
include 'includes/header.php';

$message = '';
$messageType = '';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handleLogin($conn, $_POST);
    $message = $result['message'];
    $messageType = $result['messageType'];
}
?>

<!-- Login Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="assets/img/logo.png" alt="Nafas Bumi Logo" height="80">
                            <h2 class="fw-bold mt-3">Login</h2>
                            <p class="text-muted">Masuk ke akun Nafas Bumi Anda</p>
                        </div>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-container">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                            </span>
                            </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                        <div class="mt-3 text-center">
                            <p>Belum memiliki akun? <a href="join.php" class="text-success">Daftar di sini</a></p>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
</script>

<?php include 'includes/footer.php'; ?>
