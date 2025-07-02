<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Set data sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['tanggal_daftar'] = $user['tanggal_daftar'];

        // ✅ Ini penting supaya splash tidak muncul lagi setelah login
        $_SESSION['sudah_lihat_splash'] = true;

        // Log pengunjung jika bukan admin
        if ($user['role'] === 'pengunjung') {
            $user_id = $user['id'];
            $username_log = $user['username'];
            $log_stmt = $koneksi->prepare("INSERT INTO log_pengunjung (user_id, username, waktu_login) VALUES (?, ?, NOW())");
            $log_stmt->bind_param("is", $user_id, $username_log);
            $log_stmt->execute();
        }

        // Redirect berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: index_admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo "<script>
                alert('Login gagal! Username atau password salah.');
                window.location.href='login.html';
              </script>";
    }
} else {
    echo "<script>
            alert('Akses tidak valid.');
            window.location.href='login.html';
          </script>";
}
?>
