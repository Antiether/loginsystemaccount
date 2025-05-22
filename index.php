<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>Dashboard User</h1>
            <div class="user-info">
                <a href="logout.php" class="logout-btn" onclick="return confirm('Yakin ingin logout?')">Logout</a>
            </div>
        </div>

        <div class="actions">
            <a href="tambah.php" class="add-btn">+ Tambah User Baru</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT * FROM users ORDER BY id ASC";
                    $result = mysqli_query($conn, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Format tanggal jika ada kolom created_at
                            $created_date = isset($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : '-';
                            
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                    <td>{$created_date}</td>
                                    <td class='action-links'>
                                        <a href='edit.php?id={$row['id']}' class='edit-link'>Edit</a>
                                        <a href='hapus.php?id={$row['id']}' class='delete-link' 
                                           onclick=\"return confirm('Yakin ingin menghapus user {$row['username']}?')\">Hapus</a>
                                    </td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align: center; color: #666;'>Belum ada data user</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="stats">
            <p>Total User: <strong><?php echo mysqli_num_rows($result); ?></strong></p>
        </div>
    </div>
</body>
</html>