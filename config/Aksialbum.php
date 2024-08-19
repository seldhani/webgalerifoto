<?php 
session_start();
include 'koneksi.php';

if(isset($_POST['tambah'])) {
    $namaalbum = $_POST['NamaAlbum'];
    $deskripsi = $_POST['Deskripsi'];
    $tanggal = date('Y-m-d'); // Correct date format for SQL
    $userid = $_SESSION['userid'];

    // Debug: Check if userid is set
    if (!isset($userid)) {
        echo "<script>
        alert('UserID is not set in session');
        location.href='../index.php';
        </script>";
        exit();
    }

    // Insert new album into database
    $sql = "INSERT INTO album (NamaAlbum, Deskripsi, Tanggal, UserID) VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('ssss', $namaalbum, $deskripsi, $tanggal, $userid);

    if ($stmt->execute()) {
        echo "<script>
        alert('Album saved');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Mengedit Album yang Ada
if(isset($_POST['edit'])) {
    $albumid = $_POST['AlbumID'];
    $namaalbum = $_POST['NamaAlbum'];
    $deskripsi = $_POST['Deskripsi'];
    $tanggal = date('Y-m-d'); // Correct date format for SQL
    $userid = $_SESSION['userid'];

    // Debug: Check if userid is set
    if (!isset($userid)) {
        echo "<script>
        alert('UserID is not set in session');
        location.href='../index.php';
        </script>";
        exit();
    }

    // Update album yang ada
    $sql = "UPDATE album SET NamaAlbum = ?, Deskripsi = ?, Tanggal = ? WHERE AlbumID = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('sssi', $namaalbum, $deskripsi, $tanggal, $albumid);

    if ($stmt->execute()) {
        echo "<script>
        alert('Album edited');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if(isset($_POST['hapus'])) {
    $albumid = $_POST['AlbumID'];

    // Hapus album dari database
    $sql = "DELETE FROM album WHERE AlbumID = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $albumid);

    if ($stmt->execute()) {
        echo "<script>
        alert('Album deleted');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>
