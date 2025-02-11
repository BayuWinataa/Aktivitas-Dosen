<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login
$pengabdian_id = $_GET['id'];  // ID pengabdian yang ingin dihapus

// Pastikan ID pengabdian ada dan milik dosen yang sedang login
$stmt = $conn->prepare("SELECT id FROM pengabdian_dosen WHERE dosen_id = ? AND id = ?");
$stmt->bind_param("ii", $dosen_id, $pengabdian_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Jika pengabdian ditemukan, hapus pengabdian tersebut
    $stmt = $conn->prepare("DELETE FROM pengabdian_dosen WHERE dosen_id = ? AND id = ?");
    $stmt->bind_param("ii", $dosen_id, $pengabdian_id);

    if ($stmt->execute()) {
        // Penghapusan berhasil
        $_SESSION['message'] = "Pengabdian berhasil dihapus.";
    } else {
        // Gagal menghapus
        $_SESSION['message'] = "Gagal menghapus pengabdian.";
    }
} else {
    // Pengabdian tidak ditemukan atau tidak milik dosen yang login
    $_SESSION['message'] = "Pengabdian tidak ditemukan atau Anda tidak memiliki akses.";
}

$stmt->close();
$conn->close();

// Redirect kembali ke daftar pengabdian
header("Location: lihat_pengabdian.php");
exit;