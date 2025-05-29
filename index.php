<?php
$pageTitle = 'Beranda';
$currentPage = 'home';
require_once 'includes/db_connect.php';
include 'includes/header.php';

$latestArticles = getLatestContent($conn, 3);
$upcomingEvents = getUpcomingEvents($conn, 3);

?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('assets/img/hero.jpg');">
    <div class="container hero-content text-center">
        <h1 class="display-4 fw-bold mb-4">Bersama Menjaga Bumi Kita</h1>
        <p class="lead mb-4">Bergabunglah dengan Nafas Bumi untuk menciptakan lingkungan yang lebih baik dan berkelanjutan untuk generasi mendatang.</p>
        <div>
            <a href="join.php" class="btn btn-success btn-lg me-2 px-4 py-2">Bergabung Sekarang</a>
            <a href="about.php" class="btn btn-outline-light btn-lg px-4 py-2">Pelajari Lebih Lanjut</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light-green">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Bergabung dengan Kami?</h2>
            <p class="lead text-muted">Nafas Hijau hadir untuk memberikan dampak positif bagi lingkungan</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-tree"></i>
                    </div>
                    <h4>Aksi Nyata</h4>
                    <p class="text-muted">Kami melakukan kegiatan penanaman pohon, pembersihan sampah, dan konservasi lingkungan secara rutin.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Komunitas Peduli</h4>
                    <p class="text-muted">Bergabunglah dengan ratusan anggota yang memiliki kepedulian yang sama terhadap lingkungan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-book"></i>
                    </div>
                    <h4>Edukasi Lingkungan</h4>
                    <p class="text-muted">Dapatkan pengetahuan dan keterampilan tentang praktik ramah lingkungan melalui workshop dan seminar.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Articles Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Konten Terbaru</h2>
            <a href="#" class="btn btn-outline-success">Lihat Semua</a>
        </div>
        <div class="row g-4">
            <?php if (empty($latestArticles)): ?>
                <div class="col-12">
                    <div class="alert alert-info">Belum ada konten yang dipublikasikan.</div>
                </div>
            <?php else: ?>
                <?php foreach ($latestArticles as $article): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($article['image'])): ?>
                                <img src="assets/img/<?php echo htmlspecialchars($article['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <?php else: ?>
                                <img src="assets/img/default-article.jpg" class="card-img-top" alt="Default Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                                <p class="card-text text-muted"><?php echo substr(strip_tags($article['content']), 0, 100); ?>...</p>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                <small class="text-muted">Oleh: <?php echo htmlspecialchars($article['username']); ?></small>
                                <a href="#" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Untuk Bergabung?</h2>
        <p class="lead mb-4">Jadilah bagian dari perubahan positif untuk lingkungan kita</p>
        <a href="join.php" class="btn btn-light btn-lg text-success px-4">Bergabung Sekarang</a>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Kegiatan Mendatang</h2>
        <div class="row g-4">
            <?php if (empty($upcomingEvents)): ?>
                <div class="col-12">
                    <div class="alert alert-info">Belum ada kegiatan yang dijadwalkan.</div>
                </div>
            <?php else: ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-success">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                                <p class="card-text"><strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($event['event_date'])); ?></p>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="#" class="btn btn-outline-success w-100">Daftar Kegiatan</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
