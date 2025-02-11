<?php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$dosen_id = $_SESSION['id'];  // ID dosen yang sedang login
$penunjang_id = $_GET['id'];  // ID penunjang yang ingin dihapus

// Pastikan ID penunjang ada dan milik dosen yang sedang login
$stmt = $conn->prepare("SELECT id FROM penunjang_pengabdian WHERE dosen_id = ? AND id = ?");
$stmt->bind_param("ii", $dosen_id, $penunjang_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Jika penunjang ditemukan, hapus penunjang tersebut
    $stmt = $conn->prepare("DELETE FROM penunjang_pengabdian WHERE dosen_id = ? AND id = ?");
    $stmt->bind_param("ii", $dosen_id, $penunjang_id);

    if ($stmt->execute()) {
        // Penghapusan berhasil
        $_SESSION['message'] = "Penunjang pengabdian berhasil dihapus.";
    } else {
        // Gagal menghapus
        $_SESSION['message'] = "Gagal menghapus penunjang pengabdian.";
    }
} else {
    // Penunjang tidak ditemukan atau tidak milik dosen yang login
    $_SESSION['message'] = "Penunjang pengabdian tidak ditemukan atau Anda tidak memiliki akses.";
}

$stmt->close();
$conn->close();

// Redirect kembali ke daftar penunjang
header("Location: lihat_penunjang.php");
exit;