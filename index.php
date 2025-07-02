<?php
session_start();

// 1. Tampilkan splash hanya sekali
if (!isset($_SESSION['sudah_lihat_splash'])) {
    $_SESSION['sudah_lihat_splash'] = true;
    header("Location: splash.php");
    exit();
}

// 2. Setelah splash, wajib login
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'pengunjung') {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'] ?? null;
?>


<!DOCTYPE html>
<html>

<head>
    <title>Pencarian Karakter One Piece</title>
    <link rel="icon" type="image/png" href="log.png">
    <style>
        * {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding-top: 30px;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, rgb(154, 221, 248), rgb(149, 214, 240));
            background-image: url('onepiece.jpg');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 30px;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: -1;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        h3 {
            text-align: center;
            color: rgb(11, 29, 107);
            margin-top: 60px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 30px;
            text-shadow: 1px 1px 3px #fff;
        }

        table {
            width: 90%;
            margin: 60px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            animation: fadeIn 0.4s ease;
        }

        th,
        td {
            padding: 18px 25px;
            text-align: center;
        }

        td {
            text-align: left;
        }

        th {
            background-color: rgb(99, 96, 243);
            color: white;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: rgb(131, 235, 229);
        }

        tr:hover {
            background-color: rgb(53, 148, 177);
        }

        .kembali {
            text-align: center;
            margin-top: 30px;
        }

        .kembali a button {
            padding: 10px 20px;
            background-color: rgb(37, 22, 172);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .kembali a button:hover {
            background-color: rgb(60, 42, 199);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .keluar-kiri {
            position: absolute;
            top: 20px;
            left: 30px;
            z-index: 10;
        }

        .keluar-kiri button {
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .keluar-kanan button:hover {
            background-color: #0056b3;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            width: 90%;
            margin: 40px auto;
        }

        .card {
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.7);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(16, 122, 221, 0.9);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
            display: block;
        }

        .card-body {
            padding: 16px;
            text-align: center;
        }

        .card-title {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            margin-bottom: 6px;
        }

        .card-desc {
            margin-top: 8px;
            font-size: 15px;
            color: #555;
        }

        .detail-view {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.5s ease forwards;
        }

        .detail-content {
            background: white;
            padding: 30px;
            border-radius: 14px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            animation: zoomIn 0.4s ease;
        }

        .detail-content img {
            width: 80%;
            max-width: 300px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .detail-content h2 {
            margin-top: 20px;
            font-size: 26px;
        }

        .detail-content p {
            margin-top: 10px;
            font-size: 17px;
            color: #444;
        }

        .close-btn {
            margin-top: 25px;
            padding: 10px 25px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        .close-btn:hover {
            background-color: #0056b3;
        }


        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #005eff;
            color: white;
            text-align: center;
            padding: 3px 0;
            font-size: 8px;
            z-index: 10000;
            text-shadow: 0 0 6px rgba(255, 255, 255, 0.5);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
            font-weight: 500;
            animation: fadeInUp 2s ease;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }

            .search-form {
                width: 90% !important;
            }

            .search-form:hover,
            .search-form:focus-within {
                width: 90% !important;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }

            .detail-content {
                padding: 20px;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #welcome-msg {
            position: fixed;
            top: 60px;
            left: 20px;
            padding: 10px 20px;
            font-size: 18px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: #0b1d6b;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            background-size: 600% 600%;
            box-shadow: 0 0 10px rgba(21, 88, 212, 0.7);
            opacity: 0;
            text-shadow: 1px 1px 3px #fff;
            transform: translateY(-50px);
            animation-fill-mode: forwards;
            animation-name: slideDownFadeIn;
            animation-duration: 1s;
            animation-timing-function: ease-out;
            z-index: 9999;
        }

        @keyframes slideDownFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>


<body>
    <?php include 'navbar.php'; ?>

    <?php if ($username): ?>
        <div id="welcome-msg">Selamat Datang, <?= htmlspecialchars($username) ?>!</div>
    <?php endif; ?>

    <div id="main-content">
        <?php if (!isset($_GET['kata_cari']) || $_GET['kata_cari'] == ''): ?>
            <div class="card-grid">
                <?php
                include 'koneksi.php';
                $result = mysqli_query($koneksi, "SELECT * FROM karakter");
                while ($row = mysqli_fetch_assoc($result)) {
                    $fotoUrl = "tampil_foto.php?id=" . $row['id'];
                    $nama = addslashes($row['nama_karakter']);
                    $keterangan = addslashes($row['keterangan']);
                    echo '<div class="card" onclick="showDetail(\'' . $fotoUrl . '\', \'' . $nama . '\', \'' . $keterangan . '\')">';
                    echo '<img src="' . $fotoUrl . '" alt="' . $nama . '">';
                    echo '<div class="card-body">';
                    echo '<div class="card-title">' . htmlspecialchars($row['nama_karakter']) . '</div>';
                    echo '</div></div>';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['kata_cari']) && $_GET['kata_cari'] != ''): ?>
            <table>
                <thead>
                    <tr>
                        <th>Kode Karakter</th>
                        <th>Nama Karakter</th>
                        <th>Keterangan</th>
                        <th>Bounty</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $kata_cari = $_GET['kata_cari'];
                    $query = "SELECT * FROM karakter WHERE kode_karakter LIKE '%$kata_cari%' OR nama_karakter LIKE '%$kata_cari%' OR keterangan LIKE '%$kata_cari%' ORDER BY id ASC";
                    $result = mysqli_query($koneksi, $query);
                    if (!$result || mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='5' style='text-align: center; color: red; font-weight: bold;'>Karakter tidak ditemukan</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $fotoUrl = "tampil_foto.php?id={$row['id']}";
                            $nama = htmlspecialchars($row['nama_karakter'], ENT_QUOTES);
                            echo "<tr onclick=\"showDetail('$fotoUrl', '{$row['nama_karakter']}', '{$row['keterangan']}', '{$row['bounty']}')\">";
                            echo "<td>{$row['kode_karakter']}</td>";
                            echo "<td>{$row['nama_karakter']}</td>";
                            echo "<td>{$row['keterangan']}</td>";
                            echo "<td>{$row['bounty']}</td>";
                            echo "<td><img src='$fotoUrl' alt='$nama' style='max-width: 100px;'></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="kembali">
                <a href="index.php?menu=cari"><button>Kembali</button></a>
            </div>
        <?php endif; ?>
    </div>

    <div class="detail-view" id="detailView" style="display: none;">
        <div class="detail-content">
            <img id="detailImage" src="" alt="">
            <h2 id="detailName"></h2>
            <p id="detailDesc"></p>
            <button class="close-btn" onclick="closeDetail()">Tutup</button>
        </div>
    </div>

    <?php if (isset($_GET['kata_cari'])): ?>
    <div class="keluar-kiri">
        <button onclick="window.location.href='index.php'">Keluar</button>
    </div>
    <?php endif; ?>

    <div class="footer">Present by Ima Miyati☠️</div>

    <script>
        function showDetail(fotoUrl, nama, keterangan, bounty) {
            document.getElementById('detailImage').src = fotoUrl;
            document.getElementById('detailName').textContent = nama;
            document.getElementById('detailDesc').textContent = keterangan;
            document.getElementById('detailView').style.display = 'flex';
        }

        function closeDetail() {
            document.getElementById('detailView').style.display = 'none';
        }

        document.getElementById('welcome-msg').addEventListener('click', function () {
            this.style.display = 'none';
        });
    </script>
</body>

</html>
