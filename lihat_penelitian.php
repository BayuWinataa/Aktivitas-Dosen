<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login

// Query untuk mengambil data penelitian dosen yang sedang login
$stmt = $conn->prepare("SELECT id, judul, deskripsi, kategori, tanggal_mulai, tanggal_selesai, status FROM penelitian WHERE dosen_id = ?");
$stmt->bind_param("i", $dosen_id);
$stmt->execute();
$stmt->bind_result($penelitian_id, $judul, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $status);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penelitian Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12 ">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-semibold text-blue-600 mb-6">Daftar Penelitian Anda</h1>

            <!-- Daftar Penelitian -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                <?php while ($stmt->fetch()) { ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p><strong class="text-lg font-medium">Judul:</strong> <?php echo htmlspecialchars($judul); ?></p>
                    <!-- <p>
                        <strong>Status:</strong>
                        <span class="font-medium text-gray-600">
                            <?php echo htmlspecialchars($status); ?>
                        </span>

                    </p> -->
                    <div class="mt-4 flex items-center space-x-4">
                        <a href="detail_penelitian.php?id=<?php echo $penelitian_id; ?>"
                            class="text-blue-500 hover:underline">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="edit_penelitian.php?id=<?php echo $penelitian_id; ?>"
                            class="text-yellow-500 hover:underline">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="hapus_penelitian.php?id=<?php echo $penelitian_id; ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus penelitian ini?')"
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