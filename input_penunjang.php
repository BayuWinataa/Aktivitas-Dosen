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
    $jenis_penunjang = $_POST['jenis_penunjang'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_pengajuan = $_POST['tanggal_pengajuan'];
    $jumlah_penunjang = $_POST['jumlah_penunjang'];
    // Pastikan bukti adalah file dan menangani file upload
    $bukti_penunjang = null;
    if (isset($_FILES['bukti_penunjang']) && $_FILES['bukti_penunjang']['error'] === 0) {
        $target_dir = "uploads/"; 
        $bukti_penunjang = $target_dir . basename($_FILES['bukti_penunjang']['name']); 

        if (!move_uploaded_file($_FILES['bukti_penunjang']['tmp_name'], $bukti_penunjang)) {
            $error_message = "Terjadi kesalahan saat mengupload file bukti penunjang.";
        }
    }

    // Jika tidak ada error pada upload file, masukkan data ke database
    if (empty($error_message)) {
        // Ambil dosen_id dari session
        $dosen_id = $_SESSION['id'];

        // Query untuk memasukkan data ke database
        $stmt = $conn->prepare("INSERT INTO penunjang_pengabdian (dosen_id, jenis_penunjang, deskripsi, tanggal_pengajuan, jumlah_penunjang, bukti_penunjang) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssis", $dosen_id, $jenis_penunjang, $deskripsi, $tanggal_pengajuan, $jumlah_penunjang, $bukti_penunjang);

        // Eksekusi query
        if ($stmt->execute()) {
            $success_message = "Penunjang pengabdian berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan penunjang pengabdian.";
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
    <title>Tambah Penunjang Pengabdian Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans antialiased flex">

    <?php include('sidebar.php'); ?>

    <!-- Form Input Data Penunjang Pengabdian -->
    <div class="w-[900px] mx-auto p-8">

        <!-- Pesan Sukses atau Error -->
        <?php if (!empty($error_message)): ?>
        <div class="bg-red-500 text-white p-4 rounded-md mb-6"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
        <div class="bg-green-500 text-white p-4 rounded-md mb-6"><?= $success_message ?></div>
        <?php endif; ?>

        <!-- Form Input Penunjang Pengabdian -->
        <form action="input_penunjang.php" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-blue-600 text-center">Input Penunjang Pengabdian Dosen</h1>

            <div class="mb-4">
                <label for="jenis_penunjang" class="block font-bold text-gray-700">Jenis Penunjang</label>
                <input type="text" id="jenis_penunjang" name="jenis_penunjang"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block font-bold text-gray-700">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required></textarea>
            </div>

            <div class="mb-4">
                <label for="tanggal_pengajuan" class="block font-bold text-gray-700">Tanggal Pengajuan</label>
                <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="jumlah_penunjang" class="block font-bold text-gray-700">Jumlah Penunjang</label>
                <input type="number" id="jumlah_penunjang" name="jumlah_penunjang"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="bukti_penunjang" class="block font-bold text-gray-700">Bukti Penunjang (Opsional)</label>
                <input type="file" id="bukti_penunjang" name="bukti_penunjang"
                    class="mt-2 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div class="flex flex-col items-center gap-5">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 w-1/2 rounded-md text-center font-bold">Submit
                    Penunjang</button>
            </div>
        </form>
    </div>
</body>

</html>