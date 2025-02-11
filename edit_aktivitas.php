<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$aktivitas_id = $_GET['id'] ?? null;
$jenis_aktivitas = $deskripsi = $tanggal = $durasi = $status_aktivitas = $bukti = '';

// Mengambil data aktivitas yang ingin diedit
if ($aktivitas_id) {
    $stmt = $conn->prepare("SELECT jenis_aktivitas, deskripsi, tanggal, durasi, status_aktivitas, bukti FROM aktivitas_dosen WHERE id = ? AND dosen_id = ?");
    $stmt->bind_param("ii", $aktivitas_id, $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($jenis_aktivitas, $deskripsi, $tanggal, $durasi, $status_aktivitas, $bukti);
    $stmt->fetch();
    $stmt->close();
}

// Update aktivitas jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_aktivitas = $_POST['jenis_aktivitas'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $durasi = $_POST['durasi'];  // Durasi sebagai teks
    $status_aktivitas = $_POST['status_aktivitas'];  // Status sebagai teks

    // Mengupdate bukti jika ada file baru
    if ($_FILES['bukti']['error'] == 0) {
        $target_dir = "uploads/"; // Folder tujuan untuk upload file
        $bukti = $target_dir . basename($_FILES["bukti"]["name"]);
        if (!move_uploaded_file($_FILES["bukti"]["tmp_name"], $bukti)) {
            echo "Terjadi kesalahan saat mengupload file.";
            exit;
        }
    }

    // Update data aktivitas ke database
    $stmt = $conn->prepare("UPDATE aktivitas_dosen SET jenis_aktivitas = ?, deskripsi = ?, tanggal = ?, durasi = ?, status_aktivitas = ?, bukti = ? WHERE id = ? AND dosen_id = ?");
    $stmt->bind_param("ssssssii", $jenis_aktivitas, $deskripsi, $tanggal, $durasi, $status_aktivitas, $bukti, $aktivitas_id, $_SESSION['id']);

    if ($stmt->execute()) {
        // Menampilkan alert dan kemudian mengalihkan ke halaman lihat_aktivitas.php
        echo "<script>
            alert('Aktivitas berhasil diperbarui!');
            window.location.href = 'lihat_aktivitas.php';
        </script>";
        exit;
    } else {
        echo "<p class='text-red-600'>Gagal memperbarui aktivitas.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Aktivitas Dosen</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto my-12 p-6 bg-white shadow-xl rounded-lg">
        <h1 class="text-3xl font-bold text-center text-indigo-600 mb-8">Edit Aktivitas Dosen</h1>

        <form method="POST" action="edit_aktivitas.php?id=<?php echo $aktivitas_id; ?>" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="jenis_aktivitas" class="block text-lg font-medium text-gray-700">Jenis Aktivitas:</label>
                <input type="text" id="jenis_aktivitas" name="jenis_aktivitas"
                    value="<?php echo htmlspecialchars($jenis_aktivitas); ?>"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-lg font-medium text-gray-700">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required><?php echo htmlspecialchars($deskripsi); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="tanggal" class="block text-lg font-medium text-gray-700">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($tanggal); ?>"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                <div>
                    <label for="durasi" class="block text-lg font-medium text-gray-700">Durasi :</label>
                    <input type="text" id="durasi" name="durasi" value="<?php echo htmlspecialchars($durasi); ?>"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
            </div>

            <div class="mb-4">
                <label for="status_aktivitas" class="block text-lg font-medium text-gray-700">Status Aktivitas:</label>
                <input type="text" id="status_aktivitas" name="status_aktivitas"
                    value="<?php echo htmlspecialchars($status_aktivitas); ?>"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
            </div>

            <div class="mb-4">
                <label for="bukti" class="block text-lg font-medium text-gray-700">Bukti (Opsional):</label>
                <input type="file" id="bukti" name="bukti"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex justify-center">
                <button type="submit"
                    class="w-1/2 py-3 px-6 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Update
                    Aktivitas</button>
            </div>

        </form>


    </div>

</body>

</html>