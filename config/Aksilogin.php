<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Menggunakan prepared statement untuk menghindari SQL injection
    $stmt = $koneksi->prepare("SELECT * FROM user WHERE Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Memverifikasi password
        if (password_verify($password, $data['Password'])) {
            $_SESSION['Username'] = $data['Username'];
            $_SESSION['userid'] = $data['UserID'];
            $_SESSION['status'] = 'login';
            echo "<script>
            alert('Login success');
            location.href='../admin/index.php';
            </script>";
        } else {
            echo "<script>
            alert('Username or password incorrect');
            location.href='../login.php';
            </script>";
        }
    } else {
        echo "<script>
        alert('Username or password incorrect');
        location.href='../login.php';
        </script>";
    }

    $stmt->close();
    $koneksi->close();
} else {
    echo "<script>
    alert('Failed');
    location.href='../login.php';
    </script>";
}
?>
