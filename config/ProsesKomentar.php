<?php 
session_start();
include '../config/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('You haven\'t logged in!');
    location.href='../index.php';
    </script>";
    exit();
}

$action = $_POST['action'];

if ($action === 'add') {
    $fotoid = $_POST['FotoID'];
    $isiKomentar = $_POST['IsiKomentar'];
    $userid = $_SESSION['userid'];
    
    // Insert komentar ke database
    $query = mysqli_prepare($koneksi, "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES (?, ?, ?, NOW())");
    mysqli_stmt_bind_param($query, 'iis', $fotoid, $userid, $isiKomentar);
    mysqli_stmt_execute($query);

    // Redirect ke halaman home setelah berhasil menambahkan komentar
    header("Location: ../admin/index.php");
    exit();
} elseif ($action === 'delete') {
    $komentarid = $_POST['KomentarID'];
    
    // Hapus komentar dari database
    $query = mysqli_prepare($koneksi, "DELETE FROM komentarfoto WHERE KomentarID = ?");
    mysqli_stmt_bind_param($query, 'i', $komentarid);
    mysqli_stmt_execute($query);

    // Redirect ke halaman home setelah berhasil menghapus komentar
    header("Location: ../admin/index.php");
    exit();
} else {
    // Redirect ke halaman home jika action tidak valid
    header("Location: ../admin/index.php");
    exit();
}
?>
