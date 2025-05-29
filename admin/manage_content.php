<?php
$pageTitle = 'Kelola Konten';
$currentPage = 'admin';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect if not admin
redirectIfNotAdmin();
$unreadMessageCount = getUnreadMessageCount($conn);

// Handle delete action first
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (!deleteContentById($conn, $id)) {
        $error = 'Error deleting content: ' . $conn->error;
    } else {
        // Redirect to avoid resubmission
        header("Location: manage_content.php");
        exit();
    }
}

// Get search query
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get contents using function
$contents = getContents($conn, $search);

// Error handling
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
                <h2 class="mb-0 fw-bold">Kelola Konten</h2>
                <a href="add_content.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Konten Baru
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Search and Filter -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="manage_content.php" method="get" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari konten..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <?php if ($search): ?>
                                <a href="manage_content.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i> Reset Filter
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Content Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Judul</th>
                                    <th width="20%">Penulis</th>
                                    <th width="20%">Tanggal Dibuat</th>
                                    <th width="20%">Tanggal Diperbarui</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($contents)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Tidak ada konten yang ditemukan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($contents as $index => $content): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($content['title']); ?></td>
                                            <td><?php echo htmlspecialchars($content['username']); ?></td>
                                            <td><?php echo date('d M Y H:i', strtotime($content['created_at'])); ?></td>
                                            <td><?php echo date('d M Y H:i', strtotime($content['updated_at'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="edit_content.php?id=<?php echo $content['id']; ?>" class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="manage_content.php?action=delete&id=<?php echo $content['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus konten ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>