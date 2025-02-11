<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $aktivitas_id = $_GET['id'];

    // Query untuk mengambil detail aktivitas berdasarkan ID
    $query = "
        SELECT a.id, a.jenis_aktivitas, a.deskripsi, a.tanggal, a.durasi, a.status_aktivitas, a.bukti, u.nama_dosen
        FROM aktivitas_dosen a
        JOIN users u ON a.dosen_id = u.id
        WHERE a.id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $aktivitas_id);
    $stmt->execute();
    $stmt->bind_result($id, $jenis_aktivitas, $deskripsi, $tanggal, $durasi, $status_aktivitas, $bukti, $nama_dosen);
    $stmt->fetch();
    $stmt->close();

    if ($id) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aktivitas Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    <style>
    /* CSS untuk media cetak */
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
        }

        .attachment-link {
            color: blue;
            text-decoration: underline;
        }

        /* Menampilkan gambar lampiran dengan ukuran tetap saat cetak */
        .print-attachment {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="max-w-4xl mx-auto p-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-8 space-y-8">
            <h1 class="text-3xl font-semibold text-center text-gray-900 mb-6">Detail Aktivitas Dosen</h1>

            <!-- Informasi Aktivitas Dosen -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Nama Dosen:</strong>
                            <?php echo htmlspecialchars($nama_dosen); ?></p>
                        <p class="text-lg font-medium text-gray-700"><strong>Jenis Aktivitas:</strong>
                            <?php echo htmlspecialchars($jenis_aktivitas); ?></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-lg font-medium text-gray-700"><strong>Tanggal:</strong>
                            <?php echo htmlspecialchars($tanggal); ?></p>
                        <p class="text-lg font-medium text-gray-700"><strong>Durasi:</strong>
                            <?php echo htmlspecialchars($durasi); ?></p>
                    </div>
                </div>

                <!-- Deskripsi dan Status Aktivitas -->
                <div class="space-y-2">
                    <p class="text-lg font-medium text-gray-800"><strong>Deskripsi Aktivitas:</strong>
                        <?php echo nl2br(htmlspecialchars($deskripsi)); ?></p>
                    <p class="text-lg font-medium text-gray-800"><strong>Status Aktivitas:</strong>
                        <?php echo htmlspecialchars($status_aktivitas); ?></p>
                </div>

                <!-- Bukti Aktivitas -->
                <?php if ($bukti): ?>
                <div>
                    <p class="text-lg font-medium text-gray-800"><strong>Bukti Aktivitas:</strong>
                        <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bukti)): ?>
                        <!-- Jika bukti adalah gambar, tampilkan gambar dengan ukuran yang diatur -->
                        <img src="<?php echo htmlspecialchars($bukti); ?>" alt="Bukti Aktivitas"
                            class="print-attachment" width="200" height="200">
                        <?php else: ?>
                        <a href="<?php echo htmlspecialchars($bukti); ?>" target="_blank"
                            class="text-blue-600 hover:underline">Lihat Bukti</a>
                        <?php endif; ?>
                    </p>
                </div>
                <?php else: ?>
                <div>
                    <p class="text-lg font-medium text-gray-800"><strong>Bukti:</strong> Tidak ada bukti yang diunggah.
                    </p>
                </div>
                <?php endif; ?>

            </div>

            <!-- Kembali ke Dashboard Admin -->
            <div class="mt-8 text-center no-print">
                <button onclick="window.print()"
                    class="inline-block bg-green-500 text-white py-3 px-6 rounded-lg hover:bg-green-600 transition duration-300">Cetak</button>
                <a href="admin_dashboard.php"
                    class="inline-block bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-300">Kembali
                    ke Dashboard Admin</a>
            </div>

        </div>
    </div>

</body>

</html>

<?php
    } else {
        echo "<p class='text-center text-red-500'>Data aktivitas tidak ditemukan.</p>";
    }
} else {
    echo "<p class='text-center text-red-500'>ID aktivitas tidak valid.</p>";
}

$conn->close();
?>