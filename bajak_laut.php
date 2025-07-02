<?php
session_start(); // Harus di atas sebelum ada output apapun

include 'koneksi.php';
include 'navbar.php';

$query = "SELECT * FROM karakter WHERE tipe = 'bajak laut'";
$result = mysqli_query($koneksi, $query);

$role = $_SESSION['role'] ?? 'pengunjung';
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bajak Laut - One Piece</title>
  <link rel="icon" type="image/png" href="log.png">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #060c2a;
    }
    .card-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      padding: 60px 20px 20px;
    }
    .card {
      background-color: rgb(18, 6, 121);
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(29, 32, 214, 0.9);
      width: 250px;
      overflow: hidden;
      color: white;
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.05);
    }
    .card-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-bottom: 1px solid #333;
    }
    .card-content {
      padding: 15px;
      flex-grow: 1;
    }
    .card-content h2 {
      margin: 0 0 10px 0;
      font-size: 1.2rem;
      font-weight: bold;
    }
    .card-content p {
      font-size: 0.9rem;
      line-height: 1.3;
      color: #ccc;
    }
    .bounty {
      margin-top: 8px;
      font-weight: bold;
      color: rgb(240, 19, 19);
    }
    .pdf-buttons {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin: 10px 15px 15px;
    }
    .btn-pdf {
      text-align: center;
      background-color: #00c6ff;
      padding: 6px;
      border-radius: 5px;
      text-decoration: none;
      color: #000;
      font-weight: bold;
      font-size: 0.9rem;
    }
    .btn-pdf:hover {
      background-color: #00a2d6;
    }
    .no-pdf {
      font-size: 0.8rem;
      color: lightgray;
      margin: 10px 15px;
    }
  </style>
</head>
<body>

<div class="card-container">
  <?php if (!$result): ?>
    <p style="color:white;">Query gagal: <?= mysqli_error($koneksi); ?></p>
  <?php elseif (mysqli_num_rows($result) == 0): ?>
    <p style="color:white;">Tidak ada karakter Bajak Laut.</p>
  <?php else: ?>
    <?php while ($row = mysqli_fetch_assoc($result)) : 
      $id = $row['id'];
      $pdfResult = mysqli_query($koneksi, "SELECT * FROM file_pdf WHERE karakter_id = $id");
      $pdf = mysqli_fetch_assoc($pdfResult);
    ?>
    <div class="card">
      <img class="card-image" src="tampil_foto.php?id=<?= $id ?>" alt="<?= htmlspecialchars($row['nama_karakter']) ?>">
      <div class="card-content">
        <h2><?= htmlspecialchars($row['nama_karakter']) ?></h2>
        <p><?= htmlspecialchars($row['keterangan']) ?></p>
        <p class="bounty">Bounty: <?= $row['bounty'] ?></p>
      </div>
      <?php if ($pdf): ?>
        <div class="pdf-buttons">
          <a class="btn-pdf" href="pdf/<?= htmlspecialchars($pdf['nama_file']) ?>" target="_blank">📖 Lihat PDF</a>
          <a class="btn-pdf" href="pdf/<?= htmlspecialchars($pdf['nama_file']) ?>" download>⬇️ Download PDF</a>
        </div>
      <?php else: ?>
        <div class="no-pdf">📄 PDF belum tersedia</div>
      <?php endif; ?>
    </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

</body>
</html>
