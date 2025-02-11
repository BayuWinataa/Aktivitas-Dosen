<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$penelitian_id = $_GET['id'];  // ID penelitian yang ingin dilihat

// Query untuk mengambil detail penelitian berdasarkan ID
$stmt = $conn->prepare("SELECT p.judul, p.deskripsi, p.kategori, p.tanggal_mulai, p.tanggal_selesai, p.status, 
                               p.pendanaan, p.publikasi, p.kata_kunci, p.lampiran, p.hasil_penelitian, 
                               u.nama_dosen, u.nip, u.fakultas, u.program_studi
                        FROM penelitian p
                        JOIN users u ON p.dosen_id = u.id
                        WHERE p.id = ?");
$stmt->bind_param("i", $penelitian_id);
$stmt->execute();
$stmt->bind_result($judul, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $status, $pendanaan, $publikasi, $kata_kunci, $lampiran, $hasil_penelitian, $nama_dosen, $nip, $fakultas, $program_studi);
$stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penelitian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <style>
    /* Kustomisasi gaya untuk teks */
    .section-label {
        font-weight: bold;
        width: 200px;
    }

    .section-value {
        flex: 1;
    }

    /* Gaya untuk kotak lampiran */
    .attachment-link {
        text-decoration: underline;
        color: #1D4ED8;
        /* Tailwind blue */
    }

    /* Styling untuk Card */
    .card {
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 1.875rem;
        /* 30px */
        font-weight: 600;
    }

    .card-content {
        font-size: 1rem;
        line-height: 1.5;
    }

    /* CSS untuk media print */
    @media print {

        /* Menyembunyikan tombol dan navigasi saat cetak */
        .no-print {
            display: none;
        }

        /* Menambahkan margin dan padding khusus untuk tampilan cetak */
        body {
            margin: 20mm;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .card {
            margin-bottom: 0;
            page-break-after: always;
        }

        .section-label {
            width: 150px;
            /* Lebih ramping untuk layout cetak */
        }

        /* Styling untuk gambar lampiran */
        .print-attachment {
            width: 100%;
            max-width: 100px;
            height: auto;
            margin-top: 10px;
        }
    }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">

        <div class="text-center mb-6">
            <h1 class="card-title">Detail Penelitian</h1>
        </div>

        <?php if ($judul): ?>
        <div class="card">
            <!-- Informasi Dosen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                <div class="flex">
                    <p class="section-label">Nama Dosen:</p>
                    <p class="section-value"><?php echo htmlspecialchars($nama_dosen); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">NIP:</p>
                    <p class="section-value"><?php echo htmlspecialchars($nip); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Fakultas:</p>
                    <p class="section-value"><?php echo htmlspecialchars($fakultas); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Program Studi:</p>
                    <p class="section-value"><?php echo htmlspecialchars($program_studi); ?></p>
                </div>
            </div>

            <!-- Judul Penelitian -->
            <div class="flex mb-1">
                <p class="section-label">Judul:</p>
                <p class="section-value"><?php echo htmlspecialchars($judul); ?></p>
            </div>

            <!-- Deskripsi, Kategori, dan Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                <div class="flex">
                    <p class="section-label">Deskripsi:</p>
                    <p class="section-value"><?php echo nl2br(htmlspecialchars($deskripsi)); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Kategori:</p>
                    <p class="section-value"><?php echo htmlspecialchars($kategori); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Tanggal Mulai:</p>
                    <p class="section-value"><?php echo htmlspecialchars($tanggal_mulai); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Tanggal Selesai:</p>
                    <p class="section-value"><?php echo htmlspecialchars($tanggal_selesai); ?></p>
                </div>
            </div>

            <!-- Status, Pendanaan, Publikasi, dan Kata Kunci -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1">

                <div class="flex">
                    <p class="section-label">Pendanaan:</p>
                    <p class="section-value"><?php echo htmlspecialchars($pendanaan); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Publikasi:</p>
                    <p class="section-value"><?php echo htmlspecialchars($publikasi); ?></p>
                </div>
                <div class="flex">
                    <p class="section-label">Kata Kunci:</p>
                    <p class="section-value"><?php echo htmlspecialchars($kata_kunci); ?></p>
                </div>
            </div>

            <!-- Lampiran (Jika lampiran adalah gambar, tampilkan gambar) -->
            <div class="flex">
                <p class="section-label">Lampiran:</p>
                <?php if ($lampiran): ?>
                <!-- Jika lampiran adalah gambar, tampilkan gambar tersebut -->
                <p class="section-value">
                    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $lampiran)): ?>
                    <img src="<?php echo htmlspecialchars($lampiran); ?>" alt="Lampiran Penelitian" width="200"
                        height="200" class="print-attachment">

                    <?php else: ?>
                    <a href="<?php echo htmlspecialchars($lampiran); ?>" target="_blank" class="attachment-link">Lihat
                        Lampiran</a>
                    <?php endif; ?>
                </p>
                <?php else: ?>
                <p class="section-value">Tidak ada lampiran.</p>
                <?php endif; ?>
            </div>

            <!-- Hasil Penelitian -->
            <div class="flex mb-4">
                <p class="section-label">Hasil Penelitian:</p>
                <p class="section-value"><?php echo nl2br(htmlspecialchars($hasil_penelitian)); ?></p>
            </div>
        </div>

        <?php else: ?>
        <div class="card">
            <p class="text-red-500">Data penelitian tidak ditemukan.</p>
        </div>
        <?php endif; ?>

        <!-- Tombol Cetak (Hanya untuk tampilan layar, disembunyikan saat cetak) -->
        <div class="mt-6 text-center no-print">
            <button onclick="window.print()"
                class="inline-block bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Cetak</button>
            <a href="admin_dashboard.php"
                class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Kembali ke Dashboard
                Admin</a>
        </div>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>