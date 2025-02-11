<?php
// Mulai session dan cek apakah admin login
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
require 'config.php';

// Mendapatkan ID pengabdian dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan detail pengabdian berdasarkan ID
    $query_pengabdian = "
        SELECT p.*, u.nama_dosen, u.nip, u.fakultas, u.program_studi
        FROM pengabdian_dosen p
        JOIN users u ON p.dosen_id = u.id
        WHERE p.id = $id
    ";

    $result_pengabdian = $conn->query($query_pengabdian);

    if ($result_pengabdian->num_rows > 0) {
        $row = $result_pengabdian->fetch_assoc();
    } else {
        echo "<p class='text-danger'>Data pengabdian tidak ditemukan.</p>";
        exit;
    }
} else {
    echo "<p class='text-danger'>ID tidak valid.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengabdian Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f4f7fc;
        font-family: 'Arial', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: auto;
    }

    h1,
    h2 {
        font-family: 'Roboto', sans-serif;
        color: #007bff;
    }

    .card-custom {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #fff;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .card-header h5 {
        margin-bottom: 0;
        font-weight: 600;
    }

    .card-body {
        padding: 2rem;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .btn-custom:hover {
        background-color: #0056b3;
        color: white;
    }

    .text-info {
        font-weight: bold;
    }

    .text-muted {
        font-size: 0.9rem;
    }

    .back-btn {
        font-size: 16px;
        padding: 13px 15px;
        text-decoration: none;
        background-color: rgb(0, 102, 255);
        border: none;
        border-radius: 5px;
        color: #fff;
    }

    .back-btn:hover {
        background-color: rgb(27, 127, 227);
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .document-link {
        color: #007bff;
        text-decoration: none;
    }

    .document-link:hover {
        text-decoration: underline;
    }

    .no-print {
        display: inline-block;
    }

    @media print {
        .no-print {
            display: none;
        }

        .back-btn {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="container my-5">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1>Detail Pengabdian Dosen</h1>
        </div>

        <!-- Button Back to List -->
        <div class="mb-4">
            <a href="admin_dashboard.php" class="back-btn "><i class="fas fa-arrow-left"></i> Kembali ke
                Dashboard</a>
            <div class="mt-4 text-center no-print">
                <button onclick="window.print()" class="btn btn-custom"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>

        <!-- Button Cetak -->


        <!-- Detail Pengabdian Section -->
        <div class="card card-custom">
            <div class="card-header">
                <h5><?php echo $row['judul_kegiatan']; ?></h5>
            </div>
            <div class="card-body">
                <div class="detail-section">
                    <p><strong>Nama Dosen:</strong> <span class="text-info"><?php echo $row['nama_dosen']; ?></span></p>
                    <p><strong>NIP:</strong> <?php echo $row['nip']; ?></p>
                    <p><strong>Fakultas:</strong> <?php echo $row['fakultas']; ?></p>
                    <p><strong>Program Studi:</strong> <?php echo $row['program_studi']; ?></p>
                </div>

                <div class="detail-section">
                    <p><strong>Tanggal Mulai:</strong> <?php echo date("d-m-Y", strtotime($row['tanggal_mulai'])); ?>
                    </p>
                    <p><strong>Tanggal Selesai:</strong>
                        <?php echo date("d-m-Y", strtotime($row['tanggal_selesai'])); ?></p>
                    <p><strong>Lokasi:</strong> <?php echo $row['lokasi']; ?></p>
                </div>

                <div class="detail-section">
                    <p><strong>Deskripsi Kegiatan:</strong></p>
                    <p><?php echo nl2br($row['deskripsi_kegiatan']); ?></p>
                </div>

                <div class="detail-section">
                    <p><strong>Manfaat:</strong></p>
                    <p><?php echo nl2br($row['manfaat']); ?></p>
                </div>

                <div class="detail-section">
                    <p><strong>Tim Pelaksana:</strong> <?php echo $row['tim_pelaksana']; ?></p>
                </div>

                <div class="detail-section">
                    <p><strong>Dokumentasi:</strong></p>
                    <?php if ($row['dokumentasi']) { ?>
                    <a href="<?php echo $row['dokumentasi']; ?>" target="_blank" class="document-link"><i
                            class="fas fa-eye"></i> Lihat Dokumentasi</a>
                    <?php } else { ?>
                    <span class="text-muted">Belum ada dokumentasi.</span>
                    <?php } ?>
                </div>
            </div>
        </div>


    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>