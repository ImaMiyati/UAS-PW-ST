<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Upload foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_profil'])) {
    $folder = 'foto_profil/';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
    $filename = 'user_' . $user_id . '.' . $ext;
    $target = $folder . $filename;

    if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target)) {
        mysqli_query($koneksi, "UPDATE users SET foto_profil = '$filename' WHERE id = $user_id");
    }
}

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pengguna</title>
  <link rel="icon" type="image/png" href="log.png">
  <style>
    html, body {
      height: 100%; margin: 0; padding: 0;
    }
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: url('onepiece.jpg') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      color: darkblue;
      overflow: hidden;
    }
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background-color: rgba(209, 213, 224, 0.34);
      z-index: -1;
    }

    .profile-card {
      background-color: rgba(116, 163, 218, 0.3);
      padding: 30px 40px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      text-align: center;
      max-width: 500px;
      width: 90%;
      position: relative;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
      animation: rgbGlow 5s linear infinite;
    }

    @keyframes rgbGlow {
      0% { box-shadow: 0 0 20px #ff0000; }
      25% { box-shadow: 0 0 20px #00ff00; }
      50% { box-shadow: 0 0 20px #0000ff; }
      75% { box-shadow: 0 0 20px #ffff00; }
      100% { box-shadow: 0 0 20px #ff0000; }
    }

    .profile-image {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid white;
      margin-bottom: 10px;
      position: relative;
    }

    .upload-container {
      position: relative;
      display: inline-block;
    }

    .upload-icon {
      position: absolute;
      bottom: 10px;
      right: -10px;
      background-color: #145ad0;
      color: white;
      border: none;
      border-radius: 50%;
      padding: 8px 10px;
      cursor: pointer;
      font-size: 16px;
      z-index: 2;
    }

    .upload-icon input[type='file'] {
      display: none;
    }

    .profile-info {
      margin-top: 15px;
    }

    .profile-info p {
      margin: 8px 0;
    }

    .profile-info p span {
      font-weight: bold;
    }

    .button-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 20px;
      background: linear-gradient(135deg, #6b73ff, #000dff);
      color: white;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .btn:hover {
      background: linear-gradient(135deg, #52c2f0, #1d63ff);
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <div class="profile-card">
    <form method="POST" enctype="multipart/form-data">
      <div class="upload-container">
        <?php if (!empty($user['foto_profil']) && file_exists('foto_profil/' . $user['foto_profil'])): ?>
          <img src="foto_profil/<?= htmlspecialchars($user['foto_profil']) ?>" alt="Foto Profil" class="profile-image">
        <?php else: ?>
          <img src="default_user.png" alt="Default Foto" class="profile-image">
        <?php endif; ?>

        <label class="upload-icon" title="Ubah Foto 📷">
          📷
          <input type="file" name="foto_profil" accept="image/*" onchange="this.form.submit()">
        </label>
      </div>
    </form>

    <div class="profile-info">
      <p><span>Username:</span> <?= htmlspecialchars($user['username']) ?></p>
      <p><span>Email:</span> <?= htmlspecialchars($user['email']) ?></p>
      <p><span>Tanggal Daftar:</span> <?= htmlspecialchars($user['tanggal_daftar']) ?></p>
    </div>

    <div class="button-container">
      <a href="logout.php" class="btn">🔒 Logout</a>
      <a href="index.php" class="btn">🏠 Beranda</a>
    </div>
  </div>

</body>
</html>
