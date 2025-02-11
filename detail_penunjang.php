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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penunjang Pengabdian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-semibold text-blue-600 mb-6">Detail Penunjang Pengabdian</h1>

            <!-- Menampilkan Detail Penunjang -->
            <div class="space-y-4">
                <p><strong class="text-lg font-medium">Jenis Penunjang:</strong>
                    <?php echo htmlspecialchars($jenis_penunjang); ?></p>
                <p><strong class="text-lg font-medium">Deskripsi:</strong> <?php echo htmlspecialchars($deskripsi); ?>
                </p>
                <p><strong class="text-lg font-medium">Tanggal Pengajuan:</strong>
                    <?php echo htmlspecialchars($tanggal_pengajuan); ?></p>
                <p><strong class="text-lg font-medium">Jumlah Penunjang:</strong>
                    <?php echo htmlspecialchars($jumlah_penunjang); ?></p>

                <?php if ($bukti_penunjang != NULL) { ?>
                <p><strong class="text-lg font-medium">Bukti Penunjang:</strong>
                    <a href="<?php echo htmlspecialchars($bukti_penunjang); ?>" target="_blank"
                        class="text-blue-500 hover:underline">Lihat Bukti</a>
                </p>
                <?php } else { ?>
                <p><strong class="text-lg font-medium">Bukti Penunjang:</strong> Tidak ada bukti yang diupload.</p>
                <?php } ?>
            </div>

        </div>
    </div>

</body>

</html>

<?php
$conn->close();
?>