<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login
$penelitian_id = $_GET['id'];  // ID penelitian yang ingin dihapus

// Query untuk menghapus penelitian berdasarkan ID
$stmt = $conn->prepare("DELETE FROM penelitian WHERE dosen_id = ? AND id = ?");
$stmt->bind_param("ii", $dosen_id, $penelitian_id);

if ($stmt->execute()) {
    // Redirect ke daftar penelitian setelah berhasil menghapus
    header("Location: lihat_penelitian.php?status=success");
    exit;  // Pastikan untuk menghentikan eksekusi lebih lanjut
} else {
    echo "<p>Error: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();