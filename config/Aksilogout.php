<?php 
session_start();
session_destroy();

echo "<Script>
alert('Logout success');
location.href='../index.php';
</Script>";
?>