<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id']; // ID dosen yang sedang login
$penelitian_id = $_GET['id']; // ID penelitian yang ingin diedit

// Query untuk mengambil data penelitian berdasarkan ID
$stmt = $conn->prepare("SELECT judul, deskripsi, kategori, tanggal_mulai, tanggal_selesai, status, pendanaan, publikasi, kata_kunci, lampiran, hasil_penelitian 
                        FROM penelitian WHERE dosen_id = ? AND id = ?");
$stmt->bind_param("ii", $dosen_id, $penelitian_id);
$stmt->execute();
$stmt->bind_result($judul, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $status, $pendanaan, $publikasi, $kata_kunci, $lampiran, $hasil_penelitian);

// Pastikan data ditemukan
if ($stmt->fetch()) {
    $stmt->close();

    // Proses jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil data dari form
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $kategori = $_POST['kategori'];
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $status = $_POST['status'];
        $pendanaan = $_POST['pendanaan'];
        $publikasi = $_POST['publikasi'];
        $kata_kunci = $_POST['kata_kunci'];
        $hasil_penelitian = $_POST['hasil_penelitian'];

        // Periksa apakah ada lampiran baru yang diunggah
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK) {
            $lampiran_tmp = $_FILES['lampiran']['tmp_name'];
            $lampiran_name = basename($_FILES['lampiran']['name']);
            $lampiran_path = 'uploads/' . $lampiran_name;
            move_uploaded_file($lampiran_tmp, $lampiran_path);
        } else {
            $lampiran_path = $lampiran; // Gunakan lampiran lama jika tidak diunggah
        }

        // Query untuk memperbarui data penelitian
        $update_stmt = $conn->prepare("UPDATE penelitian SET judul = ?, deskripsi = ?, kategori = ?, tanggal_mulai = ?, tanggal_selesai = ?, status = ?, pendanaan = ?, publikasi = ?, kata_kunci = ?, lampiran = ?, hasil_penelitian = ? WHERE dosen_id = ? AND id = ?");
        $update_stmt->bind_param("ssssssissssii", $judul, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $status, $pendanaan, $publikasi, $kata_kunci, $lampiran_path, $hasil_penelitian, $dosen_id, $penelitian_id);

        if ($update_stmt->execute()) {
            // Jika update berhasil, tampilkan alert dan redirect
            echo "<script>
                    alert('Penelitian berhasil diperbarui.');
                    window.location.href = 'lihat_penelitian.php';
                  </script>";
        } else {
            echo "<p class='text-red-500 font-semibold'>Error: " . $update_stmt->error . "</p>";
        }
        $update_stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penelitian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12 px-6">
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-8">
            <h1 class="text-3xl font-bold text-blue-600 text-center">Edit Penelitian</h1>

            <!-- Form untuk mengedit penelitian -->
            <form method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- Judul Penelitian -->
                <div class="flex flex-col">
                    <label for="judul" class="text-lg font-medium text-gray-700">Judul Penelitian:</label>
                    <input type="text" name="judul" value="<?php echo htmlspecialchars($judul); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Deskripsi Penelitian -->
                <div class="flex flex-col">
                    <label for="deskripsi" class="text-lg font-medium text-gray-700">Deskripsi Penelitian:</label>
                    <textarea name="deskripsi" rows="6"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required><?php echo htmlspecialchars($deskripsi); ?></textarea>
                </div>

                <!-- Kategori -->
                <div class="flex flex-col">
                    <label for="kategori" class="text-lg font-medium text-gray-700">Kategori:</label>
                    <input type="text" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Tanggal Mulai dan Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label for="tanggal_mulai" class="text-lg font-medium text-gray-700">Tanggal Mulai:</label>
                        <input type="date" name="tanggal_mulai" value="<?php echo htmlspecialchars($tanggal_mulai); ?>"
                            class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div class="flex flex-col">
                        <label for="tanggal_selesai" class="text-lg font-medium text-gray-700">Tanggal Selesai:</label>
                        <input type="date" name="tanggal_selesai"
                            value="<?php echo htmlspecialchars($tanggal_selesai); ?>"
                            class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                </div>

                <!-- Status -->
                <!-- <div class="flex flex-col">
                    <label for="status" class="text-lg font-medium text-gray-700">Status:</label>
                    <input type="text" name="status" value="<?php echo htmlspecialchars($status); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div> -->

                <!-- Pendanaan -->
                <div class="flex flex-col">
                    <label for="pendanaan" class="text-lg font-medium text-gray-700">Pendanaan (Rp) :</label>
                    <input type="text" name="pendanaan" value="<?php echo htmlspecialchars($pendanaan); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Publikasi -->
                <div class="flex flex-col">
                    <label for="publikasi" class="text-lg font-medium text-gray-700">Publikasi (URL):</label>
                    <input type="text" name="publikasi" value="<?php echo htmlspecialchars($publikasi); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Kata Kunci -->
                <div class="flex flex-col">
                    <label for="kata_kunci" class="text-lg font-medium text-gray-700">Kata Kunci:</label>
                    <input type="text" name="kata_kunci" value="<?php echo htmlspecialchars($kata_kunci); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Lampiran -->
                <div class="flex flex-col">
                    <label for="lampiran" class="text-lg font-medium text-gray-700">Lampiran:</label>
                    <input type="file" name="lampiran"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php if ($lampiran) : ?>
                    <p class="mt-2 text-gray-500">Lampiran Saat Ini: <a
                            href="<?php echo htmlspecialchars($lampiran); ?>"
                            class="text-blue-500 hover:underline">Lihat Lampiran</a></p>
                    <?php endif; ?>
                </div>

                <!-- Hasil Penelitian -->
                <div class="flex flex-col">
                    <label for="hasil_penelitian" class="text-lg font-medium text-gray-700">Hasil Penelitian:</label>
                    <textarea name="hasil_penelitian" rows="4"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($hasil_penelitian); ?></textarea>
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Perbarui Penelitian
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>
<?php
} else {
    echo "<p class='text-red-500 font-semibold'>Penelitian tidak ditemukan atau Anda tidak memiliki akses.</p>";
}

$conn->close();
?>