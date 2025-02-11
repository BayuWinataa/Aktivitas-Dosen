<?php
// File: detail_dosen.php
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
    <title>Detail Akun Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background-color: #1D4ED8;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563EB;
    }

    .btn-secondary {
        background-color: #6B7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4B5563;
    }
    </style>
</head>

<body class="bg-gray-100 flex">
    <?php include('sidebar.php'); ?>

    <div class=" mx-auto py-12 w-[900px] ">
        <div class="card bg-white rounded-lg p-8 space-y-8 shadow-lg">

            <!-- Card Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-semibold text-blue-600">Detail Akun Dosen</h2>

            </div>

            <!-- Informasi Dosen -->
            <h4 class="text-2xl font-semibold text-blue-600 mb-6">Informasi Dosen</h4>
            <div class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
                    <strong class="text-gray-700">Nama Dosen:</strong>
                    <p class="text-gray-600"><?php echo htmlspecialchars($nama_dosen ?? ''); ?></p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
                    <strong class="text-gray-700">NIP:</strong>
                    <p class="text-gray-600"><?php echo htmlspecialchars($nip ?? ''); ?></p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
                    <strong class="text-gray-700">Fakultas:</strong>
                    <p class="text-gray-600"><?php echo htmlspecialchars($fakultas ?? ''); ?></p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
                    <strong class="text-gray-700">Program Studi:</strong>
                    <p class="text-gray-600"><?php echo htmlspecialchars($program_studi ?? ''); ?></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 text-center space-x-4">
                <a href="edit_dosen.php"
                    class="btn-primary py-3 px-8 rounded-lg font-semibold w-full md:w-auto mb-4 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i> Edit Data Dosen
                </a>
            </div>
        </div>
    </div>

</body>

</html>