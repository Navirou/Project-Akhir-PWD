<?php
$pageTitle = 'Lihat Pesan';
$currentPage = 'admin';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect if not admin
redirectIfNotAdmin();
$unreadMessageCount = getUnreadMessageCount($conn);

// Get message ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: manage_messages.php");
    exit();
}

// Get message data
$message = getMessageById($conn, $id);
if (!$message) {
    header("Location: manage_messages.php");
    exit();
}

// Mark as read if not already
if (!$message['is_read']) {
    if (!markMessageAsRead($conn, $id)) {
        $error = 'Failed to mark message as read.';
    } else {
        $message['is_read'] = 1;
    }
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <div class="admin-sidebar p-3 rounded shadow-sm">
                <h5 class="mb-3 fw-bold">Admin Panel</h5>
                <div class="nav flex-column">
                    <a href="dashboard.php" class="nav-link mb-2">
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
                    <a href="manage_messages.php" class="nav-link active mb-2">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold">Detail Pesan</h2>
                <div>
                    <a href="manage_messages.php" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <?php if ($message['is_read']): ?>
                        <a href="manage_messages.php?action=mark_unread&id=<?php echo $message['id']; ?>" class="btn btn-outline-success me-2">
                            <i class="bi bi-envelope me-2"></i>Tandai Belum Dibaca
                        </a>
                    <?php else: ?>
                        <a href="manage_messages.php?action=mark_read&id=<?php echo $message['id']; ?>" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-envelope-open me-2"></i>Tandai Sudah Dibaca
                        </a>
                    <?php endif; ?>
                    <a href="manage_messages.php?action=delete&id=<?php echo $message['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </a>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <?php echo htmlspecialchars($message['subject']); ?>
                                <?php if (!$message['is_read']): ?>
                                    <span class="badge bg-success ms-2">Baru</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                <?php echo date('d M Y H:i', strtotime($message['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Pengirim:</strong>
                            </div>
                            <div class="col-md-10">
                                <?php echo htmlspecialchars($message['name']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-md-10">
                                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                    <?php echo htmlspecialchars($message['email']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
