<?php
session_start();

$role = $_SESSION['role'] ?? '';
if (!isset($_SESSION['user_id']) || $role !== 'admin') {
    header("Location: login.html");
    exit();
}

include 'koneksi.php';
include 'navbar_admin.php';

$label = 'Data Marine';
$nama_tabel = 'karakter';
$tipe = 'marine';

$search = isset($_GET['cari']) ? trim($_GET['cari']) : '';

$sql = "SELECT * FROM $nama_tabel WHERE tipe = '$tipe'";

if ($search !== '') {
    $sql .= " AND (nama_karakter LIKE '%$search%' OR kode_karakter LIKE '%$search%')";
}

$sql .= " ORDER BY id ASC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="icon" type="image/png" href="log.png">
    <title><?= $label ?></title>
    <style>
        body {
            background-color: #eef6ff;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            padding: 100px 40px 40px 40px;
        }
        h1 {
            color: #073c77;
            margin-bottom: 20px;
        }
        .tambah-btn {
            background-color: #145ad0;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
            font-size: 14px;
        }
        .search-form button {
            padding: 8px 16px;
            background-color: #145ad0;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #145ad0;
            color: white;
        }
        img {
            width: 80px;
            height: auto;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .aksi-btn {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 20px auto;
            width: fit-content;
        }
        .aksi-btn a {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            transition: 0.2s ease;
        }
        .edit-btn {
            background-color: #28a745;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .hapus-btn {
            background-color: #dc3545;
        }
        .hapus-btn:hover {
            background-color: #c82333;
        }
        .pdf-section form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        .pdf-section a {
            font-size: 13px;
        }
        .pdf-section input[type="file"] {
            font-size: 12px;
        }
        .pdf-section button {
            padding: 4px 10px;
            font-size: 12px;
            background-color: #145ad0;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .delete-pdf {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            font-size: 12px;
            border-radius: 4px;
            text-decoration: none;
        }

        .cetak-btn {
    display: inline-block;
    margin-top: 5px;
    background-color: #ffc107;
    color: black;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}
.cetak-btn:hover {
    background-color: #e0a800;
}
.download-btn {
  display: inline-block;
  background-color: #28a745;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  text-decoration: none;
  margin-left: 5px;
}
.download-btn:hover {
  background-color: #218838;
}

    </style>
</head>

<body>
    <h1><?= $label ?></h1>

    <a class="tambah-btn" href="tambahkarakter.php?tipe=<?= urlencode($tipe) ?>">+ Tambah Karakter Marine</a>

    <form class="search-form" method="GET" action="">
        <input type="text" name="cari" placeholder="Cari Nama atau Kode Karakter" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Karakter</th>
                <th>Nama Karakter</th>
                <th>Keterangan</th>
                <th>Foto</th>
                <th>PDF</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $found = false;
            while ($row = mysqli_fetch_assoc($query)):
                $found = true;
                $id = $row['id'];
                $pdfData = mysqli_query($koneksi, "SELECT * FROM file_pdf WHERE karakter_id = $id");
                $pdf = mysqli_fetch_assoc($pdfData);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['kode_karakter']) ?></td>
                <td><?= htmlspecialchars($row['nama_karakter']) ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td>
                    <?php if (!empty($row['foto'])): ?>
                        <img src="tampil_foto.php?id=<?= htmlspecialchars($row['id']) ?>" alt="Foto <?= htmlspecialchars($row['nama_karakter']) ?>" style="width: 100px; height: 100px; object-fit: contain; border: 1px solid #ccc;" />
                    <?php else: ?>
                        <em>Gambar tidak tersedia</em>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="pdf-section">
                        <form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="tipe" value="<?= $tipe ?>">
                            <?php if ($pdf): ?>
                                <a href="pdf/<?= htmlspecialchars($pdf['nama_file']) ?>" target="_blank" style="color:green;">📥 Download PDF</a>
                                <a href="hapus_pdf.php?id=<?= $id ?>&tipe=<?= urlencode($tipe) ?>" class="delete-pdf" onclick="return confirm('Hapus file PDF ini?')">🗑️ Hapus PDF</a>
                                <input type="file" name="pdf" accept=".pdf" required>
                                <button type="submit">📤 Ganti</button>
                            <?php else: ?>
                                <span style="color:gray;">Belum ada PDF</span>
                                <input type="file" name="pdf" accept=".pdf" required>
                                <button type="submit">📤 Upload</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </td>
                <td class="aksi-btn">
  <a class="edit-btn" href="edit_karakter.php?id=<?= $id ?>&tipe=<?= urlencode($tipe) ?>">Edit</a>
  <a class="hapus-btn" href="hapus_karakter.php?id=<?= $id ?>&tipe=<?= urlencode($tipe) ?>" onclick="return confirm('Yakin ingin menghapus karakter ini?')">Hapus</a>
  <a class="cetak-btn" href="cetak_pdf.php?id=<?= $row['kode_karakter'] ?>" target="_blank">Cetak</a>
  <a class="download-btn" href="cetak_pdf.php?id=<?= $row['kode_karakter'] ?>&download=1" download>Download PDF</a>
</td>

            </tr>
            <?php endwhile; ?>

            <?php if (!$found): ?>
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada karakter ditemukan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>
