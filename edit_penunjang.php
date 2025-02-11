<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

// Mengambil ID penunjang dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $penunjang_id = $_GET['id'];
} else {
    header("Location: daftar_penunjang.php");
    exit;
}

// Query untuk mengambil data penunjang berdasarkan ID
$stmt = $conn->prepare("SELECT jenis_penunjang, deskripsi, tanggal_pengajuan, jumlah_penunjang, bukti_penunjang FROM penunjang_pengabdian WHERE id = ?");
$stmt->bind_param("i", $penunjang_id);
$stmt->execute();
$stmt->bind_result($jenis_penunjang, $deskripsi, $tanggal_pengajuan, $jumlah_penunjang, $bukti_penunjang);
$stmt->fetch();
$stmt->close();

// Variabel untuk pesan notifikasi
$success_message = '';
$error_message = '';

// Proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $jenis_penunjang = $_POST['jenis_penunjang'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_pengajuan = $_POST['tanggal_pengajuan'];
    $jumlah_penunjang = $_POST['jumlah_penunjang'];

    // Proses upload file jika ada
    $bukti_penunjang = $bukti_penunjang; // Tetap menggunakan bukti lama jika tidak ada file baru
    if (isset($_FILES['bukti_penunjang']) && $_FILES['bukti_penunjang']['error'] === 0) {
        $target_dir = "uploads/";
        $bukti_penunjang = $target_dir . basename($_FILES['bukti_penunjang']['name']);
        // Upload file
        if (!move_uploaded_file($_FILES['bukti_penunjang']['tmp_name'], $bukti_penunjang)) {
            $error_message = "Terjadi kesalahan saat mengupload file bukti penunjang.";
        }
    }

    // Jika tidak ada error, update data di database
    if (empty($error_message)) {
        $stmt = $conn->prepare("UPDATE penunjang_pengabdian SET jenis_penunjang = ?, deskripsi = ?, tanggal_pengajuan = ?, jumlah_penunjang = ?, bukti_penunjang = ? WHERE id = ?");
        $stmt->bind_param("sssiss", $jenis_penunjang, $deskripsi, $tanggal_pengajuan, $jumlah_penunjang, $bukti_penunjang, $penunjang_id);

        if ($stmt->execute()) {
            $success_message =
            "<script>
                    alert('Pengabdian berhasil diperbarui.');
                    window.location.href = 'lihat_penunjang.php';
                  </script>";
        } else {
            $error_message = "Gagal memperbarui penunjang pengabdian.";
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
    <title>Edit Penunjang Pengabdian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-semibold text-blue-600 mb-6">Edit Penunjang Pengabdian</h1>

            <!-- Pesan Sukses atau Error -->
            <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-4 rounded-md mb-6"><?= $success_message ?></div>
            <?php elseif ($error_message): ?>
            <div class="bg-red-500 text-white p-4 rounded-md mb-6"><?= $error_message ?></div>
            <?php endif; ?>

            <!-- Form Edit Penunjang -->
            <form action="edit_penunjang.php?id=<?php echo $penunjang_id; ?>" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                <div class="mb-4">
                    <label for="jenis_penunjang" class="block font-bold text-gray-700">Jenis Penunjang</label>
                    <input type="text" id="jenis_penunjang" name="jenis_penunjang"
                        class="mt-2 p-2 w-full border border-gray-300 rounded-md"
                        value="<?php echo htmlspecialchars($jenis_penunjang); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block font-bold text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                        class="mt-2 p-2 w-full border border-gray-300 rounded-md"
                        required><?php echo htmlspecialchars($deskripsi); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="tanggal_pengajuan" class="block font-bold text-gray-700">Tanggal Pengajuan</label>
                    <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan"
                        class="mt-2 p-2 w-full border border-gray-300 rounded-md"
                        value="<?php echo htmlspecialchars($tanggal_pengajuan); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="jumlah_penunjang" class="block font-bold text-gray-700">Jumlah Penunjang</label>
                    <input type="number" id="jumlah_penunjang" name="jumlah_penunjang"
                        class="mt-2 p-2 w-full border border-gray-300 rounded-md"
                        value="<?php echo htmlspecialchars($jumlah_penunjang); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="bukti_penunjang" class="block font-bold text-gray-700">Bukti Penunjang
                        (Opsional)</label>
                    <input type="file" id="bukti_penunjang" name="bukti_penunjang"
                        class="mt-2 p-2 w-full border border-gray-300 rounded-md">
                    <?php if ($bukti_penunjang): ?>
                    <p class="mt-2 text-blue-500">
                        <a href="<?php echo htmlspecialchars($bukti_penunjang); ?>" target="_blank">Lihat Bukti
                            Sebelumnya</a>
                    </p>
                    <?php endif; ?>
                </div>

                <div class="flex justify-center ">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-semibold w-1/2">Perbarui
                        Penunjang</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>