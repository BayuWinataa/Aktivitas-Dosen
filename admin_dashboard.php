<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Custom Styles for a Better Look */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table thead {
        background-color: #007bff;
        color: white;
    }

    .card-custom {
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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

    .table td,
    .table th {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .text-danger {
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="mx-3 my-5">
        <!-- Header -->
        <h1 class="text-center mb-4 text-primary">Admin Dashboard</h1>

        <!-- Logout Button -->
        <div class="text-end mb-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Data Penelitian Section -->
        <h2 class="text-center mb-4">Data Penelitian</h2>

        <?php
        // Database connection and query fetching
        session_start();
        if ($_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }

        require 'config.php';

        // Query to get data for penelitian
        $query_penelitian = "
            SELECT p.id, p.judul, u.nama_dosen, u.nip, u.fakultas, u.program_studi, p.status
            FROM penelitian p
            JOIN users u ON p.dosen_id = u.id
            ORDER BY u.nama_dosen ASC
        ";

        $result_penelitian = $conn->query($query_penelitian);

        if ($result_penelitian->num_rows > 0) {
            echo "<div class='table-responsive'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIP</th>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th>Judul Penelitian</th>
                                
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>";

            // Adding serial number and displaying data
            $no = 1;
            while ($row = $result_penelitian->fetch_assoc()) {
                echo "<tr>
                        <td>" . $no++ . "</td>
                        <td>" . $row['nama_dosen'] . "</td>
                        <td>" . $row['nip'] . "</td>
                        <td>" . $row['fakultas'] . "</td>
                        <td>" . $row['program_studi'] . "</td>
                        <td>" . $row['judul'] . "</td>
                      
                        <td><a href='detail_penelitian_admin.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Detail</a></td>
                    </tr>";
            }

            echo "</tbody>
                </table>
                </div>";
        } else {
            echo "<p class='text-danger'>Data Masih Kosong</p>";
        }
        ?>

        <!-- Data Aktivitas Dosen Section -->
        <h2 class="text-center mb-4">Data Aktivitas Dosen</h2>

        <?php
        // Query to get data for aktivitas dosen
        $query_aktivitas = "
            SELECT a.id, a.jenis_aktivitas, u.nama_dosen, u.nip, u.fakultas, u.program_studi, a.status_aktivitas, a.dosen_id
            FROM aktivitas_dosen a
            JOIN users u ON a.dosen_id = u.id
            ORDER BY u.nama_dosen ASC
        ";

        $result_aktivitas = $conn->query($query_aktivitas);

        if ($result_aktivitas->num_rows > 0) {
            echo "<div class='table-responsive'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIP</th>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th>Jenis Aktivitas</th>
                                <th>Status Aktivitas</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>";

            // Adding serial number and displaying data
            $no = 1;
            while ($row = $result_aktivitas->fetch_assoc()) {
                echo "<tr>
                        <td>" . $no++ . "</td>
                        <td>" . $row['nama_dosen'] . "</td>
                        <td>" . $row['nip'] . "</td>
                        <td>" . $row['fakultas'] . "</td>
                        <td>" . $row['program_studi'] . "</td>
                        <td>" . $row['jenis_aktivitas'] . "</td>
                        <td>" . $row['status_aktivitas'] . "</td>
                        <td><a href='detail_aktivitas_admin.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Detail</a></td>
                    </tr>";
            }

            echo "</tbody>
                </table>
                </div>";
        } else {
            echo "<p class='text-danger'>Data Masih Kosong</p>";
        }
        ?>

        <!-- Data Pengabdian Dosen Section -->
        <h2 class="text-center mb-4">Data Pengabdian Dosen</h2>

        <?php
        // Query untuk mendapatkan data pengabdian dosen
        $query_pengabdian = "
            SELECT p.id, p.judul_kegiatan, p.tanggal_mulai, p.tanggal_selesai, p.lokasi, p.deskripsi_kegiatan, p.manfaat, p.tim_pelaksana, p.dokumentasi, u.nama_dosen, u.nip, u.fakultas, u.program_studi
            FROM pengabdian_dosen p
            JOIN users u ON p.dosen_id = u.id
            ORDER BY u.nama_dosen ASC
        ";

        $result_pengabdian = $conn->query($query_pengabdian);

        if ($result_pengabdian->num_rows > 0) {
            echo "<div class='table-responsive'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIP</th>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th>Judul Pengabdian</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Lokasi</th>
                                <th>Deskripsi Kegiatan</th>
                                <th>Manfaat</th>
                                <th>Tim Pelaksana</th>
                                <th>Dokumentasi</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>";

            // Menambahkan nomor urut dan menampilkan data
            $no = 1;
            while ($row = $result_pengabdian->fetch_assoc()) {
                echo "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . $row['nama_dosen'] . "</td>
                    <td>" . $row['nip'] . "</td>
                    <td>" . $row['fakultas'] . "</td>
                    <td>" . $row['program_studi'] . "</td>
                    <td>" . $row['judul_kegiatan'] . "</td>
                    <td>" . $row['tanggal_mulai'] . "</td>
                    <td>" . $row['tanggal_selesai'] . "</td>
                    <td>" . $row['lokasi'] . "</td>
                    <td>" . $row['deskripsi_kegiatan'] . "</td>
                    <td>" . $row['manfaat'] . "</td>
                    <td>" . $row['tim_pelaksana'] .
                "</td>
                        <td>";

                // Cek apakah ada bukti penunjang
                if (!empty($row['dokumentasi'])) {
                    echo "<a href='" . $row['dokumentasi'] . "' class='btn btn-info btn-sm' target='_blank'>Lihat Bukti</a>";
                } else {
                    echo "-";
                }

                echo "</td>
                    <td><a href='detail_pengabdian_admin.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Detail</a></td>
                </tr>";
            }

            echo "</tbody>
                </table>
                </div>";
        } else {
            echo "<p class='text-danger'>Data Masih Kosong</p>";
        }
        ?>

        <!-- Data Penunjang Pengabdian Dosen Section -->
        <h2 class="text-center mb-4">Data Penunjang Pengabdian Dosen</h2>

        <?php
        // Query untuk mendapatkan data penunjang pengabdian dosen
        $query_penunjang = "
            SELECT pp.id, pp.jenis_penunjang, pp.deskripsi, pp.tanggal_pengajuan, pp.jumlah_penunjang, pp.bukti_penunjang, u.nama_dosen, u.nip, u.fakultas, u.program_studi
            FROM penunjang_pengabdian pp
            JOIN users u ON pp.dosen_id = u.id
            ORDER BY u.nama_dosen ASC
        ";

        $result_penunjang = $conn->query($query_penunjang);

        if ($result_penunjang->num_rows > 0) {
            echo "<div class='table-responsive'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIP</th>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th>Jenis Penunjang</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Jumlah Penunjang</th>
                                <th>Bukti Penunjang</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>";

            // Menambahkan nomor urut dan menampilkan data
            $no = 1;
            while ($row = $result_penunjang->fetch_assoc()) {
                echo "<tr>
    <td>" . $no++ . "</td>
    <td>" . $row['nama_dosen'] . "</td>
    <td>" . $row['nip'] . "</td>
    <td>" . $row['fakultas'] . "</td>
    <td>" . $row['program_studi'] . "</td>
    <td>" . $row['jenis_penunjang'] . "</td>
    <td>" . $row['deskripsi'] . "</td>
    <td>" . $row['tanggal_pengajuan'] . "</td>
    <td>" . $row['jumlah_penunjang'] . "</td>
    <td>";

                // Cek apakah ada bukti penunjang
                if (!empty($row['bukti_penunjang'])) {
                    echo "<a href='" . $row['bukti_penunjang'] . "' class='btn btn-info btn-sm' target='_blank'>Lihat Bukti</a>";
                } else {
                    echo "-";
                }

                echo "</td>
    <td><a href='detail_penunjang_admin.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Detail</a></td>
</tr>";

            }

            echo "</tbody>
                </table>
                </div>";
        } else {
            echo "<p class='text-danger'>Data Masih Kosong</p>";
        }
        ?>

    </div>

    <!-- Link to Bootstrap JS (optional for modal or dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>