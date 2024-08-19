<?php
session_start();
include 'koneksi.php'; // Include your database connection file

$fotoid = $_GET['FotoID'];
$userid = $_SESSION['userid'];

// Check if the like exists
$ceklike = mysqli_prepare($koneksi, "SELECT LikeID FROM likefoto WHERE FotoID = ? AND UserID = ?");
mysqli_stmt_bind_param($ceklike, 'ii', $fotoid, $userid);
mysqli_stmt_execute($ceklike);
$ceklike_result = mysqli_stmt_get_result($ceklike);

if (mysqli_num_rows($ceklike_result) > 0) {
    // Like exists, delete it
    $row = mysqli_fetch_array($ceklike_result);
    $likeid = $row['LikeID'];

    $delete_like = mysqli_prepare($koneksi, "DELETE FROM likefoto WHERE LikeID = ?");
    mysqli_stmt_bind_param($delete_like, 'i', $likeid);
    if (mysqli_stmt_execute($delete_like)) {
        echo "<script>
        alert('Like removed!');
        location.href='../admin/index.php';
        </script>";
    } else {
        echo "Error deleting like: " . mysqli_stmt_error($delete_like);
    }
} else {
    // Like does not exist, add it
    $tanggallike = date('Y-m-d');
    $insert_like = mysqli_prepare($koneksi, "INSERT INTO likefoto (FotoID, UserID, TanggalLike) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insert_like, 'iis', $fotoid, $userid, $tanggallike);
    if (mysqli_stmt_execute($insert_like)) {
        echo "<script>
        alert('Liked!');
        location.href='../admin/index.php';
        </script>";
    } else {
        echo "Error inserting like: " . mysqli_stmt_error($insert_like);
    }
}

// Close statements and connection
mysqli_stmt_close($ceklike);
mysqli_stmt_close($delete_like);
mysqli_stmt_close($insert_like);
$koneksi->close();
?>
