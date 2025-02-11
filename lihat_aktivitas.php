<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';
$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login

// Query untuk mengambil daftar aktivitas dosen yang sedang login
$stmt = $conn->prepare("SELECT id, jenis_aktivitas, deskripsi, tanggal, durasi, status_aktivitas, bukti FROM aktivitas_dosen WHERE dosen_id = ?");
$stmt->bind_param("i", $dosen_id);
$stmt->execute();
$stmt->bind_result($aktivitas_id, $jenis_aktivitas, $deskripsi, $tanggal, $durasi, $status_aktivitas, $bukti);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Aktivitas Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>
    <!-- Container Utama -->
    <div class="flex flex-col items-center py-12 px-6">
        <div class="w-[900px] bg-white rounded-lg shadow-xl p-8">
            <!-- Header -->
            <h1 class="text-4xl font-bold text-center text-blue-600 mb-8">Daftar Aktivitas Anda</h1>

            <?php
            $aktivitas_found = false;
            while ($stmt->fetch()) {
                $aktivitas_found = true;

                echo "
                <div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6 mb-6 bg-gray-50 border border-gray-200 rounded-lg shadow-md'>
                    <div class='sm:col-span-1'>
                        <h2 class='text-2xl font-semibold text-gray-800'>{$jenis_aktivitas}</h2>
                        <p class='text-gray-700 mt-2'><strong>Tanggal:</strong> " . htmlspecialchars($tanggal) . "</p>
                        <p class='text-gray-700'><strong>Durasi:</strong> " . htmlspecialchars($durasi) . " </p>
                        <span class='text-gray-700'><strong>Status:</strong> " . htmlspecialchars($status_aktivitas) . "</span>
                    </div>
                    <div class='sm:col-span-1 lg:col-span-2'>
                        <p class='text-gray-700'><strong>Deskripsi:</strong><br>" . nl2br(htmlspecialchars($deskripsi)) . "</p>";

                if (!empty($bukti)) {
                    echo "<p class='text-gray-700 mt-2'><strong>Bukti:</strong> 
                        <a href='" . htmlspecialchars($bukti) . "' target='_blank' class='text-blue-500 hover:text-blue-700 underline'>Lihat Bukti</a>
                    </p>";
                } else {
                    echo "<p class='text-gray-700 mt-2'><strong>Bukti:</strong> Tidak ada bukti tersedia</p>";
                }

                echo "
                    <div class='flex mt-4 space-x-4'>
                        <!-- Edit Button with Icon and Text -->
                        <a href='edit_aktivitas.php?id=" . $aktivitas_id . "' 
                            class='flex items-center text-yellow-500 hover:text-yellow-600 text-lg transition-colors duration-200 ease-in-out'>
                            <i class='fas fa-edit text-2xl mr-2'></i> Edit
                        </a>
                        <!-- Delete Button with Icon and Text -->
                        <a href='hapus_aktivitas.php?id=" . $aktivitas_id . "' 
                            class='flex items-center text-red-500 hover:text-red-600 text-lg transition-colors duration-200 ease-in-out' 
                            onclick='return confirm(\"Apakah Anda yakin ingin menghapus aktivitas ini?\")'>
                            <i class='fas fa-trash-alt text-2xl mr-2'></i> Hapus
                        </a>
                    </div>
                </div>
            </div>";
            }

            if (!$aktivitas_found) {
                echo "<p class='text-center text-gray-500'>Belum ada aktivitas yang terdaftar.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</body>



</html>