<?php
session_start();

// Cek role pengguna
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'config.php';

// Cek apakah ada parameter id yang diterima dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan detail data penunjang pengabdian berdasarkan id
    $query = "
        SELECT p.id, p.jenis_penunjang, p.deskripsi, p.tanggal_pengajuan, p.jumlah_penunjang, p.bukti_penunjang, u.nama_dosen, u.nip
        FROM penunjang_pengabdian p
        JOIN users u ON p.dosen_id = u.id
        WHERE p.id = ?
    ";

    // Menyiapkan statement SQL
    if ($stmt = $conn->prepare($query)) {
        // Bind parameter id
        $stmt->bind_param('i', $id);

        // Menjalankan query
        $stmt->execute();

        // Mendapatkan hasilnya
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Menampilkan data detail
            $row = $result->fetch_assoc();
        } else {
            // Jika tidak ada data ditemukan
            echo "<p class='text-danger'>Data penunjang pengabdian tidak ditemukan.</p>";
            exit;
        }
    } else {
        echo "<p class='text-danger'>Terjadi kesalahan dalam query.</p>";
        exit;
    }
} else {
    echo "<p class='text-danger'>ID tidak ditemukan.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penunjang Pengabdian</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f4f7fc;
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 900px;
        margin: auto;
    }

    .card-custom {
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .card-custom:hover {
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
    }

    .btn-custom:hover {
        background-color: #0056b3;
        color: white;
    }

    .text-danger {
        font-weight: bold;
    }

    .text-end {
        text-align: right;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 20px;
    }

    .card-header h3 {
        margin: 0;
    }

    .card-body p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
    }

    .no-print {
        display: inline-block;
        margin-bottom: 20px;
    }

    @media print {
        .no-print {
            display: none;
        }

        .dashboard {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="container my-5">
        <!-- Header -->
        <h1 class="text-center text-primary mb-4">Detail Penunjang Pengabdian</h1>

        <!-- Logout Button -->
        <div class="text-end mb-4 no-print">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Detail Penunjang Pengabdian -->
        <div class="card card-custom">
            <div class="card-header">
                <h3>Informasi Penunjang Pengabdian</h3>
            </div>
            <div class="card-body">
                <p><strong>Nama Dosen:</strong> <?php echo $row['nama_dosen']; ?></p>
                <p><strong>NIP:</strong> <?php echo $row['nip']; ?></p>
                <p><strong>Jenis Penunjang:</strong> <?php echo $row['jenis_penunjang']; ?></p>
                <p><strong>Deskripsi:</strong> <?php echo $row['deskripsi']; ?></p>
                <p><strong>Tanggal Pengajuan:</strong>
                    <?php echo date('d-m-Y', strtotime($row['tanggal_pengajuan'])); ?></p>
                <p><strong>Jumlah Penunjang:</strong> <?php echo $row['jumlah_penunjang']; ?></p>
                <p><strong>Bukti Penunjang:</strong> <?php
                                                        if (!empty($row['bukti_penunjang'])) {
                                                            echo "<a href='{$row['bukti_penunjang']}' target='_blank'>Lihat Bukti</a>";
                                                        } else {
                                                            echo "Tidak ada bukti penunjang.";
                                                        }
                                                        ?></p>

                <!-- Back Button -->
                <a href="admin_dashboard.php" class="btn btn-back btn-sm dashboard">Kembali ke Dashboard</a>
                <!-- Print Button -->
                <div class="no-print text-center">
                    <button onclick="window.print()" class="btn btn-custom btn-sm"><i class="fas fa-print"></i>
                        Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Link to Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Menutup koneksi database setelah selesai
$conn->close();
?>