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

$stmt = $koneksi->prepare("SELECT * FROM karakter WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" type="image/png" href="log.png">
    <title>Detail Karakter</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: url('kapal.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 40px;
            color: #fff;
            animation: fadeIn 1s ease;
        }

        h2 {
            text-align: center;
            color: #66ccff;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgb(17, 8, 146);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(0, 0, 50, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 25px rgba(0, 123, 255, 0.5);
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            color: #cceeff;
        }

        th {
            background-color: rgba(0, 123, 255, 0.8);
            color: #fff;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: rgba(22, 172, 209, 0.4);
        }

        .tombol-aksi {
            margin-top: 30px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 25px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            margin: 0 10px;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.5);
            border: 2px solid #0d74c1;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            text-align: center;
            user-select: none;
        }

        .btn:hover {
            background-color: #0d74c1;
            box-shadow: 0 6px 12px rgba(13, 116, 193, 0.7);
            transform: scale(1.05);
        }

        p {
            text-align: center;
            font-size: 1.1rem;
            color: #ddd;
            background: rgba(81, 187, 214, 0.6);
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(60, 114, 172, 0.4);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <h2>Detail Karakter</h2>
    <table>
        <tr>
            <td>ID</td>
            <td>
                <?= htmlspecialchars($data['id']) ?>
            </td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>
                <?= htmlspecialchars($data['nama_karakter']) ?>
            </td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td>
                <?= htmlspecialchars($data['tipe']) ?>
            </td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>
                <?= nl2br(htmlspecialchars($data['keterangan'])) ?>
            </td>
        </tr>
        <tr>
            <td>Foto</td>
            <td>
                <img src="tampil_foto.php?id=<?= $data['id'] ?>" alt="Foto Karakter"
                    style="max-width:100%; height:auto;">
            </td>
        </tr>
    </table>

    <div class="tombol-aksi">
        <a class="btn" href="edit_karakter.php?id=<?= $data['id'] ?>">✏️ Edit Karakter</a> |
        <a class="btn" href="index_admin.php">⬅ Kembali</a>
    </div>

</body>

</html>