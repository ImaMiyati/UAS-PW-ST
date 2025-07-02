<?php
session_start();
include 'koneksi.php';
require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Ambil dari session atau GET id
if (isset($_GET['id'])) {
    $kode = $_GET['id'];
    $query = $koneksi->query("SELECT * FROM karakter WHERE kode_karakter = '$kode' LIMIT 1");
    if ($query && $query->num_rows > 0) {
        $data = $query->fetch_assoc();
        $data['filename'] = 'profil_' . strtolower(str_replace(' ', '_', $data['nama_karakter'])) . '.pdf';
    } else {
        echo "Data tidak ditemukan.";
        exit();
    }
} elseif (isset($_SESSION['pdf_karakter'])) {
    $data = $_SESSION['pdf_karakter'];
    unset($_SESSION['pdf_karakter']);
    $kode = $data['kode'];
    $query = $koneksi->query("SELECT foto FROM karakter WHERE kode_karakter = '$kode' LIMIT 1");
    $fotoData = ($query && $query->num_rows > 0) ? $query->fetch_assoc()['foto'] : '';
} else {
    echo "Data tidak ditemukan.";
    exit();
}

// Ambil foto dari DB
$fotoBase64 = '';
$mimeType = 'image/jpeg';
if (!empty($kode)) {
    $query = $koneksi->query("SELECT foto FROM karakter WHERE kode_karakter = '$kode' LIMIT 1");
    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $fotoData = $row['foto'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($fotoData);
        $fotoBase64 = base64_encode($fotoData);
    }
}

$bountyRow = '';
$tipeKarakter = strtolower(trim($data['tipe']));
if ($tipeKarakter === 'bajak laut') {
    $bountyFormatted = ($data['bounty'] !== '' && $data['bounty'] !== '0') ? $data['bounty'] : '-';
    $bountyRow = "<tr><td><b>Bounty</b></td><td>$bountyFormatted</td></tr>";
}

$html = "
<style>
    body { font-family: Arial, sans-serif; font-size: 14px; padding: 20px; }
    h1 { text-align: center; font-size: 24px; color: darkblue; margin-bottom: 10px; }
    h2 { text-align: center; font-size: 20px; font-weight: bold; margin: 10px 0; }
    .foto-karakter { text-align: center; margin: 20px 0; }
    table { border-collapse: collapse; margin-top: 20px; width: 100%; }
    td { border: 1px solid #333; padding: 10px; }
</style>
<h1>PROFIL KARAKTER</h1>
<div class='foto-karakter'>
    <img src='data:$mimeType;base64,$fotoBase64' width='180' style='border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.5);'><br>
    <h2>{$data['nama_karakter']}</h2>
</div>
<table>
    $bountyRow
    <tr><td><b>Tipe</b></td><td>{$data['tipe']}</td></tr>
    <tr><td><b>Keterangan</b></td><td>{$data['keterangan']}</td></tr>
</table>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream($data['filename'], ["Attachment" => false]);
exit();
