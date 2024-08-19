<?php 
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('You haven\'t logged in!');
    location.href='../index.php';
    </script>";
    exit();
}

// Mengambil UserID dari sesi
$userid = $_SESSION['userid'];
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
    <div class="row">
        <?php 
        // Query untuk mendapatkan semua foto dari semua user, termasuk Username
        $query = mysqli_prepare($koneksi, "
            SELECT foto.*, user.Username 
            FROM foto 
            JOIN user ON foto.UserID = user.UserID
        ");
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);

        while($data = mysqli_fetch_array($result)) { ?>
            <div class="col-md-3 mt-2">
                <a type="button" data-bs-toggle="modal" data-bs-target="#komentar<?php echo $data['FotoID']?>">
                    <div class="card">
                        <img src="../assets/img/<?php echo htmlspecialchars($data['LokasiFile'])?>" class="card-img-top" title="<?php echo htmlspecialchars($data['JudulFoto'])?>" style="height:12rem;" alt="">
                        <div class="card-footer text-center">
                            <?php 
                            $fotoid = $data['FotoID'];
                            
                            // Query untuk mengecek apakah user sudah like foto ini
                            $ceklike = mysqli_prepare($koneksi, "SELECT * FROM likefoto WHERE FotoID = ? AND UserID = ?");
                            mysqli_stmt_bind_param($ceklike, 'ii', $fotoid, $userid);
                            mysqli_stmt_execute($ceklike);
                            $resultlike = mysqli_stmt_get_result($ceklike);

                            if (mysqli_num_rows($resultlike) > 0) { ?>
                                <a href="../config/Proseslike.php?FotoID=<?php echo $fotoid ?>&action=unlike" class="text-danger">
                                    <i class="fa fa-heart"></i>
                            <?php } else { ?>
                                    <a href="../config/Proseslike.php?FotoID=<?php echo $fotoid ?>&action=like">
                                        <i class="fa-regular fa-heart"></i>
                            <?php } 

                            // Query untuk menghitung total like pada foto
                            $like = mysqli_prepare($koneksi, "SELECT COUNT(*) AS totalLikes FROM likefoto WHERE FotoID = ?");
                            mysqli_stmt_bind_param($like, 'i', $fotoid);
                            mysqli_stmt_execute($like);
                            $resultlike = mysqli_stmt_get_result($like);
                            $likeData = mysqli_fetch_array($resultlike);
                            echo htmlspecialchars($likeData['totalLikes']) . ' Likes';
                            ?>
                            </a>
                            <a href="#"><i class="fa-regular fa-comment"></i>10 Komentar</a>
                        </div>
                    </div>
                </a>

                <!-- Modal untuk foto dan komentar -->
                <div class="modal fade" id="komentar<?php echo $data['FotoID']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-8">
                            <img src="../assets/img/<?php echo htmlspecialchars($data['LokasiFile'])?>" class="img-fluid" title="<?php echo htmlspecialchars($data['JudulFoto'])?>" alt="">  
                          </div>
                          <div class="col-md-4">
                            <div class="m-2">
                              <div class="overflow-auto">
                                <div class="sticky-top">
                                  <strong><?php echo htmlspecialchars($data['JudulFoto'])?></strong>
                                  <span class="badge bg-secondary"><?php echo htmlspecialchars($data['Username'])?></span>
                                  <span class="badge bg-secondary"><?php echo htmlspecialchars($data['TanggalUnggah'])?></span>
                                  <span class="badge bg-secondary"><?php echo htmlspecialchars($data['AlbumID'])?></span>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Daftar Komentar -->
                            <div class="mt-3">
                              <?php 
                              $fotoid = $data['FotoID'];
                              $queryKomentar = mysqli_prepare($koneksi, "SELECT komentarfoto.*, user.Username FROM komentarfoto JOIN user ON komentarfoto.UserID = user.UserID WHERE FotoID = ? ORDER BY TanggalKomentar DESC");
                              mysqli_stmt_bind_param($queryKomentar, 'i', $fotoid);
                              mysqli_stmt_execute($queryKomentar);
                              $resultKomentar = mysqli_stmt_get_result($queryKomentar);

                              while($komentar = mysqli_fetch_array($resultKomentar)) { ?>
                                <div class="card mb-2">
                                  <div class="card-body">
                                    <p><?php echo htmlspecialchars($komentar['IsiKomentar']); ?></p>
                                    <small class="text-muted">By <?php echo htmlspecialchars($komentar['Username']); ?> on <?php echo htmlspecialchars($komentar['TanggalKomentar']); ?></small>
                                  </div>
                                </div>
                              <?php } ?>
                            </div>

                            <!-- Form Tambah Komentar -->
                            <form action="../config/ProsesKomentar.php" method="POST" class="mt-3">
                                <input type="hidden" name="FotoID" value="<?php echo $fotoid; ?>">
                                <input type="hidden" name="action" value="add">
                                <textarea name="IsiKomentar" class="form-control" rows="2" placeholder="Add a comment..." required></textarea>
                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
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
