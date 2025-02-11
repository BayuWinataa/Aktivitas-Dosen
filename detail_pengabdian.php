<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login
$pengabdian_id = $_GET['id'];  // ID pengabdian yang ingin dilihat

// Query untuk mengambil detail pengabdian berdasarkan ID dan dosen_id
$stmt = $conn->prepare("SELECT judul_kegiatan, deskripsi_kegiatan, tanggal_mulai, tanggal_selesai, lokasi, manfaat, tim_pelaksana, dokumentasi 
                        FROM pengabdian_dosen WHERE dosen_id = ? AND id = ?");
$stmt->bind_param("ii", $dosen_id, $pengabdian_id);
$stmt->execute();
$stmt->bind_result($judul_kegiatan, $deskripsi_kegiatan, $tanggal_mulai, $tanggal_selesai, $lokasi, $manfaat, $tim_pelaksana, $dokumentasi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengabdian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 flex">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12 px-6">
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-8">

            <?php if ($stmt->fetch()) { ?>

            <h1 class="text-3xl font-semibold text-blue-600 mb-6 text-center">Detail Pengabdian</h1>

            <div class="space-y-6">

                <!-- Judul Pengabdian -->
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Judul Kegiatan:</strong></p>
                    <p class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($judul_kegiatan); ?></p>
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Deskripsi Kegiatan:</strong></p>
                    <p class="text-base text-gray-700"><?php echo nl2br(htmlspecialchars($deskripsi_kegiatan)); ?></p>
                </div>

                <!-- Tanggal Mulai dan Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Tanggal Mulai:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($tanggal_mulai); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Tanggal Selesai:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($tanggal_selesai); ?></p>
                    </div>
                </div>

                <!-- Lokasi dan Manfaat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Lokasi:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($lokasi); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Manfaat:</strong></p>
                        <p class="text-base text-gray-700"><?php echo nl2br(htmlspecialchars($manfaat)); ?></p>
                    </div>
                </div>

                <!-- Tim Pelaksana -->
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Tim Pelaksana:</strong></p>
                    <p class="text-base text-gray-700"><?php echo htmlspecialchars($tim_pelaksana); ?></p>
                </div>

                <!-- Dokumentasi -->
                <?php if ($dokumentasi) { ?>
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Dokumentasi:</strong></p>
                    <a href="<?php echo htmlspecialchars($dokumentasi); ?>" target="_blank"
                        class="text-blue-500 hover:underline">Lihat Dokumentasi</a>
                </div>
                <?php } else { ?>
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Dokumentasi:</strong></p>
                    <p class="text-base text-gray-700">Tidak ada dokumentasi.</p>
                </div>
                <?php } ?>

            </div>

            <?php } else { ?>
            <p class="text-red-500 text-center">Data pengabdian tidak ditemukan atau Anda tidak memiliki akses.</p>
            <?php } ?>

        </div>

    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>