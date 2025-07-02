<?php
$role = $_SESSION['role'] ?? 'admin';
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
    padding: 0 30px;
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
  @keyframes rgbGlow {
    0% { box-shadow: 0 0 8px #0dcaf0, 0 0 16px #00aaff; }
    50% { box-shadow: 0 0 15px #0dcaf0, 0 0 30px #00aaff; }
    100% { box-shadow: 0 0 8px #0dcaf0, 0 0 16px #00aaff; }
  }

  @keyframes zoomGlow {
    0% { transform: scale(1); text-shadow: 0 0 5px #0dcaf0, 0 0 10px #00aaff; }
    50% { transform: scale(1.1); text-shadow: 0 0 15px #0dcaf0, 0 0 25px #00aaff; }
    100% { transform: scale(1); text-shadow: 0 0 5px #0dcaf0, 0 0 10px #00aaff; }
  }

  @keyframes rotateOnce {
    0% { transform: rotate(0deg); }
    50% { transform: rotate(10deg); }
    100% { transform: rotate(0deg); }
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
    animation: rgbGlow 3s infinite ease-in-out;
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
    animation: zoomGlow 3s infinite ease-in-out;
    transition: transform 0.5s;
    cursor: pointer;
  }

  .navbar-logo.clicked {
    animation: rotateOnce 0.6s ease-in-out;
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
    padding: 10px;
    position: relative;
    transition: color 0.3s ease;
  }

  .navbar-menu li a::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 5px;
    width: 0%;
    height: 3px;
    background: #0dcaf0;
    box-shadow: 0 0 8px #0dcaf0;
    transition: width 0.4s ease-in-out;
    border-radius: 10px;
  }

  .navbar-menu li a:hover::after,
  .navbar-menu li a.active::after {
    width: 100%;
  }

  .navbar-menu li a:hover,
  .navbar-menu li a.active {
    color: #0dcaf0;
    text-shadow: 0 0 5px #0dcaf0, 0 0 10px #00aaff;
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
    <div class="navbar-logo" id="logo">Pencarian Karakter One Piece</div>

    <ul class="navbar-menu">
      <li><a href="index_admin.php" class="<?= $current === 'index_admin.php' ? 'active' : '' ?>">Beranda</a></li>
      <li><a href="bajaklaut_admin.php" class="<?= $current === 'bajaklaut_admin.php' ? 'active' : '' ?>">Bajak Laut</a></li>
      <li><a href="marine_admin.php" class="<?= $current === 'marine_admin.php' ? 'active' : '' ?>">Marine</a></li>
      <li><a href="buahiblis_admin.php" class="<?= $current === 'buahiblis_admin.php' ? 'active' : '' ?>">Buah Iblis</a></li>
      <li><a href="profil_admin.php" class="<?= $current === 'profil_admin.php' ? 'active' : '' ?>">Profil</a></li>
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

  // Efek animasi klik logo
  const logo = document.getElementById('logo');
  logo.addEventListener('click', () => {
    logo.classList.add('clicked');
    setTimeout(() => {
      logo.classList.remove('clicked');
    }, 600);
  });
</script>
