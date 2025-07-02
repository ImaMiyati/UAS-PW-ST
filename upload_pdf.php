<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $tipe = strtolower(trim($_POST['tipe'] ?? 'bajak laut')); // bersihkan & standarisasi

    // Cek validitas tipe dan siapkan redirect
    $halaman_redirect = [
        'bajak laut' => 'bajaklaut_admin.php',
        'marine' => 'marine_admin.php',
        'buah iblis' => 'buahiblis_admin.php'
    ];
    $redirect = $halaman_redirect[$tipe] ?? 'bajaklaut_admin.php';

    // Validasi dan proses upload PDF
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $folder = 'pdf/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $nama_asli = $_FILES['pdf']['name'];
        $ext = pathinfo($nama_asli, PATHINFO_EXTENSION);
        $nama_file_baru = 'karakter_' . $id . '.' . $ext;
        $lokasi_simpan = $folder . $nama_file_baru;

        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $lokasi_simpan)) {
            // Hapus data lama
            mysqli_query($koneksi, "DELETE FROM file_pdf WHERE karakter_id = $id");

            // Simpan nama file baru
            $stmt = mysqli_prepare($koneksi, "INSERT INTO file_pdf (karakter_id, nama_file) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "is", $id, $nama_file_baru);
            mysqli_stmt_execute($stmt);

            echo "<script>alert('PDF berhasil diunggah!'); window.location.href='$redirect';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menyimpan file.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('File tidak valid.'); window.history.back();</script>";
    }
}
?>
