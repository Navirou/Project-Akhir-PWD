<?php
$pageTitle = 'Admin Dashboard';
$currentPage = 'admin';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect if not admin
redirectIfNotAdmin();

// Get counts for dashboard
$contentCount = getContentCount($conn);
$eventCount = getEventCount($conn);
$memberCount = getMemberCount($conn);
$unreadMessageCount = getUnreadMessageCount($conn);

// Get latest members
$latestMembers = getLatestMembers($conn, 5);

// Get latest messages
$latestMessages = getLatestMessages($conn, 5);

// Error handling
$error = '';
if ($conn->error) {
    $error = 'Database error: ' . $conn->error;
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <div class="admin-sidebar p-3 rounded shadow-sm">
                <h5 class="mb-3 fw-bold">Admin Panel</h5>
                <div class="nav flex-column">
                    <a href="dashboard.php" class="nav-link active mb-2">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a href="manage_content.php" class="nav-link mb-2">
                        <i class="bi bi-file-earmark-text me-2"></i> Kelola Konten
                    </a>
                    <a href="manage_event.php" class="nav-link mb-2">
                        <i class="bi bi-calendar-event me-2"></i> Kelola Kegiatan
                    </a>
                    <a href="manage_members.php" class="nav-link mb-2">
                        <i class="bi bi-people me-2"></i> Kelola Anggota
                    </a>
                    <a href="manage_messages.php" class="nav-link mb-2">
                        <i class="bi bi-envelope me-2"></i> Pesan
                        <?php if ($unreadMessageCount > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-2"><?php echo $unreadMessageCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="../logout.php" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold">Dashboard</h2>
                <div>
                    <span class="text-muted me-2">Selamat datang,</span>
                    <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Konten</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo $contentCount; ?></h3>
                                </div>
                                <div class="feature-icon bg-light-green">
                                    <i class="bi bi-newspaper text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="manage_content.php" class="text-success text-decoration-none">
                                <small>Lihat semua <i class="bi bi-arrow-right ms-1"></i></small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Kegiatan</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo $eventCount; ?></h3>
                                </div>
                                <div class="feature-icon bg-light-green">
                                    <i class="bi bi-calendar-event text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="manage_event.php" class="text-success text-decoration-none">
                                <small>Lihat semua <i class="bi bi-arrow-right ms-1"></i></small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Anggota</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo $memberCount; ?></h3>
                                </div>
                                <div class="feature-icon bg-light-green">
                                    <i class="bi bi-people text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="manage_members.php" class="text-success text-decoration-none">
                                <small>Lihat semua <i class="bi bi-arrow-right ms-1"></i></small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Pesan Baru</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo $unreadMessageCount; ?></h3>
                                </div>
                                <div class="feature-icon bg-light-green">
                                    <i class="bi bi-envelope text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="manage_messages.php" class="text-success text-decoration-none">
                                <small>Lihat semua <i class="bi bi-arrow-right ms-1"></i></small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="add_content.php" class="btn btn-outline-success w-100">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Konten
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="add_event.php" class="btn btn-outline-success w-100">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Kegiatan
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="manage_messages.php" class="btn btn-outline-success w-100">
                                <i class="bi bi-envelope-open me-2"></i> Lihat Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Latest Members & Messages -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Anggota Terbaru</h5>
                            <a href="manage_members.php" class="text-success text-decoration-none">
                                <small>Lihat semua</small>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Tanggal Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($latestMembers)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-3">Belum ada anggota.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($latestMembers as $member): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                                    <td><?php echo date('d M Y', strtotime($member['join_date'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Latest Messages -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Pesan Terbaru</h5>
                            <a href="manage_messages.php" class="text-success text-decoration-none">
                                <small>Lihat semua</small>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($latestMessages)): ?>
                                <div class="text-center py-4">
                                    <p class="mb-0 text-muted">Belum ada pesan.</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($latestMessages as $message): ?>
                                        <a href="view_message.php?id=<?php echo $message['id']; ?>" class="list-group-item list-group-item-action <?php echo $message['is_read'] ? '' : 'bg-light-green'; ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($message['name']); ?></h6>
                                                <small class="text-muted"><?php echo date('d M Y H:i', strtotime($message['created_at'])); ?></small>
                                            </div>
                                            <p class="mb-1 text-truncate"><strong>Subjek:</strong> <?php echo htmlspecialchars($message['subject']); ?></p>
                                            <small class="text-muted text-truncate d-block"><?php echo htmlspecialchars(substr($message['message'], 0, 100)); ?>...</small>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>