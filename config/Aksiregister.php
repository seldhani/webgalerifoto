<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $email = $_POST['Email'];
    $namalengkap = $_POST['NamaLengkap'];
    $alamat = $_POST['Alamat'];

    // Hash password sebelum menyimpannya ke database
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $koneksi->prepare("INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $username, $passwordHash, $email, $namalengkap, $alamat);

    if ($stmt->execute()) {
        echo "<script>
        alert('Registered');
        location.href='../login.php';
        </script>";
    } else {
        echo "<script>
        alert('Registration failed');
        location.href='../register.php';
        </script>";
    }

    $stmt->close();
    $koneksi->close();
} else {
    echo "<script>
    alert('Failed');
    location.href='../register.php';
    </script>";
}
?>
