<?php
// File: index.php
session_start();

// Check if user is already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'dosen') {
        $id = $_SESSION['id'];
        header("Location: dosen_dashboard.php?id=$id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
</head>

<body>
    <h1>Welcome to the User System</h1>
    <p><a href="register.php">Register</a></p>
    <p><a href="login.php">Login</a></p>
</body>

</html>