<?php
session_start();
include 'koneksi.php';

$role = $_SESSION['role'] ?? '';
if (!isset($_SESSION['user_id']) || $role !== 'admin') {
    header("Location: login.html");
    exit();}

$username = $_SESSION['username'] ?? 'Admin';

$today = date("Y-m-d");

$pengunjungHariIni = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM log_pengunjung WHERE DATE(waktu_login) = '$today'")
)['total'];

$pengunjungBulanan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM log_pengunjung WHERE MONTH(waktu_login) = MONTH(CURRENT_DATE()) AND YEAR(waktu_login) = YEAR(CURRENT_DATE())")
)['total'];

$totalKarakter = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM karakter")
)['total'];

$totalBajakLaut = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM karakter WHERE tipe = 'bajak laut'")
)['total'];

$totalMarine = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM karakter WHERE tipe = 'marine'")
)['total'];

$totalBuahIblis = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM karakter WHERE tipe = 'buah iblis'")
)['total'];


function get7HariTerakhir() {
    $labels = [];
    for ($i = 6; $i >= 0; $i--) {
        $labels[] = date('D', strtotime("-$i days")); 
    }
    return $labels;
}

function getDataPengunjung7Hari($conn) {
    $data = [];
    for ($i = 6; $i >= 0; $i--) {
        $tanggal = date('Y-m-d', strtotime("-$i days"));
        $query = "SELECT COUNT(*) as total FROM log_pengunjung WHERE DATE(waktu_login) = '$tanggal'";
        $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $data[] = (int)$result['total'];
    }
    return $data;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="log.png">
    <title>Dashboard Admin - One Piece</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding-top: 60px;
            padding: 30px;
            background: linear-gradient(to bottom right, #9addf8, #95d6f0);
            background-image: url('onepiece.jpg');
            background-size: cover;
            background-attachment: fixed;
            position: relative;
            min-height: 100vh;
            color: #0b1d6b;
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

        .welcome {
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

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 10px;
            margin: 20px 0;
        }

        .box {
            background: rgba(21, 88, 212, 0.66);
            border: 1px solid #4fc3f7;
            border-radius: 12px;
            padding: 20px;
            color: white;
            text-align: center;
            box-shadow: 0 0 15px #4fc3f7;
            font-family: Orbitron, sans-serif;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .box:hover {
            background-color: rgba(39, 7, 184, 0.56);
        }

        h3 {
            text-align: center;
            color: #0b1d6b;
            margin: 60px 0 10px;
            text-transform: uppercase;
            font-size: 30px;
            text-shadow: 1px 1px 3px #fff;
        }

        .kembali {
            text-align: center;
            margin-top: 30px;
        }

        .kembali a button {
            padding: 10px 20px;
            background-color: #2516ac;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .kembali a button:hover {
            background-color: #3c2ac7;
        }

        .grafik-container {
            width: 90%;
            max-width: 800px;
            margin: 80px auto 40px;
            background: rgba(255, 255, 255, 0.85);
            padding: 60px 20px 20px 20px;
            border-radius: 30px;
            box-shadow: 20px 0 20px rgba(0, 123, 255, 0.5);
        }

        .grafik-container h3 {
            margin-top: 10px;
            color: #0b1d6b;
            text-shadow: none;
            text-transform: none;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .welcome {
                top: 40px;
                left: 10px;
                font-size: 16px;
                padding: 8px 14px;
            }

            .box {
                font-size: 18px;
                padding: 16px;
            }

            h3 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar_admin.php'; ?>

    <div id="welcomeMessage" class="welcome">
        Selamat Datang, <span style="color:#0056b3;">
            <?= htmlspecialchars($username) ?>
        </span>!<br>
        Semangat Bekerja!!!
    </div>

    <div class="grafik-container">
        <h3>Grafik Pengunjung 7 Hari Terakhir</h3>
        <canvas id="grafikPengunjung"></canvas>
    </div>

    <div class="dashboard">
        <div class="box" data-type="pengunjung_hari_ini">👥 Pengunjung Hari Ini<br><strong>
                <?= $pengunjungHariIni ?>
            </strong><br><small>Lihat Detail</small></div>
        <div class="box" data-type="pengunjung_bulan_ini">📅 Pengunjung Bulan Ini<br><strong>
                <?= $pengunjungBulanan ?>
            </strong><br><small>Lihat Detail</small></div>
        <div class="box" data-type="total_karakter">🧍‍♂️ Total Karakter<br><strong>
                <?= $totalKarakter ?>
            </strong><br><small>Lihat Detail</small></div>
        <div class="box" data-type="bajak_laut">🏴‍☠️ Total Bajak Laut<br><strong>
                <?= $totalBajakLaut ?>
            </strong><br><small>Lihat Detail</small></div>
        <div class="box" data-type="marine">⚓ Total Marine<br><strong>
                <?= $totalMarine ?>
            </strong><br><small>Lihat Detail</small></div>
        <div class="box" data-type="buah_iblis">🍎 Total Buah Iblis<br><strong>
                <?= $totalBuahIblis ?>
            </strong><br><small>Lihat Detail</small></div>
    </div>

    <h3>Menu Aksi Admin</h3>
    <div class="dashboard">
        <div class="box"><a href="tambahkarakter.php" style="color: white; text-decoration:none;">➕ Tambah Bajak
                Laut</a></div>
        <div class="box"><a href="tambahkarakter.php" style="color: white; text-decoration:none;">➕ Tambah Marine</a>
        </div>
        <div class="box"><a href="tambahkarakter.php" style="color: white; text-decoration:none;">➕ Tambah Buah
                Iblis</a></div>
    </div>

    <script>

        document.getElementById('welcomeMessage').addEventListener('click', function () {
            this.style.display = 'none';
        });

        const labels = <?= json_encode(get7HariTerakhir()) ?>;
        const dataPengunjung = <?= json_encode(getDataPengunjung7Hari($koneksi)) ?>;

        const ctx = document.getElementById('grafikPengunjung').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: dataPengunjung,
                    backgroundColor: 'rgba(21, 88, 212, 0.4)',
                    borderColor: 'rgba(21, 88, 212, 0.8)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgba(21, 88, 212, 1)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1,
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: 'Orbitron, sans-serif',
                                size: 14,
                            }
                        }
                    }
                }
            }
        });

        document.querySelectorAll('.dashboard .box').forEach(box => {
            box.addEventListener('click', () => {
                const type = box.getAttribute('data-type');
                if (!type) return;

                window.location.href = 'detail.php?type=' + encodeURIComponent(type);
            });
        });
    </script>
</body>

</html>