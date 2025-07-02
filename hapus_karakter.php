<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location='index_admin.php';</script>";
    exit;
}

$id = (int)$_GET['id'];

$stmt = $koneksi->prepare("DELETE FROM karakter WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Karakter berhasil dihapus!'); window.location = 'index_admin.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus karakter.'); window.location = 'index_admin.php';</script>";
}

$stmt->close();
?>