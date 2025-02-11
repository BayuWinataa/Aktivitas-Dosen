<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$aktivitas_id = $_GET['id'] ?? null;

if ($aktivitas_id) {
    // Query untuk menghapus aktivitas dosen
    $stmt = $conn->prepare("DELETE FROM aktivitas_dosen WHERE id = ? AND dosen_id = ?");
    $stmt->bind_param("ii", $aktivitas_id, $_SESSION['id']);

    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman lihat_aktivitas.php
        echo "<script>
                alert('Aktivitas berhasil dihapus.');
                window.location.href = 'lihat_aktivitas.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus aktivitas.');
                window.location.href = 'lihat_aktivitas.php';
              </script>";
    }

    $stmt->close();
}

$conn->close();