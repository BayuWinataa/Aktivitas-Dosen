<?php
// File: dosen_dashboard.php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$id = $_SESSION['id'];

// Fetch existing data
$stmt = $conn->prepare("SELECT nama_dosen, nip, fakultas, program_studi FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nama_dosen, $nip, $fakultas, $program_studi);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dosen Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex ">
        <!-- Sidebar -->
        <?php include('sidebar.php'); ?>
        <!-- Main Content -->
        <main class="flex-1 bg-gray-100 p-6">
            <!-- Content Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-blue-600">Selamat Datang di Dashboard</h1>
                <p class="text-gray-700">Kelola data penelitian, aktivitas, pengabdian, dan penunjang Anda dengan mudah.
                </p>
            </div>

            <!-- Pengelolaan Penelitian -->
            <section class="mb-3">
                <h2 class="text-xl font-semibold text-blue-600 mb-4">Pengelolaan Penelitian</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="lihat_penelitian.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Lihat Daftar Penelitian</span>
                        <i class="fas fa-list-alt text-blue-600"></i>
                    </a>
                    <a href="input_penelitian.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Input Penelitian Baru</span>
                        <i class="fas fa-plus-circle text-blue-600"></i>
                    </a>
                </div>
            </section>

            <!-- Pengelolaan Aktivitas -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold text-blue-600 mb-4">Pengelolaan Aktivitas</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="lihat_aktivitas.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Lihat Daftar Aktivitas</span>
                        <i class="fas fa-tasks text-blue-600"></i>
                    </a>
                    <a href="input_aktivitas.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Input Aktivitas Baru</span>
                        <i class="fas fa-plus text-blue-600"></i>
                    </a>
                </div>
            </section>

            <!-- Pengelolaan Pengabdian -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold text-blue-600 mb-4">Pengelolaan Pengabdian</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="lihat_pengabdian.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Lihat Daftar Pengabdian</span>
                        <i class="fas fa-handshake text-blue-600"></i>
                    </a>
                    <a href="input_pengabdian.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Input Pengabdian Baru</span>
                        <i class="fas fa-plus-circle text-blue-600"></i>
                    </a>
                </div>
            </section>

            <!-- Pengelolaan Penunjang -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold text-blue-600 mb-4">Pengelolaan Penunjang</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="lihat_penunjang.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Lihat Daftar Penunjang</span>
                        <i class="fas fa-list mr-3 text-blue-600"></i>
                    </a>
                    <a href="input_penunjang.php"
                        class="bg-white shadow rounded-lg p-4 flex items-center justify-between hover:shadow-lg transition duration-200">
                        <span class="text-blue-600 font-medium">Input Penunjang Baru</span>
                        <i class="fas fa-plus-circle text-blue-600"></i>
                    </a>
                </div>
            </section>

        </main>
    </div>

</body>


</html>