<?php 
session_start();
include 'koneksi.php';

$role = $_SESSION['role'] ?? '';
if (!isset($_SESSION['user_id']) || $role !== 'admin') {
    header("Location: login.html");
    exit();
}

$type = $_GET['type'] ?? '';
$title = '';
$query = '';

switch ($type) {
    case 'pengunjung_hari_ini':
    $title = "Pengunjung Hari Ini";
    $today = date("Y-m-d");
    $query = "SELECT user_id, username, waktu_login FROM log_pengunjung WHERE DATE(waktu_login) = '$today' ORDER BY waktu_login DESC";
    break;

case 'pengunjung_bulan_ini':
    $title = "Pengunjung Bulan Ini";
    $query = "SELECT user_id, username, waktu_login FROM log_pengunjung WHERE MONTH(waktu_login) = MONTH(CURRENT_DATE()) AND YEAR(waktu_login) = YEAR(CURRENT_DATE()) ORDER BY waktu_login DESC";
    break;


    case 'total_karakter':
        $title = "Daftar Semua Karakter";
        $query = "SELECT * FROM karakter ORDER BY nama_karakter";
        break;
    case 'bajak_laut':
        $title = "Daftar Bajak Laut";
        $query = "SELECT * FROM karakter WHERE tipe = 'bajak laut' ORDER BY nama_karakter";
        break;
    case 'marine':
        $title = "Daftar Marine";
        $query = "SELECT * FROM karakter WHERE tipe = 'marine' ORDER BY nama_karakter";
        break;
    case 'buah_iblis':
        $title = "Daftar Buah Iblis";
        $query = "SELECT * FROM karakter WHERE tipe = 'buah iblis' ORDER BY nama_karakter";
        break;
    default:
        echo "<h3 style='color: white; text-align:center;'>Tipe data tidak diketahui.</h3>";
        exit();
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" type="image/png" href="log.png">
  <title>
    <?= htmlspecialchars($title) ?>
  </title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet" />
  <style>
    html,
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      height: 100%;
      margin: 0;
      font-family: 'Orbitron', sans-serif;
      background: url('kapal.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      overflow-x: hidden;
      overflow-y: auto;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 80, 0.7);
      z-index: 0;
    }

    #container {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      color: #00ffff;
      text-shadow: 0 0 15px #00f, 0 0 25px #0ff;
      margin-bottom: 20px;
      text-align: center;
    }

    .table-container {
      width: 100%;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(0, 0, 50, 0.3);
      border-radius: 10px;
    }

    th,
    td {
      padding: 12px 14px;
      border: 1px solid rgba(0, 255, 255, 0.3);
      text-align: left;
    }

    th {
      background-color: rgba(0, 123, 255, 0.8);
      color: #fff;
      text-shadow: 0 0 10px #0ff;
    }

    tr:nth-child(even) {
      background-color: rgba(255, 255, 255, 0.05);
    }

    tr:hover {
      background-color: rgba(0, 123, 255, 0.2);
      transition: 0.3s;
    }

    .btn {
      padding: 6px 12px;
      background-color: #00bcd4;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      margin: 2px;
      display: inline-block;
      transition: all 0.3s ease;
      font-weight: bold;
      box-shadow: 0 0 10px #00ffff80;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #0097a7;
      transform: scale(1.05);
      box-shadow: 0 0 15px #00ffff;
    }

    .tombol-kembali {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1000;
    }

    .tombol-kembali .btn {
      background-color: rgba(0, 188, 212, 0.8);
      padding: 8px 14px;
      font-size: 14px;
      border-radius: 10px;
    }

    .tombol-kembali .btn:hover {
      background-color: #0097a7;
      transform: scale(1.05);
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background: #00bcd4;
      border-radius: 8px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #0097a7;
    }

    @media (max-width: 600px) {
      .tombol-kembali .btn {
        padding: 6px 10px;
        font-size: 13px;
      }
    }
  </style>
</head>

<body>

  <div id="container">
    <h2>
      <?= htmlspecialchars($title) ?>
    </h2>

    <div class="table-container">
      <?php if (mysqli_num_rows($result) > 0): ?>
      <table>
        <thead>
          <tr>
            <?php if (strpos($type, 'pengunjung') !== false): ?>
            <th>User ID</th>
            <th>Username</th>
            <th>Waktu Login</th>
            <?php else: ?>
            <th>No</th>
            <th>Nama Karakter</th>
            <th>Tipe</th>
            <th>Keterangan</th>
            <th>Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <?php if (strpos($type, 'pengunjung') !== false): ?>
    <td><?= htmlspecialchars($row['user_id']) ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['waktu_login']) ?></td>
<?php else: ?>

            <td>
              <?= $no++ ?>
            </td>
            <td>
              <?= htmlspecialchars($row['nama_karakter']) ?>
            </td>
            <td>
              <?= htmlspecialchars($row['tipe']) ?>
            </td>
            <td>
              <?= htmlspecialchars($row['keterangan']) ?>
            </td>
            <td>
              <a class="btn" href="detail_karakter.php?id=<?= $row['id'] ?>">Lihat</a>
              <a class="btn" href="edit_karakter.php?id=<?= $row['id'] ?>">Edit</a>
              <a class="btn" href="hapus_karakter.php?id=<?= $row['id'] ?>"
                onclick="return confirm('Hapus karakter ini?')">Hapus</a>
            </td>
            <?php endif; ?>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
      <p style="text-align:center;">Tidak ada data ditemukan.</p>
      <?php endif; ?>
    </div>

    <div class="tombol-kembali">
      <a href="index_admin.php" class="btn">⬅ Kembali ke Beranda</a>
    </div>
  </div>

</body>

</html>