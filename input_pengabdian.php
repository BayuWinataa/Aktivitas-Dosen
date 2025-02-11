<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

// Variabel untuk pesan notifikasi
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $judul_kegiatan = $_POST['judul_kegiatan'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $lokasi = $_POST['lokasi'];
    $deskripsi_kegiatan = $_POST['deskripsi_kegiatan'];
    $manfaat = $_POST['manfaat'];
    $tim_pelaksana = $_POST['tim_pelaksana'];

    // Pastikan bukti adalah file dan menangani file upload
    $dokumentasi = null;
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === 0) {
        $target_dir = "uploads/";
        $dokumentasi = $target_dir . basename($_FILES['dokumentasi']['name']);

        if (!move_uploaded_file($_FILES['dokumentasi']['tmp_name'], $dokumentasi)) {
            $error_message = "Terjadi kesalahan saat mengupload file dokumentasi.";
        }
    }

    // Jika tidak ada error pada upload file, masukkan data ke database
    if (empty($error_message)) {
        // Pastikan nilai dosen_id diambil dari sesi
        $dosen_id = $_SESSION['id'];

        // Query untuk memasukkan data
        $stmt = $conn->prepare("INSERT INTO pengabdian_dosen (dosen_id, judul_kegiatan, tanggal_mulai, tanggal_selesai, lokasi, deskripsi_kegiatan, manfaat, tim_pelaksana, dokumentasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("issssssss", $dosen_id, $judul_kegiatan, $tanggal_mulai, $tanggal_selesai, $lokasi, $deskripsi_kegiatan, $manfaat, $tim_pelaksana, $dokumentasi);

        if ($stmt->execute()) {
            $success_message = "Kegiatan pengabdian berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan kegiatan pengabdian.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengabdian Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-gray-100 font-sans antialiased flex">

    <?php include('sidebar.php'); ?>
    <div class="w-[900px] mx-auto p-8">

        <!-- Pesan Sukses atau Error -->
        <!-- <?php if (isset($success_message)): ?>
        <div class="bg-green-500 text-white p-4 rounded-md mb-6"><?= $success_message ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
        <div class="bg-red-500 text-white p-4 rounded-md mb-6"><?= $error_message ?></div>
        <?php endif; ?> -->

        <!-- Form Input Data Pengabdian -->
        <form action="input_pengabdian.php" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-blue-600 text-center">Input Kegiatan Pengabdian Dosen</h1>

            <div class="mb-4">
                <label for="judul_kegiatan" class="block font-bold text-gray-700">Judul Kegiatan Pengabdian</label>
                <input type="text" id="judul_kegiatan" name="judul_kegiatan"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="tanggal_mulai" class="block font-bold text-gray-700">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="tanggal_selesai" class="block font-bold text-gray-700">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="lokasi" class="block font-bold text-gray-700">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" class="mt-2 p-2 w-full border border-gray-300 rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="deskripsi_kegiatan" class="block font-bold text-gray-700">Deskripsi Kegiatan</label>
                <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required></textarea>
            </div>

            <div class="mb-4">
                <label for="manfaat" class="block font-bold text-gray-700">Manfaat</label>
                <textarea id="manfaat" name="manfaat" rows="3"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md"></textarea>
            </div>

            <div class="mb-4">
                <label for="tim_pelaksana" class="block font-bold text-gray-700">Tim Pelaksana</label>
                <input type="text" id="tim_pelaksana" name="tim_pelaksana"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="dokumentasi" class="block font-bold text-gray-700">Dokumentasi (Foto / Video)</label>
                <input type="file" id="dokumentasi" name="dokumentasi"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div class="flex flex-col items-center gap-5">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 w-1/2 rounded-md  text-center font-bold">Submit
                    Pengabdian</button>
            </div>
        </form>
    </div>
</body>

</html>