<?php 
session_start();
include '../config/koneksi.php'; // Include your database connection file

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('You haven\'t logged in!');
    location.href='../index.php';
    </script>";
    exit();
}

// Mengambil UserID dari sesi
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
} else {
    echo "<script>
    alert('User ID not found. Please log in again.');
    location.href='../index.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Photo</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="index.php"><strong>Home</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-2" id="navbarNav">
      <div class="navbar-nav me-auto">
        <a href="home.php" class="nav-link">My Gallery</a>
        <a href="album.php" class="nav-link">Album</a>
        <a href="foto.php" class="nav-link">Photos</a>
      </div>
    </div>
    <a href="../config/Aksilogout.php" class="btn btn-outline-danger m-1">Logout</a>
  </div>
</nav>

<div class="container mt-3">

<!-- Bagian Album -->
Album: 
<?php 
$albumQuery = mysqli_prepare($koneksi, "SELECT * FROM album WHERE UserID = ?");
mysqli_stmt_bind_param($albumQuery, 'i', $userid);
mysqli_stmt_execute($albumQuery);
$albumResult = mysqli_stmt_get_result($albumQuery);

while($row = mysqli_fetch_array($albumResult)) { ?>
    <a href="home.php?AlbumID=<?php echo htmlspecialchars($row['AlbumID'])?>" class="btn btn-outline-primary">
        <?php echo htmlspecialchars($row['NamaAlbum'])?>
    </a>
<?php } ?>

<!-- Bagian Foto -->
<div class="row">
    <?php 
    // Periksa apakah AlbumID disediakan di URL
    if (isset($_GET['AlbumID'])) {
        $albumid = $_GET['AlbumID'];
        // Jika AlbumID disediakan, ambil foto dari album tersebut
        $fotoQuery = mysqli_prepare($koneksi, "SELECT * FROM foto WHERE UserID = ? AND AlbumID = ?");
        mysqli_stmt_bind_param($fotoQuery, 'ii', $userid, $albumid);
    } else {
        // Jika tidak ada AlbumID, ambil semua foto dari semua album milik user
        $fotoQuery = mysqli_prepare($koneksi, "SELECT * FROM foto WHERE UserID = ?");
        mysqli_stmt_bind_param($fotoQuery, 'i', $userid);
    }

    mysqli_stmt_execute($fotoQuery);
    $fotoResult = mysqli_stmt_get_result($fotoQuery);

    while($data = mysqli_fetch_array($fotoResult)){ ?>
        <div class="col-md-3 mt-2">
            <div class="card">
                <img src="../assets/img/<?php echo htmlspecialchars($data['LokasiFile'])?>" class="card-img-top" title="<?php echo htmlspecialchars($data['JudulFoto'])?>" style="height:12rem;" alt="">
                <div class="card-footer text-center">
                    <?php 
                    $fotoid = $data['FotoID'];
                    
                    // Query untuk mengecek apakah user sudah like foto ini
                    $ceklikeQuery = mysqli_prepare($koneksi, "SELECT * FROM likefoto WHERE FotoID = ? AND UserID = ?");
                    mysqli_stmt_bind_param($ceklikeQuery, 'ii', $fotoid, $userid);
                    mysqli_stmt_execute($ceklikeQuery);
                    $resultlike = mysqli_stmt_get_result($ceklikeQuery);

                    if (mysqli_num_rows($resultlike) > 0) { ?>
                        <a href="../config/Proseslike.php?FotoID=<?php echo $fotoid ?>&action=unlike" class="text-danger">
                            <i class="fa fa-heart"></i>
                    <?php } else { ?>
                            <a href="../config/Proseslike.php?FotoID=<?php echo $fotoid ?>&action=like">
                                <i class="fa-regular fa-heart"></i>
                    <?php } 

                    // Query untuk menghitung total like pada foto
                    $likeQuery = mysqli_prepare($koneksi, "SELECT COUNT(*) AS totalLikes FROM likefoto WHERE FotoID = ?");
                    mysqli_stmt_bind_param($likeQuery, 'i', $fotoid);
                    mysqli_stmt_execute($likeQuery);
                    $resultlike = mysqli_stmt_get_result($likeQuery);
                    $likeData = mysqli_fetch_array($resultlike);
                    echo htmlspecialchars($likeData['totalLikes']) . ' Likes';
                    ?>
                    </a>
                    <a href="#"><i class="fa-regular fa-comment"></i>10 Komentar</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

</div>

<footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; Selma Ramadhani</p>
</footer>

<script type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
