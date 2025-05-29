<?php
$pageTitle = 'Join Kami';
$currentPage = 'join';
require_once 'includes/db_connect.php';
include 'includes/header.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handleJoin($conn, $_POST);
    $message = $result['message'];
    $messageType = $result['messageType'];
}
?>

<!-- Join Hero Section -->
<section class="py-5 bg-light-green">
    <div class="container">
        <div class="row align-items-stretch">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="fw-bold mb-4">Bergabung dengan Nafas Bumi</h1>
                <p class="lead mb-4">Jadilah bagian dari gerakan untuk menciptakan lingkungan yang lebih baik dan berkelanjutan.</p>
                <div class="d-flex flex-column flex-md-row gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="feature-icon me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Komunitas Peduli</h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="feature-icon me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Kegiatan Rutin</h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="feature-icon me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-award"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Sertifikat</h5>
                        </div>
                    </div>
                </div>
                <p>Dengan bergabung, Anda akan mendapatkan:</p>
                <ul class="mb-4">
                    <li>Akses ke semua kegiatan Nafas Bumi</li>
                    <li>Newsletter bulanan dengan informasi terbaru</li>
                    <li>Kesempatan untuk berpartisipasi dalam program khusus anggota</li>
                    <li>Jaringan dengan sesama pecinta lingkungan</li>
                    <li>Sertifikat partisipasi untuk setiap kegiatan</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Form Pendaftaran</h3>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="join.php">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
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
                            <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Konfirmasi Password</label>
                            <div class="password-container">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                            </span>
                            </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">Saya menyetujui <a href="#" class="text-success">syarat dan ketentuan</a> Nafas Bumi</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Daftar Sekarang</button>
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

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Apa Kata Anggota Kami</h2>
            <p class="lead text-muted">Pengalaman dari anggota Nafas Bumi</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text mb-4">"Bergabung dengan Nafas Bumi adalah salah satu keputusan terbaik saya. Saya telah belajar banyak tentang lingkungan dan bertemu dengan orang-orang yang memiliki passion yang sama."</p>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/krii.jpg" alt="Testimonial" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0 fw-bold">Upin</h6>
                                <small class="text-muted">Anggota sejak 2019</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text mb-4">"Kegiatan-kegiatan yang diadakan oleh Nafas Bumi sangat bermanfaat dan menyenangkan. Saya merasa berkontribusi nyata untuk lingkungan dan mendapatkan teman-teman baru."</p>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/tengh.jpg" alt="Testimonial" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0 fw-bold">Ipin</h6>
                                <small class="text-muted">Anggota sejak 2020</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text mb-4">"Sebagai seorang yang peduli dengan lingkungan, Nafas Bumi memberikan saya platform untuk menyalurkan passion saya. Workshop dan kegiatan yang diadakan sangat informatif."</p>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/knan.jpg" alt="Testimonial" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0 fw-bold">Udin</h6>
                                <small class="text-muted">Anggota sejak 2018</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');
    
    // Toggle password visibility for the main password
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }

    // Toggle password visibility for the confirm password
    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        eyeIconConfirm.classList.remove('fa-eye');
        eyeIconConfirm.classList.add('fa-eye-slash');
    } else {
        confirmPasswordInput.type = 'password';
        eyeIconConfirm.classList.remove('fa-eye-slash');
        eyeIconConfirm.classList.add('fa-eye');
    }
}

</script>


<?php include 'includes/footer.php'; ?>
