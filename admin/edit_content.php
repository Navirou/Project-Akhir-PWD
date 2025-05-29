<?php
$pageTitle = 'Edit Konten';
$currentPage = 'admin';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect if not admin
redirectIfNotAdmin();
$unreadMessageCount = getUnreadMessageCount($conn);

// Get content ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: manage_content.php");
    exit();
}

// Get content data
$content = getContentById($conn, $id);
if (!$content) {
    header("Location: manage_content.php");
    exit();
}

// Handle form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $contentText = $_POST['content'] ?? '';
    $image = sanitizeInput($_POST['image'] ?? '');
    
    if (empty($title) || empty($contentText)) {
        $message = 'Judul dan konten harus diisi.';
        $messageType = 'danger';
    } else {
        $success = updateContent($conn, $id, $title, $contentText, $image);
        if ($success) {
            $message = 'Konten berhasil diperbarui.';
            $messageType = 'success';
            // Refresh content data
            $content = getContentById($conn, $id);
        } else {
            $message = 'Terjadi kesalahan saat memperbarui konten.';
            $messageType = 'danger';
        }
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
                    <a href="manage_content.php" class="nav-link active mb-2">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold">Edit Konten</h2>
                <a href="manage_content.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="edit_content.php?id=<?php echo $id; ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($content['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar (URL)</label>
                            <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($content['image'] ?? ''); ?>" placeholder="Masukkan URL gambar (opsional)">
                            <small class="text-muted">Biarkan kosong jika tidak ada gambar</small>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Konten</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($content['content']); ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="manage_content.php" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
