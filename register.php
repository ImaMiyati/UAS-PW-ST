<?php
include 'koneksi.php';

$message = '';
$icon = '';
$redirect = '';
$showAlert = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($username) || empty($password) || empty($email)) {
        $message = 'Semua field harus diisi!';
        $icon = 'warning';
        $redirect = 'register.html';
        $showAlert = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
        $icon = 'error';
        $redirect = 'register.html';
        $showAlert = true;
    } else {
        $stmt_check = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $message = 'Username sudah dipakai, coba yang lain.';
            $icon = 'error';
            $redirect = 'register.html';
            $showAlert = true;
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("INSERT INTO users (username, email, password, role, tanggal_daftar) VALUES (?, ?, ?, 'pengunjung', NOW())");
            $stmt->bind_param("sss", $username, $email, $pass_hash);
            if ($stmt->execute()) {
                $message = 'Akun berhasil dibuat. Silakan login.';
                $icon = 'success';
                $redirect = 'login.html';
                $showAlert = true;
            } else {
                $message = 'Terjadi kesalahan saat menyimpan data.';
                $icon = 'error';
                $redirect = 'register.html';
                $showAlert = true;
            }
            $stmt->close();
        }
        $stmt_check->close();
    }

    $koneksi->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Proses Register</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if ($showAlert): ?>
<script>
Swal.fire({
    icon: '<?= $icon ?>',
    title: '<?= $icon === "success" ? "Berhasil!" : "Gagal!" ?>',
    text: '<?= $message ?>',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = '<?= $redirect ?>';
});
</script>
<?php else: ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Akses Tidak Valid',
    text: 'Silakan kembali ke halaman register.',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = 'register.html';
});
</script>
<?php endif; ?>
</body>
</html>
