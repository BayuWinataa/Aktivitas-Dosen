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
    $jenis_aktivitas = $_POST['jenis_aktivitas'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $durasi = $_POST['durasi'];
    $status_aktivitas = $_POST['status_aktivitas'];

    // Pastikan bukti adalah file dan menangani file upload
    $bukti = null;
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0) {
        $target_dir = "uploads/";
        $bukti = $target_dir . basename($_FILES['bukti']['name']);

        if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $bukti)) {
            $error_message = "Terjadi kesalahan saat mengupload file bukti.";
        }
    }

    // Jika tidak ada error pada upload file, masukkan data ke database
    if (empty($error_message)) {
        $stmt = $conn->prepare("INSERT INTO aktivitas_dosen (dosen_id, jenis_aktivitas, deskripsi, tanggal, durasi, status_aktivitas, bukti) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $_SESSION['id'], $jenis_aktivitas, $deskripsi, $tanggal, $durasi, $status_aktivitas, $bukti);

        if ($stmt->execute()) {
            $success_message = "Aktivitas berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan aktivitas.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Aktivitas Dosen</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    <?php include('sidebar.php'); ?>

    <!-- Alert Notifikasi -->
    <?php if (!empty($success_message)): ?>
    <script>
    alert("<?php echo $success_message; ?>");
    window.location.href = "dosen_dashboard.php"; // Redirect setelah input berhasil
    </script>
    <?php elseif (!empty($error_message)): ?>
    <script>
    alert("<?php echo $error_message; ?>");
    </script>
    <?php endif; ?>

    <!-- Form Input -->
    <div class="w-[900px] mx-auto my-12 p-6 bg-white shadow-xl rounded-lg">
        <h1 class="text-3xl font-bold text-center text-indigo-600 mb-8">Input Aktivitas Dosen Baru</h1>

        <form method="POST" action="input_aktivitas.php" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="jenis_aktivitas" class="block text-lg font-medium text-gray-700">Jenis Aktivitas:</label>
                <input type="text" id="jenis_aktivitas" name="jenis_aktivitas" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-lg font-medium text-gray-700">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="tanggal" class="block text-lg font-medium text-gray-700">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="durasi" class="block text-lg font-medium text-gray-700">Durasi :</label>
                    <input type="text" id="durasi" name="durasi" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="mb-4">
                <label for="status_aktivitas" class="block text-lg font-medium text-gray-700">Status Aktivitas:</label>
                <input type="text" id="status_aktivitas" name="status_aktivitas" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="bukti" class="block text-lg font-medium text-gray-700">Bukti (Opsional):</label>
                <input type="file" id="bukti" name="bukti"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex justify-center my-3">
                <button type="submit"
                    class="w-1/2 py-3 px-6 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Tambah
                    Aktivitas</button>
            </div>
        </form>
    </div>

</body>

</html>