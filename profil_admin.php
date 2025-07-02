<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="log.png">
  <title>Profil Pengguna</title>
  <link rel="stylesheet" href="style.css">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
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
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(209, 213, 224, 0.34);
      z-index: -1;
    }

    .profile-card {
      background-color: rgba(116, 163, 218, 0.3);
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(13, 72, 233, 0.6);
      backdrop-filter: blur(10px);
      text-align: center;
      max-width: 500px;
      width: 90%;
    }

    .button-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
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
    <h2>Profil Admin</h2>
    <div class="profile-info">
      <p><span>ID:</span>
        <?= htmlspecialchars($user['id']) ?>
      </p>
      <p><span>Username:</span>
        <?= htmlspecialchars($user['username']) ?>
      </p>
      <p><span>Sebagai:</span>
        <?= htmlspecialchars($user['role']) ?>
      </p>
    </div>

    <div class="button-container">
      <a href="logout.php" class="btn">🔒 Logout</a>
      <a href="index_admin.php" class="btn">🏠 Beranda</a>
    </div>

  </div>

  <script>
    function togglePassword() {
      const passField = document.getElementById('passwordField');
      const toggleBtn = document.querySelector('.toggle-password');

      if (passField.innerText.includes('*')) {
        passField.innerText = "<?= $user['password'] ?>";
        toggleBtn.innerText = "Sembunyikan";
      } else {
        passField.innerText = "<?= str_repeat('*', strlen($user['password'])) ?>";
        toggleBtn.innerText = "Tampilkan";
      }
    }
  </script>

</body>

</html>