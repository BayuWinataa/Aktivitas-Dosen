<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login

// Query untuk mengambil data pengabdian dosen yang sedang login
$stmt = $conn->prepare("SELECT id, judul_kegiatan, deskripsi_kegiatan, tanggal_mulai, tanggal_selesai, lokasi, manfaat, tim_pelaksana, dokumentasi FROM pengabdian_dosen WHERE dosen_id = ?");
$stmt->bind_param("i", $dosen_id);
$stmt->execute();
$stmt->bind_result($pengabdian_id, $judul_kegiatan, $deskripsi_kegiatan, $tanggal_mulai, $tanggal_selesai, $lokasi, $manfaat, $tim_pelaksana, $dokumentasi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengabdian Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-6 px-3">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-semibold text-blue-600 mb-6">Daftar Pengabdian Anda</h1>

            <!-- Daftar Pengabdian -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                <?php while ($stmt->fetch()) { ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-2">
                    <p><strong class="text-lg font-medium">Judul Kegiatan:</strong>
                        <?php echo htmlspecialchars($judul_kegiatan); ?></p>
                    <p><strong class="text-sm font-medium">Deskripsi:</strong>
                        <?php echo htmlspecialchars($deskripsi_kegiatan); ?></p>
                    <p><strong class="text-sm font-medium">Tanggal Mulai:</strong>
                        <?php echo htmlspecialchars($tanggal_mulai); ?></p>
                    <p><strong class="text-sm font-medium">Tanggal Selesai:</strong>
                        <?php echo htmlspecialchars($tanggal_selesai); ?></p>
                    <p><strong class="text-sm font-medium">Lokasi:</strong> <?php echo htmlspecialchars($lokasi); ?></p>
                    <p><strong class="text-sm font-medium">Manfaat:</strong> <?php echo htmlspecialchars($manfaat); ?>
                    </p>
                    <p><strong class="text-sm font-medium">Tim Pelaksana:</strong>
                        <?php echo htmlspecialchars($tim_pelaksana); ?></p>

                    <div class="mt-4 flex items-center space-x-4">
                        <a href="detail_pengabdian.php?id=<?php echo $pengabdian_id; ?>"
                            class="text-blue-500 hover:underline">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="edit_pengabdian.php?id=<?php echo $pengabdian_id; ?>"
                            class="text-yellow-500 hover:underline">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="hapus_pengabdian.php?id=<?php echo $pengabdian_id; ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengabdian ini?')"
                            class="text-red-500 hover:underline">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>