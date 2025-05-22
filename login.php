<?php
session_start();
include "koneksi.php";

// Jika sudah login, redirect ke index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        $data = mysqli_fetch_assoc($query);

        if ($data && password_verify($password, $data['password'])) {
            $_SESSION['username'] = $data['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Harap isi semua field!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" name="login">Login</button>
            </form>
            
            <p class="register-link">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>
        </div>
    </div>
</body>
</html>