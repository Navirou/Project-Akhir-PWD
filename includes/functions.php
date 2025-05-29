<?php
session_start();

// Fungsi untuk memeriksa apakah pengguna saat ini sudah login dengan memeriksa sesi pengguna
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Fungsi untuk mengelola pengiriman formulir kontak, termasuk validasi dan penyimpanan pesan ke database
 * @param mysqli $conn
 * @param array $postData
 * @return array ['message' => string, 'messageType' => string]
 */
function handleContactFormSubmission($conn, $postData) {
    $message = '';
    $messageType = '';

    $name = sanitizeInput($postData['name'] ?? '');
    $email = sanitizeInput($postData['email'] ?? '');
    $subject = sanitizeInput($postData['subject'] ?? '');
    $messageContent = sanitizeInput($postData['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($messageContent)) {
        $message = 'Semua field harus diisi.';
        $messageType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $messageContent);

        if ($stmt->execute()) {
            $message = 'Pesan Anda telah berhasil dikirim. Kami akan menghubungi Anda segera.';
            $messageType = 'success';
        } else {
            $message = 'Terjadi kesalahan saat mengirim pesan: ' . $conn->error;
            $messageType = 'danger';
        }
        $stmt->close();
    }

    return ['message' => $message, 'messageType' => $messageType];
}

/**
 * Fungsi untuk mengelola pengiriman formulir login, memverifikasi kredensial pengguna, dan mengatur sesi
 * @param mysqli $conn
 * @param array $postData
 * @return array ['message' => string, 'messageType' => string]
 */
function handleLogin($conn, $postData) {
    $message = '';
    $messageType = '';

    $username = sanitizeInput($postData['username'] ?? '');
    $password = $postData['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = 'Username dan password harus diisi.';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = 'Username atau password salah.';
            $messageType = 'danger';
        }
    }

    return ['message' => $message, 'messageType' => $messageType];
}

/**
 * Fungsi untuk mengelola pengiriman formulir pendaftaran anggota baru dengan transaksi untuk memastikan integritas data
 * @param mysqli $conn
 * @param array $postData
 * @return array ['message' => string, 'messageType' => string]
 */
function handleJoin($conn, $postData) {
    $message = '';
    $messageType = '';

    $fullName = sanitizeInput($postData['full_name'] ?? '');
    $email = sanitizeInput($postData['email'] ?? '');
    $phone = sanitizeInput($postData['phone'] ?? '');
    $address = sanitizeInput($postData['address'] ?? '');
    $username = sanitizeInput($postData['username'] ?? '');
    $password = $postData['password'] ?? '';
    $confirmPassword = $postData['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($phone) || empty($address) || empty($username) || empty($password) || empty($confirmPassword)) {
        $message = 'Semua field harus diisi.';
        $messageType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
        $messageType = 'danger';
    } elseif ($password !== $confirmPassword) {
        $message = 'Password dan konfirmasi password tidak cocok.';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        if ($count > 0) {
            $message = 'Username atau email sudah terdaftar.';
            $messageType = 'danger';
        } else {
            $conn->begin_transaction();

            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'member')");
                $stmt->bind_param("ssss", $username, $hashedPassword, $email, $fullName);
                $stmt->execute();
                $userId = $conn->insert_id;
                $stmt->close();

                $stmt = $conn->prepare("INSERT INTO members (user_id, phone, address) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $userId, $phone, $address);
                $stmt->execute();
                $stmt->close();

                $conn->commit();

                $message = 'Pendaftaran berhasil! Silakan login untuk mengakses akun Anda.';
                $messageType = 'success';
            } catch (Exception $e) {

                $conn->rollback();
                $message = 'Terjadi kesalahan saat mendaftar: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }

    return ['message' => $message, 'messageType' => $messageType];
}

/**
 * Mengelola pengiriman formulir pendaftaran pengguna baru, termasuk validasi dan penyimpanan data pengguna
 * @param mysqli $conn
 * @param array $postData
 * @return array ['message' => string, 'messageType' => string]
 */
function handleRegister($conn, $postData) {
    $message = '';
    $messageType = '';

    $fullName = sanitizeInput($postData['full_name'] ?? '');
    $email = sanitizeInput($postData['email'] ?? '');
    $username = sanitizeInput($postData['username'] ?? '');
    $password = $postData['password'] ?? '';
    $confirmPassword = $postData['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $message = 'Semua field harus diisi.';
        $messageType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
        $messageType = 'danger';
    } elseif ($password !== $confirmPassword) {
        $message = 'Password dan konfirmasi password tidak cocok.';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $message = 'Username atau email sudah terdaftar.';
            $messageType = 'danger';
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'member')");
            $stmt->bind_param("ssss", $username, $hashedPassword, $email, $fullName);
            if (!$stmt->execute()) {
                $message = 'Terjadi kesalahan saat mendaftar: ' . $conn->error;
                $messageType = 'danger';
            } else {
                $message = 'Pendaftaran berhasil! Silakan login untuk mengakses akun Anda.';
                $messageType = 'success';
            }
            $stmt->close();
        }
    }

    return ['message' => $message, 'messageType' => $messageType];
}

// Fungsi untuk memeriksa apakah pengguna saat ini memiliki peran sebagai admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fungsi untuk mengalihkan pengguna ke halaman login jika mereka belum login
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Fungsi untuk mengalihkan pengguna ke halaman utama jika mereka bukan admin
function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: index.php");
        exit();
    }
}

// Fungsi untuk mengambil konten terbaru dari database dengan batasan jumlah yang ditentukan
function getLatestContent($mysqli, $limit = 3) {
    $sql = "SELECT c.*, u.username FROM content c 
            JOIN users u ON c.author_id = u.id";
    
    $sql .= " ORDER BY c.created_at DESC LIMIT ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk mengambil kegiatan yang akan datang dari database dengan batasan jumlah yang ditentukan
function getUpcomingEvents($mysqli, $limit = 3) {
    $sql = "SELECT * FROM events WHERE event_date >= NOW() ORDER BY event_date ASC LIMIT ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk membersihkan dan mensterilkan input dari pengguna untuk mencegah serangan injeksi
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk menghitung jumlah pesan yang belum dibaca di database.
function getUnreadMessageCount($conn) {
    $result = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0");
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Fungsi untuk menambahkan konten baru ke database
function addContent($conn, $title, $content, $image, $author_id) {
    $stmt = $conn->prepare("INSERT INTO content (title, content, image, author_id) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("sssi", $title, $content, $image, $author_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk menambahkan acara baru ke database
function addEvent($conn, $title, $description, $event_date, $location) {
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("ssss", $title, $description, $event_date, $location);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk memperbarui konten yang sudah ada di database
function updateContent($conn, $id, $title, $content, $image) {
    $stmt = $conn->prepare("UPDATE content SET title = ?, content = ?, image = ?, updated_at = NOW() WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("sssi", $title, $content, $image, $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk mengambil konten berdasarkan ID dari database
function getContentById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM content WHERE id = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
    $stmt->close();
    return $content;
}

// Fungsi untuk memperbarui kegiaatan yang sudah ada di database
function updateEvent($conn, $id, $title, $description, $event_date, $location) {
    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, location = ? WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("ssssi", $title, $description, $event_date, $location, $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk mengambil kegiatan berdasarkan ID dari database
function getEventById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
    return $event;
}

// Fungsi untuk mengambil semua anggota dari database dengan opsi pencarian
function getAllMembers($conn, $search = '') {
    $sql = "SELECT m.*, u.username, u.email, u.full_name FROM members m JOIN users u ON m.user_id = u.id";
    $params = [];
    $types = '';
    if (!empty($search)) {
        $sql .= " WHERE (u.full_name LIKE ? OR u.email LIKE ? OR u.username LIKE ?)";
        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam];
        $types = 'sss';
    }
    $sql .= " ORDER BY m.join_date DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    $stmt->close();
    return $members;
}

// Fungsi untuk menghapus konten berdasarkan ID dari database
function deleteContentById($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM content WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk menghapus acara berdasarkan ID dari database
function deleteEventById($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// FUngsi untuk mengambil semua pesan dari database dengan opsi pencarian dan status baca
function getAllMessages($conn, $search = '', $readStatus = '') {
    $sql = "SELECT * FROM messages";
    $params = [];
    $types = '';
    $conditions = [];

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
        $searchParam = "%$search%";
        $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
        $types .= 'ssss';
    }

    if ($readStatus !== '') {
        $conditions[] = "is_read = ?";
        $params[] = ($readStatus === 'read') ? 1 : 0;
        $types .= 'i';
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();
    return $messages;
}

// Fungsi untuk memperbarui status baca pesan berdasarkan ID
function updateMessageReadStatus($conn, $id, $isRead) {
    $stmt = $conn->prepare("UPDATE messages SET is_read = ? WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("ii", $isRead, $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk menghapus pesan berdasarkan ID dari database
function deleteMessageById($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk menghitung jumlah konten yang ada di database
function getContentCount($conn) {
    $result = $conn->query("SELECT COUNT(*) as count FROM content");
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['count'];
    }
    return 0;
}

// Fungsi untuk menghitung jumlah kegiatan yang ada di database
function getEventCount($conn) {
    $result = $conn->query("SELECT COUNT(*) as count FROM events");
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['count'];
    }
    return 0;
}

// Fungsi untuk menghitung jumlah anggota yang ada di database
function getMemberCount($conn) {
    $result = $conn->query("SELECT COUNT(*) as count FROM members");
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['count'];
    }
    return 0;
}

// Fungsi untuk mendapatkan member terbaru
function getLatestMembers($conn, $limit = 5) {
    $members = [];
    $stmt = $conn->prepare("SELECT m.*, u.full_name, u.email FROM members m JOIN users u ON m.user_id = u.id ORDER BY m.join_date DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    $stmt->close();
    return $members;
}

// Fungsi untuk mendapatkan pesan terbaru
function getLatestMessages($conn, $limit = 5) {
    $messages = [];
    $stmt = $conn->prepare("SELECT * FROM messages ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();
    return $messages;
}

// Fungsi untuk mendapatkan daftar konten dengan pencarian opsional
function getContents($conn, $search = '') {
    $sql = "SELECT c.*, u.username FROM content c JOIN users u ON c.author_id = u.id";
    $params = [];
    if (!empty($search)) {
        $sql .= " WHERE c.title LIKE ?";
        $params[] = "%$search%";
    }
    $sql .= " ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param("s", $params[0]);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $contents = [];
    while ($row = $result->fetch_assoc()) {
        $contents[] = $row;
    }
    $stmt->close();
    return $contents;
}

// Fungsi untuk mendapatkan daftar kegiatan dengan pencarian opsional
function getEvents($conn, $search = '') {
    $sql = "SELECT * FROM events";
    $params = [];
    if (!empty($search)) {
        $sql .= " WHERE title LIKE ?";
        $params[] = "%$search%";
    }
    $sql .= " ORDER BY event_date ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param("s", $params[0]);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $stmt->close();
    return $events;
}

// Fungsi untuk mendapatkan daftar anggota dengan pencarian opsional
function getMembers($conn, $search = '') {
    $sql = "SELECT m.*, u.username, u.email, u.full_name FROM members m JOIN users u ON m.user_id = u.id";
    $where = [];
    $params = [];
    $types = '';

    if (!empty($search)) {
        $where[] = "(u.full_name LIKE ? OR u.email LIKE ? OR u.username LIKE ?)";
        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam];
        $types = 'sss';
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY m.join_date DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    $stmt->close();
    return $members;
}

// Fungsi untuk menandai pesan sebagai sudah dibaca
function markMessageAsRead($conn, $id) {
    $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Fungsi untuk mendapatkan pesan berdasarkan ID
function getMessageById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE id = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();
    $stmt->close();
    return $message;
}