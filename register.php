<?php
$pageTitle = 'Pendaftaran';
$currentPage = 'register';
require_once 'includes/db_connect.php';
include 'includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handleRegister($conn, $_POST);
    $message = $result['message'];
    $messageType = $result['messageType'];
}

?>

<!-- Register Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="assets/img/logo.png" alt="Nafas Bumi Logo" height="80">
                            <h2 class="fw-bold mt-3">Daftar Akun</h2>
                            <p class="text-muted">Buat akun baru untuk bergabung dengan Nafas Bumi</p>
                        </div>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="register.php">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">Saya menyetujui <a href="#" class="text-success">syarat dan ketentuan</a> Nafas Bumi</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Daftar</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Sudah memiliki akun? <a href="login.php" class="text-success">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
