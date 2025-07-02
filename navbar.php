<?php
$role = $_SESSION['role'] ?? 'pengunjung';
$current = basename($_SERVER['PHP_SELF']);
?>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: Arial, sans-serif;
    background-image: url('onepiece.jpg');
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
    background-attachment: fixed;
    min-height: 100vh;
    padding-left: 30px;
    padding-right: 30px;
    position: relative;
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

  /* Animations */
  @keyframes rgbBlueGlow {
    0% {
      box-shadow: 0 0 10px rgba(0, 212, 255, 0.6), 0 0 20px rgba(0, 212, 255, 0.4);
    }
    50% {
      box-shadow: 0 0 15px rgba(0, 102, 255, 0.9), 0 0 30px rgba(0, 212, 255, 0.6);
    }
    100% {
      box-shadow: 0 0 10px rgba(0, 212, 255, 0.6), 0 0 20px rgba(0, 212, 255, 0.4);
    }
  }

  @keyframes zoomGlow {
    0% {
      transform: scale(1);
      text-shadow: 0 0 5px #0dcaf0, 0 0 10px #00aaff;
    }
    50% {
      transform: scale(1.08);
      text-shadow: 0 0 15px #0dcaf0, 0 0 25px #00aaff;
    }
    100% {
      transform: scale(1);
      text-shadow: 0 0 5px #0dcaf0, 0 0 10px #00aaff;
    }
  }

  .navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 50px;
    z-index: 1000;
    background-color: rgba(4, 13, 92, 0.9);
    backdrop-filter: blur(8px);
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 238, 255, 0.96);
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    animation: rgbBlueGlow 3s infinite ease-in-out;
  }

  .navbar-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 15px;
    flex-wrap: nowrap;
  }

  .navbar-logo {
    color: white;
    font-size: 22px;
    font-weight: bold;
    line-height: 1.2;
    animation: zoomGlow 2.5s infinite ease-in-out;
    transform-origin: left center;
  }

  .navbar-menu {
    list-style: none;
    align-items: center;
    display: flex;
    gap: 25px;
    margin: 0;
    padding: 0;
  }

  .navbar-menu li a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 10px 15px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease-in-out;
    border-radius: 8px;
  }

  .navbar-menu li a::before {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%) scaleX(0);
    transform-origin: center;
    width: 100%;
    height: 3px;
    background: #0dcaf0;
    box-shadow: 0 0 8px #0dcaf0;
    transition: transform 0.4s ease;
    border-radius: 50px;
  }

  .navbar-menu li a:hover::before,
  .navbar-menu li a.active::before {
    transform: translateX(-50%) scaleX(1);
  }

  .navbar-menu li a:hover,
  .navbar-menu li a.active {
    color: #0dcaf0;
    text-shadow: 0 0 6px #0dcaf0, 0 0 12px #00aaff;
    background-color: rgba(0, 255, 255, 0.05);
  }

  .navbar-search-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
  }

  .navbar-search-toggle {
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 6px 8px;
    transition: all 0.3s ease;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    user-select: none;
  }

  .navbar-search-form {
    display: none;
    position: absolute;
    left: -200px;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.95);
    padding: 4px 8px;
    border-radius: 20px;
    box-shadow: 0 0 10px #0dcaf0;
    z-index: 1001;
  }

  .navbar-search-form input[type="text"] {
    padding: 6px 12px;
    font-size: 14px;
    border: 1px solid #0dcaf0;
    border-radius: 20px;
    outline: none;
    width: 180px;
    background: white;
    color: black;
  }
</style>

<nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">Pencarian Karakter One Piece</div>

    <ul class="navbar-menu">
      <li>
        <div class="navbar-search-wrapper">
          <div class="navbar-search-toggle" onclick="toggleSearchBar()">🔍</div>
          <form id="navbar-search-form" class="navbar-search-form" method="GET" action="index.php">
            <input type="text" name="kata_cari" placeholder="Cari karakter..."
              value="<?= isset($_GET['kata_cari']) ? htmlspecialchars($_GET['kata_cari']) : '' ?>">
          </form>
        </div>
      </li>
      <li><a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Beranda</a></li>
      <li><a href="bajak_laut.php" class="<?= $current === 'bajak_laut.php' ? 'active' : '' ?>">Bajak Laut</a></li>
      <li><a href="marine.php" class="<?= $current === 'marine.php' ? 'active' : '' ?>">Marine</a></li>
      <li><a href="buah_iblis.php" class="<?= $current === 'buah_iblis.php' ? 'active' : '' ?>">Buah Iblis</a></li>
      <li><a href="profil.php" class="<?= $current === 'profil.php' ? 'active' : '' ?>">Profil</a></li>
    </ul>
  </div>
</nav>

<script>
  function adjustPaddingTop() {
    const navbar = document.querySelector('.navbar');
    const containers = document.querySelectorAll('.container, .card-container');
    if (!navbar) return;

    const navHeight = navbar.offsetHeight;
    containers.forEach(container => {
      container.style.paddingTop = navHeight + 20 + 'px';
    });
  }

  window.addEventListener('DOMContentLoaded', adjustPaddingTop);
  window.addEventListener('resize', adjustPaddingTop);

  function toggleSearchBar() {
    const form = document.getElementById('navbar-search-form');
    if (form.style.display === 'none' || form.style.display === '') {
      form.style.display = 'inline-block';
      form.scrollIntoView({ behavior: 'smooth' });
    } else {
      form.style.display = 'none';
    }
  }
</script>
