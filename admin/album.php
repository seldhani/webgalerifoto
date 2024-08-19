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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Photo</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
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

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card mt-2">
                <div class="card-header">Add Album</div>
                <div class="card-body">
                    <form action="../config/Aksialbum.php" method="POST">
                        <label class="form-label">Album Name</label>
                        <input type="text" name="NamaAlbum" class="form-control" required>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="Deskripsi" required></textarea>
                        <button type="submit" class="btn btn-primary mt-2" name="tambah">Add</button>
                    </form>
                </div>
            </div>
        </div>
            <div class="col-md-8">
                <div class="card mt-2">
                    <div class="card-header">Data Album</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Album Name</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $userid = $_SESSION['userid'];
                                $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                                while ($data = mysqli_fetch_array($sql)){
                                ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['NamaAlbum'] ?></td>
                                    <td><?php echo $data['Deskripsi'] ?></td>
                                    <td><?php echo $data['Tanggal'] ?></td>
                                    <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $data['AlbumID']; ?>">
                                      Edit
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="editModal<?php echo $data['AlbumID']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $data['AlbumID']; ?>" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo $data['AlbumID']; ?>">Edit Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                            <form action="../config/Aksialbum.php" method="POST">
                                              <input type="hidden" name="AlbumID" value="<?php echo $data['AlbumID']; ?>">
                                              <label class="form-label">Album Name</label>
                                              <input type="text" name="NamaAlbum" value="<?php echo $data['NamaAlbum']; ?>" class="form-control" required>
                                              <label class="form-label">Description</label>
                                              <textarea class="form-control" name="Deskripsi" required><?php echo $data['Deskripsi']; ?></textarea>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?php echo $data['AlbumID']; ?>">
                                      Delete
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="hapusModal<?php echo $data['AlbumID']; ?>" tabindex="-1" aria-labelledby="hapusModalLabel<?php echo $data['AlbumID']; ?>" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="hapusModalLabel<?php echo $data['AlbumID']; ?>">Delete Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                            <form action="../config/Aksialbum.php" method="POST">
                                              <input type="hidden" name="AlbumID" value="<?php echo $data['AlbumID']; ?>">
                                             Are you sure to delete <strong> <?php echo $data['NamaAlbum']?></strong>? Data deleted cannot be downloaded back 
                                          </div>
                                          <div class="modal-footer">
                                            <button type="submit" name="hapus" class="btn btn-outline-danger">Delete</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>


                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

<footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; Selma Ramadhani</p>
</footer>

<script type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
