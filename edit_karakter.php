<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "ID karakter tidak valid!";
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM karakter WHERE id= '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $tipe = $_POST['tipe'];
    $keterangan = $_POST['keterangan'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['foto']['tmp_name'];
        $fotoData = addslashes(file_get_contents($tmpName));

        $update = mysqli_query($koneksi, "UPDATE karakter SET 
            nama_karakter='$nama',
            tipe='$tipe',
            keterangan='$keterangan',
            foto='$fotoData'
            WHERE id='$id'");
    } else {
        $update = mysqli_query($koneksi, "UPDATE karakter SET 
            nama_karakter='$nama',
            tipe='$tipe',
            keterangan='$keterangan'
            WHERE id='$id'");
    }

    if ($update) {
        header("Location: detail_karakter.php?id=$id");
        exit();
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Karakter</title>
  <link rel="icon" type="image/png" href="log.png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
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

    form {
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
      color: rgb(12, 3, 94);
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      color: rgb(38, 168, 233);
    }

    input[type="text"],
    select,
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      margin-bottom: 16px;
      border: none;
      border-radius: 8px;
      background-color: rgba(84, 128, 194, 0.1);
      color: rgb(149, 243, 255);
      box-shadow: inset 0 0 5px rgba(9, 29, 116, 0.3);
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
      transition: background 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    img {
      margin-top: 10px;
      max-width: 100px;
      border-radius: 10px;
      border: 2px solid #007bff;
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
  <h2>Edit Karakter</h2>
  <form method="post" enctype="multipart/form-data">
    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama_karakter']) ?>" required><br><br>

    <label>Tipe:</label><br>
    <select name="tipe" required>
      <option value="bajak laut" <?=$data['tipe']=='bajak laut' ? 'selected' : '' ?>>bajak laut</option>
      <option value="marine" <?=$data['tipe']=='marine' ? 'selected' : '' ?>>marine</option>
      <option value="buah iblis" <?=$data['tipe']=='buah iblis' ? 'selected' : '' ?>>buah iblis</option>
    </select><br><br>

    <label>Keterangan:</label><br>
    <textarea name="keterangan" required><?= htmlspecialchars($data['keterangan']) ?></textarea><br><br>

    <label>Ganti foto (opsional):</label><br>
    <input type="file" name="foto" id="fotoInput" accept="image/*"><br><br>

    <img id="previewFoto" src="tampil_foto.php?id=<?= $data['id'] ?>" alt="Preview Foto"
      style="max-width:100px; border-radius:10px; border:2px solid #007bff; display:block; margin-bottom:15px;"><br>

    <button type="submit" name="submit">Simpan Perubahan</button>
    <button type="button" onclick="window.location.href='detail_karakter.php?id=<?= $id ?>'"
      style="margin-top:10px; background:#f44336;">Batal</button>
  </form>

  <script>
    const fotoInput = document.getElementById('fotoInput');
    const preview = document.getElementById('previewFoto');

    fotoInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.setAttribute('src', e.target.result);
          preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
      } else {
        preview.setAttribute('src', 'tampil_foto.php?id=<?= $data['id'] ?>');
      }
    });
  </script>

</body>

</html>