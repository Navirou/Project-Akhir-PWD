<?php
$pageTitle = 'Edit Kegiatan';
$currentPage = 'admin';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect if not admin
redirectIfNotAdmin();
$unreadMessageCount = getUnreadMessageCount($conn);

// Get event ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: manage_event.php");
    exit();
}

// Get event data
$event = getEventById($conn, $id);
if (!$event) {
    header("Location: manage_event.php");
    exit();
}

// Handle form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = $_POST['description'] ?? '';
    $event_date = sanitizeInput($_POST['event_date'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');

    if (empty($title) || empty($description) || empty($event_date)) {
        $message = 'Judul, deskripsi, dan tanggal kegiatan harus diisi.';
        $messageType = 'danger';
    } else {
        $success = updateEvent($conn, $id, $title, $description, $event_date, $location);
        if ($success) {
            $message = 'Kegiatan berhasil diperbarui.';
            $messageType = 'success';
            // Refresh event data
            $event = getEventById($conn, $id);
        } else {
            $message = 'Terjadi kesalahan saat memperbarui kegiatan.';
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
                    <a href="manage_content.php" class="nav-link mb-2">
                        <i class="bi bi-file-earmark-text me-2"></i> Kelola Konten
                    </a>
                    <a href="manage_event.php" class="nav-link active mb-2">
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
                <h2 class="mb-0 fw-bold">Edit Kegiatan</h2>
                <a href="manage_event.php" class="btn btn-outline-secondary">
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
                    <form method="post" action="edit_event.php?id=<?php echo $id; ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Kegiatan</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Kegiatan</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Tanggal Kegiatan</label>
                            <input type="datetime-local" class="form-control" id="event_date" name="event_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi Kegiatan</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" placeholder="Masukkan lokasi (opsional)">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
