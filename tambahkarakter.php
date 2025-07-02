<?php
session_start();
include 'koneksi.php';
require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$enum_values = [];
$result = $koneksi->query("SHOW COLUMNS FROM karakter LIKE 'tipe'");
if ($result) {
    $row = $result->fetch_assoc();
    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $enum = explode(",", $matches[1]);
    foreach ($enum as $value) {
        $enum_values[] = trim($value, "'");
    }
}

$lastKodeNum = 0;
$resultLast = $koneksi->query("SELECT kode_karakter FROM karakter ORDER BY CAST(SUBSTRING(kode_karakter, 2) AS UNSIGNED) DESC LIMIT 1");
if ($resultLast && $resultLast->num_rows > 0) {
    $rowLast = $resultLast->fetch_assoc();
    $lastKodeStr = $rowLast['kode_karakter'];
    $lastKodeNum = (int)substr($lastKodeStr, 1);
}
$nextKodeFormatted = 'K' . str_pad($lastKodeNum + 1, 2, '0', STR_PAD_LEFT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $nextKodeFormatted;
    $nama = $_POST['nama_karakter'];
    $tipe = $_POST['tipe'];
    $keterangan = $_POST['keterangan'];
    $bounty = ($tipe === 'bajak laut' && isset($_POST['bounty']) && trim($_POST['bounty']) !== '') ? trim($_POST['bounty']) : '0';

    $foto = $_FILES['foto'];
    if ($foto['error'] === 0 && is_uploaded_file($foto['tmp_name'])) {
        $fotoData = file_get_contents($foto['tmp_name']);

        $stmt = $koneksi->prepare("INSERT INTO karakter (kode_karakter, nama_karakter, tipe, keterangan, bounty, foto) VALUES (?, ?, ?, ?, ?, ?)");
        $null = null;
        $stmt->bind_param("sssssb", $kode, $nama, $tipe, $keterangan, $bounty, $null);
        $stmt->send_long_data(5, $fotoData);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $filename = 'profil_' . strtolower(str_replace(' ', '_', $nama)) . '.pdf';

            $_SESSION['pdf_karakter'] = [
                'kode' => $kode,
                'nama' => $nama,
                'bounty' => $bounty,
                'tipe' => $tipe,
                'keterangan' => $keterangan,
                'filename' => $filename
            ];

            echo "<script>alert('Karakter berhasil ditambahkan! Silahkan kembali ke Dashboard untuk cetak pdf'); window.location = 'tambahkarakter.php';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan data.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Upload foto gagal.');</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/png" href="log.png">
  <title>Tambah Karakter</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: url('kapal.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      animation: fadeIn 1s ease-in-out;
    }

    .form-container {
      background: rgba(0, 0, 50, 0.6);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 123, 255, 0.7);
      width: 400px;
      backdrop-filter: blur(10px);
      animation: zoomIn 0.6s ease;
    }

    h2 {
      text-align: center;
      color: rgb(9, 126, 236);
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      color: rgb(38, 168, 233);
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: none;
      border-radius: 8px;
      background-color: rgba(84, 128, 194, 0.1);
      color: rgb(26, 162, 180);
      box-shadow: inset 0 0 5px rgba(9, 29, 116, 0.3);
      font-size: 14px;
    }

    textarea {
      resize: vertical;
      height: 80px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      border: none;
      border-radius: 10px;
      color: #ffffff;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    #bountyGroup {
      display: none;
    }

    #fotoPreview {
      margin-top: 15px;
      max-width: 100%;
      border-radius: 10px;
      border: 2px solid #007bff;
      box-shadow: 0 0 10px #00ffffaa;
      display: none;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes zoomIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
</head>

<body>
  <div class="form-container">
    <h2>Tambah Karakter</h2>
    <form action="" method="post" enctype="multipart/form-data">
      <label for="kode_karakter">Kode Karakter (otomatis)</label>
      <input type="text" name="kode_karakter" id="kode_karakter" value="<?= htmlspecialchars($nextKodeFormatted) ?>"
        readonly />

      <label for="nama_karakter">Nama Karakter</label>
      <input type="text" name="nama_karakter" id="nama_karakter" required />

      <label for="tipe">Tipe Karakter</label>
      <select name="tipe" id="tipe" onchange="cekTipe()" required>
        <option value="">-- Pilih Tipe --</option>
        <?php foreach ($enum_values as $value): ?>
        <option value="<?= htmlspecialchars($value) ?>">
          <?= htmlspecialchars($value) ?>
        </option>
        <?php endforeach; ?>
      </select>

      <div id="bountyGroup">
  <label for="bounty">Bounty (jika Bajak Laut)</label>
  <input type="text" name="bounty" id="bounty" />
</div>

      <label for="keterangan">Keterangan</label>
      <input type="text" name="keterangan" id="keterangan" required />

      <label for="foto">Foto Karakter</label>
      <input type="file" name="foto" id="foto" accept="image/*" required />
      <img id="fotoPreview" alt="Preview Foto Karakter" />

      <button type="submit">Simpan Karakter</button>
      <button type="button" onclick="window.location.href='index_admin.php'"
        style="margin-top:10px; background:#f44336;">Batal</button>
    </form>
  </div>

  <script>
    function cekTipe() {
      var tipe = document.getElementById("tipe").value.trim().toLowerCase();
      var bountyGroup = document.getElementById("bountyGroup");
      if (tipe === "bajak laut") {
        bountyGroup.style.display = "block";
      } else {
        bountyGroup.style.display = "none";
        document.getElementById("bounty").value = "";
      }
    }

    function previewFoto(input) {
      var preview = document.getElementById('fotoPreview');
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    }

    window.onload = function () {
      cekTipe();
      document.getElementById('foto').addEventListener('change', function () {
        previewFoto(this);
      });
    }
  </script>
</body>

</html>