<?php 

$hostname = 'localhost';
$userdb = 'root';
$passdb = '';
$namedb = 'webgalerifoto';
$koneksi = mysqli_connect($hostname,$userdb,$passdb,$namedb);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}
?>