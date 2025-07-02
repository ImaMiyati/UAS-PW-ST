<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID tidak valid');
}

$id = (int)$_GET['id'];

$query = "SELECT foto, tipe_foto FROM karakter WHERE id = ?";
$stmt = $koneksi->prepare($query);

if (!$stmt) {
    http_response_code(500);
    exit('Query gagal: ' . $koneksi->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    http_response_code(404);
    exit('Data tidak ditemukan');
}

$stmt->bind_result($fotoData, $tipeFoto);
$stmt->fetch();
$stmt->close();

if (empty($fotoData)) {
    http_response_code(404);
    exit('Foto kosong');
}

if (!$tipeFoto || strpos($tipeFoto, 'image/') !== 0) {
    $tipeFoto = 'image/jpeg';
}

if (ob_get_length()) {
    ob_end_clean();
}

header("Content-Type: $tipeFoto");
echo $fotoData;
exit;
?>
