<?php
include 'koneksi.php';

$id = intval($_GET['id']);
$tipe = $_GET['tipe'] ?? 'bajak laut'; // fallback default

// Ambil data file PDF berdasarkan karakter_id
$pdf = mysqli_query($koneksi, "SELECT nama_file FROM file_pdf WHERE karakter_id = $id");
$data = mysqli_fetch_assoc($pdf);

// Hapus file dari folder dan database
if ($data) {
    $file_path = 'pdf/' . $data['nama_file'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    mysqli_query($koneksi, "DELETE FROM file_pdf WHERE karakter_id = $id");
}

// Arahkan balik sesuai tipe
switch ($tipe) {
    case 'marine':
        header("Location: marine_admin.php");
        break;
    case 'buah iblis':
        header("Location: buahiblis_admin.php");
        break;
    default:
        header("Location: bajaklaut_admin.php");
        break;
}
exit();
?>
