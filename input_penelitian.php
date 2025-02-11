<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['kategori'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status = $_POST['status'];
    $pendanaan = $_POST['pendanaan'];
    $publikasi = $_POST['publikasi'];
    $kata_kunci = $_POST['kata_kunci'];
    $lampiran = $_FILES['lampiran'];
    $hasil_penelitian = $_POST['hasil_penelitian'];

    // Menangani file lampiran jika ada
    $lampiran_file = "";
    if ($lampiran['error'] === UPLOAD_ERR_OK) {
        // Menentukan lokasi untuk menyimpan file
        $lampiran_file = 'uploads/' . basename($lampiran['name']);
        // Memindahkan file ke folder tujuan
        if (!move_uploaded_file($lampiran['tmp_name'], $lampiran_file)) {
            echo "<p>Error: File upload failed.</p>";
            exit;
        }
    }

    // Query untuk menyimpan data penelitian ke database
    // Query untuk menyimpan data penelitian
    $stmt = $conn->prepare("INSERT INTO penelitian (dosen_id, judul, deskripsi, kategori, tanggal_mulai, tanggal_selesai, status, pendanaan, publikasi, kata_kunci, lampiran, hasil_penelitian) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Pastikan semua parameter sesuai dengan tipe data
    $stmt->bind_param("isssssssssss", $dosen_id, $judul, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $status, $pendanaan, $publikasi, $kata_kunci, $lampiran_file, $hasil_penelitian);

    // Eksekusi query
    if ($stmt->execute()) {
        // Menambahkan alert dengan JavaScript dan melakukan redirect
        echo "<script>
                alert('Penelitian berhasil disimpan.');
                window.location.href = 'dosen_dashboard.php';
              </script>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Penelitian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    .bg-custom {
        background-color: #f9fafb;
        /* Warna latar belakang abu-abu sangat terang */
    }

    .btn-primary {
        background-color: #1D4ED8;
        /* Warna biru untuk tombol */
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563EB;
        /* Hover effect */
    }
    </style>
</head>

<body class="bg-custom flex">

    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12 px-6">
        <div class="bg-white shadow-lg rounded-xl p-8 space-y-8">

            <h1 class="text-3xl font-bold text-blue-600 text-center">Input Data Penelitian</h1>

            <!-- Form untuk menginput data penelitian -->
            <form method="POST" enctype="multipart/form-data" class="space-y-3">

                <!-- Judul Penelitian -->
                <div class="flex flex-col">
                    <label for="judul" class="text-lg font-medium text-gray-700">Judul Penelitian:</label>
                    <input type="text" name="judul"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Deskripsi Penelitian -->
                <div class="flex flex-col">
                    <label for="deskripsi" class="text-lg font-medium text-gray-700">Deskripsi Penelitian:</label>
                    <textarea name="deskripsi"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="6" required></textarea>
                </div>

                <!-- Kategori Penelitian -->
                <div class="flex flex-col">
                    <label for="kategori" class="text-lg font-medium text-gray-700">Kategori Penelitian:</label>
                    <input type="text" name="kategori"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Mulai -->
                <div class="flex flex-col">
                    <label for="tanggal_mulai" class="text-lg font-medium text-gray-700">Tanggal Mulai:</label>
                    <input type="date" name="tanggal_mulai"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Tanggal Selesai -->
                <div class="flex flex-col">
                    <label for="tanggal_selesai" class="text-lg font-medium text-gray-700">Tanggal Selesai:</label>
                    <input type="date" name="tanggal_selesai"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <!-- Status Penelitian -->
                <!-- <div class="flex flex-col">
                    <label for="status" class="text-lg font-medium text-gray-700">Status Penelitian:</label>
                    <input type="text" name="status"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div> -->

                <!-- Pendanaan -->
                <div class="flex flex-col">
                    <label for="pendanaan" class="text-lg font-medium text-gray-700">Pendanaan (Rp):</label>
                    <input type="text" name="pendanaan" inputmode="numeric"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Publikasi -->
                <div class="flex flex-col">
                    <label for="publikasi" class="text-lg font-medium text-gray-700">Publikasi:</label>
                    <input type="text" name="publikasi"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Kata Kunci -->
                <div class="flex flex-col">
                    <label for="kata_kunci" class="text-lg font-medium text-gray-700">Kata Kunci:</label>
                    <input type="text" name="kata_kunci"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Lampiran -->
                <div class="flex flex-col">
                    <label for="lampiran" class="text-lg font-medium text-gray-700">Lampiran (Optional):</label>
                    <input type="file" name="lampiran"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Hasil Penelitian -->
                <div class="flex flex-col">
                    <label for="hasil_penelitian" class="text-lg font-medium text-gray-700">Hasil Penelitian:</label>
                    <textarea name="hasil_penelitian"
                        class="mt-2 p-3 border border-gray-300 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="6"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="btn-primary py-3 px-6 rounded-lg font-semibold w-full md:w-auto">
                        Submit Penelitian
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>