<?php
session_start();
include "koneksi.php";

// Jika sudah login, redirect ke index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif (strlen($username) < 3) {
        $error = "Username minimal 3 karakter!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $check_user = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password dan simpan ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Daftar Akun</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    <small>Minimal 3 karakter</small>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                    <small>Minimal 6 karakter</small>
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="register">Daftar</button>
            </form>
            
            <p class="register-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>