<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id']; // ID dosen yang sedang login
$pengabdian_id = $_GET['id']; // ID pengabdian yang ingin diedit

// Query untuk mengambil data pengabdian berdasarkan ID
$stmt = $conn->prepare("SELECT judul_kegiatan, tanggal_mulai, tanggal_selesai, lokasi, deskripsi_kegiatan, manfaat, tim_pelaksana, dokumentasi 
                        FROM pengabdian_dosen WHERE id = ? AND dosen_id = ?");
$stmt->bind_param("ii", $pengabdian_id, $dosen_id);
$stmt->execute();
$stmt->bind_result($judul_kegiatan, $tanggal_mulai, $tanggal_selesai, $lokasi, $deskripsi_kegiatan, $manfaat, $tim_pelaksana, $dokumentasi);

// Pastikan data ditemukan
if ($stmt->fetch()) {
    $stmt->close();

    // Proses jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil data dari form
        $judul_kegiatan = $_POST['judul_kegiatan'];
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $lokasi = $_POST['lokasi'];
        $deskripsi_kegiatan = $_POST['deskripsi_kegiatan'];
        $manfaat = $_POST['manfaat'];
        $tim_pelaksana = $_POST['tim_pelaksana'];

        // Periksa apakah ada dokumentasi baru yang diunggah
        if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === UPLOAD_ERR_OK) {
            $dokumentasi_tmp = $_FILES['dokumentasi']['tmp_name'];
            $dokumentasi_name = basename($_FILES['dokumentasi']['name']);
            $dokumentasi_path = 'uploads/' . $dokumentasi_name;
            move_uploaded_file($dokumentasi_tmp, $dokumentasi_path);
        } else {
            $dokumentasi_path = $dokumentasi; // Gunakan dokumentasi lama jika tidak diunggah
        }

        // Query untuk memperbarui data pengabdian
        $update_stmt = $conn->prepare("UPDATE pengabdian_dosen SET judul_kegiatan = ?, tanggal_mulai = ?, tanggal_selesai = ?, lokasi = ?, deskripsi_kegiatan = ?, manfaat = ?, tim_pelaksana = ?, dokumentasi = ? WHERE id = ? AND dosen_id = ?");
        $update_stmt->bind_param("ssssssssii", $judul_kegiatan, $tanggal_mulai, $tanggal_selesai, $lokasi, $deskripsi_kegiatan, $manfaat, $tim_pelaksana, $dokumentasi_path, $pengabdian_id, $dosen_id);

        if ($update_stmt->execute()) {
            // Jika update berhasil, tampilkan alert dan redirect
            echo "<script>
                    alert('Pengabdian berhasil diperbarui.');
                    window.location.href = 'lihat_pengabdian.php';
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
    <title>Edit Pengabdian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12 px-6">
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-8">
            <h1 class="text-3xl font-bold text-blue-600 text-center">Edit Pengabdian</h1>

            <!-- Form untuk mengedit pengabdian -->
            <form method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- Judul Kegiatan -->
                <div class="flex flex-col">
                    <label for="judul_kegiatan" class="text-lg font-medium text-gray-700">Judul Kegiatan
                        Pengabdian:</label>
                    <input type="text" name="judul_kegiatan" value="<?php echo htmlspecialchars($judul_kegiatan); ?>"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Deskripsi Kegiatan -->
                <div class="flex flex-col">
                    <label for="deskripsi_kegiatan" class="text-lg font-medium text-gray-700">Deskripsi
                        Kegiatan:</label>
                    <textarea name="deskripsi_kegiatan" rows="6"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required><?php echo htmlspecialchars($deskripsi_kegiatan); ?></textarea>
                </div>

                <!-- Lokasi -->
                <div class="flex flex-col">
                    <label for="lokasi" class="text-lg font-medium text-gray-700">Lokasi Kegiatan:</label>
                    <input type="text" name="lokasi" value="<?php echo htmlspecialchars($lokasi); ?>"
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

                <!-- Manfaat -->
                <div class="flex flex-col">
                    <label for="manfaat" class="text-lg font-medium text-gray-700">Manfaat Kegiatan:</label>
                    <textarea name="manfaat" rows="4"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($manfaat); ?></textarea>
                </div>

                <!-- Tim Pelaksana -->
                <div class="flex flex-col">
                    <label for="tim_pelaksana" class="text-lg font-medium text-gray-700">Tim Pelaksana:</label>
                    <textarea name="tim_pelaksana" rows="4"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($tim_pelaksana); ?></textarea>
                </div>

                <!-- Dokumentasi -->
                <div class="flex flex-col">
                    <label for="dokumentasi" class="text-lg font-medium text-gray-700">Dokumentasi:</label>
                    <input type="file" name="dokumentasi"
                        class="mt-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php if ($dokumentasi) : ?>
                    <p class="mt-2 text-gray-500">Dokumentasi Saat Ini: <a
                            href="<?php echo htmlspecialchars($dokumentasi); ?>"
                            class="text-blue-500 hover:underline">Lihat Dokumentasi</a></p>
                    <?php endif; ?>
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Perbarui Pengabdian
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>
<?php
} else {
    echo "<p class='text-red-500 font-semibold'>Pengabdian tidak ditemukan atau Anda tidak memiliki akses.</p>";
}

$conn->close();
?>