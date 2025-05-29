<?php
$pageTitle = 'Kelola Pesan';
$currentPage = 'admin';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect if not admin
redirectIfNotAdmin();
$unreadMessageCount = getUnreadMessageCount($conn);

// Get read status filter
$readStatus = isset($_GET['read_status']) ? sanitizeInput($_GET['read_status']) : '';

// Get search query
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Build query
$messages = getAllMessages($conn, $search, $readStatus);
$error = null;

// Handle mark as read/unread
if (isset($_GET['action']) && ($_GET['action'] === 'mark_read' || $_GET['action'] === 'mark_unread') && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $isRead = ($_GET['action'] === 'mark_read') ? 1 : 0;
    
    $success = updateMessageReadStatus($conn, $id, $isRead);
    if (!$success) {
        $error = 'Failed to update message status.';
    }
    // Redirect to avoid resubmission
    header("Location: manage_messages.php" . ($readStatus !== '' ? "?read_status=$readStatus" : ""));
    exit();
}

// Handle delete message
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $success = deleteMessageById($conn, $id);
    if (!$success) {
        $error = 'Failed to delete message.';
    }
    // Redirect to avoid resubmission
    header("Location: manage_messages.php" . ($readStatus !== '' ? "?read_status=$readStatus" : ""));
    exit();
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
                <h2 class="mb-0 fw-bold">Kelola Pesan</h2>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Search and Filter -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="manage_messages.php" method="get" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari pesan..." name="search" value="<?php echo $search; ?>">
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <?php if ($search || $readStatus !== ''): ?>
                                <a href="manage_messages.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i> Reset Filter
                                </a>
                            <?php endif; ?>
                            <div class="btn-group ms-2">
                                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                                    Filter Status
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="manage_messages.php?read_status=unread<?php echo $search ? "&search=$search" : ""; ?>">Belum Dibaca</a></li>
                                    <li><a class="dropdown-item" href="manage_messages.php?read_status=read<?php echo $search ? "&search=$search" : ""; ?>">Sudah Dibaca</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Messages Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Nama</th>
                                    <th width="15%">Email</th>
                                    <th width="20%">Subjek</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Tidak ada pesan yang ditemukan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($messages as $index => $message): ?>
                                        <tr class="<?php echo $message['is_read'] ? '' : 'table-light'; ?>">
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($message['name']); ?></td>
                                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                                            <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                            <td><?php echo date('d M Y H:i', strtotime($message['created_at'])); ?></td>
                                            <td>
                                                <?php if ($message['is_read']): ?>
                                                    <span class="badge bg-secondary">Dibaca</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Baru</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="view_message.php?id=<?php echo $message['id']; ?>" class="btn btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if ($message['is_read']): ?>
                                                        <a href="manage_messages.php?action=mark_unread&id=<?php echo $message['id']; ?><?php echo $readStatus !== '' ? "&read_status=$readStatus" : ""; ?>" class="btn btn-outline-success" title="Tandai belum dibaca">
                                                            <i class="bi bi-envelope"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="manage_messages.php?action=mark_read&id=<?php echo $message['id']; ?><?php echo $readStatus !== '' ? "&read_status=$readStatus" : ""; ?>" class="btn btn-outline-secondary" title="Tandai sudah dibaca">
                                                            <i class="bi bi-envelope-open"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="manage_messages.php?action=delete&id=<?php echo $message['id']; ?><?php echo $readStatus !== '' ? "&read_status=$readStatus" : ""; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')" title="Hapus">
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
