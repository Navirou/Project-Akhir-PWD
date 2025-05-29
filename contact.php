<?php
$pageTitle = 'Kontak Kami';
$currentPage = 'contact';
require_once 'includes/db_connect.php';
include 'includes/header.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handleContactFormSubmission($conn, $_POST);
    $message = $result['message'];
    $messageType = $result['messageType'];
}
?>

<!-- Contact Hero Section -->
<section class="py-5 bg-light-green">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="fw-bold mb-4">Hubungi Kami</h1>
                <p class="lead mb-4">Punya pertanyaan, saran, atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami.</p>
                <div class="d-flex align-items-center mb-3">
                    <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Alamat</h5>
                        <p class="mb-0 text-muted">Jl. Kaliurang Atas, Sleman</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Email</h5>
                        <p class="mb-0 text-muted">info@nafasbumi.org</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Telepon</h5>
                        <p class="mb-0 text-muted">+62128335374</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="feature-icon me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Jam Operasional</h5>
                        <p class="mb-0 text-muted">Senin - Jumat: 09:00 - 17:00</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Kirim Pesan</h3>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="contact.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Lokasi Kami</h2>
            <p class="lead text-muted">Temukan kami di peta</p>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="ratio ratio-21x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63259.42695524673!2d110.36347598004093!3d-7.713786023048666!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a594ad98dec4d%3A0x4027a76e352fe10!2sNgaglik%2C%20Sleman%20Regency%2C%20Special%20Region%20of%20Yogyakarta!5e0!3m2!1sen!2sid!4v1748272085886!5m2!1sen!2sid" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Social Media Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Ikuti Kami</h2>
            <p class="lead text-muted">Dapatkan update terbaru dari kegiatan kami</p>
        </div>
        <div class="row justify-content-center text-center">
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm py-4">
                        <i class="bi bi-facebook text-success" style="font-size: 2.5rem;"></i>
                        <div class="card-body">
                            <h5 class="card-title">Facebook</h5>
                            <p class="card-text text-muted">@nafasbumi</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm py-4">
                        <i class="bi bi-twitter text-success" style="font-size: 2.5rem;"></i>
                        <div class="card-body">
                            <h5 class="card-title">Twitter</h5>
                            <p class="card-text text-muted">@nafasbumi</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm py-4">
                        <i class="bi bi-instagram text-success" style="font-size: 2.5rem;"></i>
                        <div class="card-body">
                            <h5 class="card-title">Instagram</h5>
                            <p class="card-text text-muted">@nafasbumi</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm py-4">
                        <i class="bi bi-youtube text-success" style="font-size: 2.5rem;"></i>
                        <div class="card-body">
                            <h5 class="card-title">YouTube</h5>
                            <p class="card-text text-muted">Nafas Bumi</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
