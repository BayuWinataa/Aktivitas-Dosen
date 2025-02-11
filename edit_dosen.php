<?php
// File: edit_dosen.php
session_start();
if ($_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

require 'config.php';

$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_dosen = $_POST['nama_dosen'];
    $nip = $_POST['nip'];
    $fakultas = $_POST['fakultas'];
    $program_studi = $_POST['program_studi'];

    // Update data di database
    $stmt = $conn->prepare("UPDATE users SET nama_dosen = ?, nip = ?, fakultas = ?, program_studi = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nama_dosen, $nip, $fakultas, $program_studi, $id);

    if ($stmt->execute()) {
        // Redirect ke detail_dosen setelah berhasil update
        header("Location: detail_dosen.php");
        exit;
    } else {
        $message = "<div class='bg-red-500 text-white p-4 rounded mb-4'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Fetch existing data
$stmt = $conn->prepare("SELECT nama_dosen, nip, fakultas, program_studi FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nama_dosen, $nip, $fakultas, $program_studi);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

    <?php include('sidebar.php'); ?>

    <div class="w-[900px] mx-auto py-12 px-4">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Card Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-semibold text-blue-600">Edit Data Dosen</h2>
            </div>

            <!-- Display success or error message -->
            <?php if (!empty($message)) echo $message; ?>

            <!-- Form -->
            <form method="POST">
                <div class="space-y-6">
                    <!-- Nama Dosen -->
                    <div>
                        <label for="nama_dosen" class="block text-gray-700 font-medium">Nama Dosen:</label>
                        <input type="text" id="nama_dosen" name="nama_dosen"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo htmlspecialchars($nama_dosen ?? ''); ?>" required>
                    </div>

                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-gray-700 font-medium">NIP:</label>
                        <input type="text" id="nip" name="nip"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo htmlspecialchars($nip ?? ''); ?>" required>
                    </div>

                    <!-- Fakultas -->
                    <div>
                        <label for="fakultas" class="block text-gray-700 font-medium">Fakultas:</label>
                        <input type="text" id="fakultas" name="fakultas"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo htmlspecialchars($fakultas ?? ''); ?>" required>
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label for="program_studi" class="block text-gray-700 font-medium">Program Studi:</label>
                        <input type="text" id="program_studi" name="program_studi"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo htmlspecialchars($program_studi ?? ''); ?>" required>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between mt-6">
                        <button type="submit"
                            class="bg-blue-500 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-600 transition duration-200">Update
                            Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>