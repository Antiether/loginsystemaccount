<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Validasi ID user yang ingin diedit
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Ambil data user berdasarkan ID
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    echo "User tidak ditemukan.";
    exit();
}

$user = mysqli_fetch_assoc($result);

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    $update = "UPDATE users SET username = '$username' WHERE id = $id";
    if (mysqli_query($conn, $update)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal mengupdate user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>Edit User</h1>
            <div class="header-top">
                <a href="logout.php" class="logout-btn" onclick="return confirm('Yakin ingin logout?')">Logout</a>
            </div>
        </div>

        <div class="form-section">
            <form method="post" class="form-edit">
                <label for="username">Username Baru:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <div class="form-buttons">
                    <button type="submit" class="save-btn">Simpan</button>
                    <a href="index.php" class="cancel-btn">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>