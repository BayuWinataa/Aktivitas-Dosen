<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login
$penelitian_id = $_GET['id'];  // ID penelitian yang ingin dilihat

// Query untuk mengambil detail penelitian berdasarkan ID dan dosen_id
$stmt = $conn->prepare("SELECT judul, deskripsi, kategori, tanggal_mulai, tanggal_selesai, status, pendanaan, publikasi, kata_kunci, lampiran, hasil_penelitian 
                        FROM penelitian WHERE dosen_id = ? AND id = ?");
$stmt->bind_param("ii", $dosen_id, $penelitian_id);
$stmt->execute();
$stmt->bind_result($judul, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $status, $pendanaan, $publikasi, $kata_kunci, $lampiran, $hasil_penelitian);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penelitian</title>
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

            <h1 class="text-3xl font-semibold text-blue-600 mb-6 text-center">Detail Penelitian</h1>

            <div class="space-y-6">

                <!-- Judul Penelitian -->
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Judul:</strong></p>
                    <p class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($judul); ?></p>
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Deskripsi:</strong></p>
                    <p class="text-base text-gray-700"><?php echo nl2br(htmlspecialchars($deskripsi)); ?></p>
                </div>

                <!-- Kategori, Tanggal, Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div ss="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Kategori:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($kategori); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Tanggal Mulai:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($tanggal_mulai); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Tanggal Selesai:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($tanggal_selesai); ?></p>
                    </div>
                    <!-- <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Status:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($status); ?></p>
                    </div> -->
                </div>

                <!-- Pendanaan, Publikasi, Kata Kunci -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Pendanaan (Rp):</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($pendanaan); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Publikasi:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($publikasi); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Kata Kunci:</strong></p>
                        <p class="text-base text-gray-700"><?php echo htmlspecialchars($kata_kunci); ?></p>
                    </div>
                </div>

                <!-- Lampiran -->
                <?php if ($lampiran) { ?>
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Lampiran:</strong></p>
                    <a href="<?php echo htmlspecialchars($lampiran); ?>" target="_blank"
                        class="text-blue-500 hover:underline">Lihat Lampiran</a>
                </div>
                <?php } else { ?>
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Lampiran:</strong></p>
                    <p class="text-base text-gray-700">Tidak ada lampiran.</p>
                </div>
                <?php } ?>

                <!-- Hasil Penelitian -->
                <div class="flex flex-col">
                    <p class="text-lg font-medium text-gray-700"><strong>Hasil Penelitian:</strong></p>
                    <p class="text-base text-gray-700"><?php echo nl2br(htmlspecialchars($hasil_penelitian)); ?></p>
                </div>

            </div>

            <?php } else { ?>
            <p class="text-red-500 text-center">Data penelitian tidak ditemukan atau Anda tidak memiliki akses.</p>
            <?php } ?>

        </div>

    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>