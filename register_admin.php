<?php
include 'koneksi.php';

$username = 'admin';
$password_plain = 'admin123';
$email = 'admin@email.com';
$role = 'admin';

// Cek apakah admin sudah ada
$sql_check = "SELECT * FROM users WHERE username = ?";
$stmt = $koneksi->prepare($sql_check);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "✅ Admin sudah ada. Tidak perlu setup ulang.";
} else {
    // Buat admin
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql_insert);
    $stmt->bind_param("ssss", $username, $email, $password_hash, $role);

    if ($stmt->execute()) {
        echo "✅ Admin berhasil dibuat:<br>";
        echo "Username: <strong>admin</strong><br>";
        echo "Password: <strong>admin123</strong>";
    } else {
        echo "❌ Gagal buat admin: " . $stmt->error;
    }
}
?>
