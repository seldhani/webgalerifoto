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
                <div class="card-header">Add Photo</div>
                <div class="card-body">
                    <form action="../config/Aksifoto.php" method="POST" enctype="multipart/form-data">
                        <label class="form-label">Photo Name</label>
                        <input type="text" name="JudulFoto" class="form-control" required>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="DeskripsiFoto" required></textarea>
                        <label class="form-label">Album</label>
                        <select class="form-control" name="AlbumID" required>
                            <?php 
                            // Ambil album yang hanya milik user yang sedang login
                            $userid = $_SESSION['userid'];
                            $queryAlbum = mysqli_prepare($koneksi, "SELECT * FROM album WHERE userid = ?");
                            mysqli_stmt_bind_param($queryAlbum, 'i', $userid);
                            mysqli_stmt_execute($queryAlbum);
                            $resultAlbum = mysqli_stmt_get_result($queryAlbum);

                            while ($album = mysqli_fetch_array($resultAlbum)) {
                                echo '<option value="' . htmlspecialchars($album['AlbumID']) . '">' . htmlspecialchars($album['NamaAlbum']) . '</option>';
                            }
                            ?>
                        </select>
                        <label class="form-label">File</label>
                        <input type="file" class="form-control" name="LokasiFile" required>
                        <button type="submit" class="btn btn-primary mt-2" name="tambah">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mt-2">
                <div class="card-header">Data Photos</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Photo Title</th>
                                <th>Photo Description</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $userid = $_SESSION['userid'];
                            $sql = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid'");
                            while ($data = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><img src="../assets/img/<?php echo htmlspecialchars($data['LokasiFile'])?>" width="100"></td>
                                <td><?php echo htmlspecialchars($data['JudulFoto']) ?></td>
                                <td><?php echo htmlspecialchars($data['DeskripsiFoto']) ?></td>
                                <td><?php echo htmlspecialchars($data['TanggalUnggah']) ?></td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars($data['FotoID']); ?>">
                                      Edit
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="editModal<?php echo htmlspecialchars($data['FotoID']); ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo htmlspecialchars($data['FotoID']); ?>" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo htmlspecialchars($data['FotoID']); ?>">Edit Photo Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                            <form action="../config/Aksifoto.php" method="POST"  enctype="multipart/form-data">
                                              <input type="hidden" name="FotoID" value="<?php echo htmlspecialchars($data['FotoID']); ?>">
                                              <label class="form-label">Photo Title</label>
                                              <input type="text" name="JudulFoto" value="<?php echo htmlspecialchars($data['JudulFoto']); ?>" class="form-control" required>
                                              <label class="form-label">Photo Description</label>
                                              <textarea class="form-control" name="DeskripsiFoto" required><?php echo htmlspecialchars($data['DeskripsiFoto']); ?></textarea>

                                              <label class="form-label">Album</label>
                                              <select class="form-control" name="AlbumID">
                                                <?php 
                                                // Ambil album yang hanya milik user yang sedang login
                                                $queryAlbumEdit = mysqli_prepare($koneksi, "SELECT * FROM album WHERE userid = ?");
                                                mysqli_stmt_bind_param($queryAlbumEdit, 'i', $userid);
                                                mysqli_stmt_execute($queryAlbumEdit);
                                                $resultAlbumEdit = mysqli_stmt_get_result($queryAlbumEdit);

                                                while ($data_album = mysqli_fetch_array($resultAlbumEdit)) { ?>
                                                    <option 
                                                    <?php if ($data_album['AlbumID'] == $data['AlbumID']) { ?> 
                                                        selected="selected" 
                                                    <?php } ?>
                                                    value="<?php echo htmlspecialchars($data_album['AlbumID']); ?>">
                                                        <?php echo htmlspecialchars($data_album['NamaAlbum']); ?>
                                                    </option>
                                                <?php } ?>
                                              </select>

                                              <label class="form-label">Photo</label>
                                              <div class="row">
                                                  <div class="col-md-4">
                                                      <img src="../assets/img/<?php echo htmlspecialchars($data['LokasiFile'])?>" width="100">
                                                  </div>
                                                  <div class="col-md-8">
                                                      <label class="form-label">Change File</label>
                                                      <input type="file" class="form-control" name="LokasiFile">
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?php echo htmlspecialchars($data['FotoID']); ?>">
                                      Delete
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="hapusModal<?php echo htmlspecialchars($data['FotoID']); ?>" tabindex="-1" aria-labelledby="hapusModalLabel<?php echo htmlspecialchars($data['FotoID']); ?>" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="hapusModalLabel<?php echo htmlspecialchars($data['FotoID']); ?>">Delete Photo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                            <form action="../config/Aksifoto.php" method="POST">
                                              <input type="hidden" name="FotoID" value="<?php echo htmlspecialchars($data['FotoID']); ?>">
                                              Are you sure to delete <strong><?php echo htmlspecialchars($data['JudulFoto'])?></strong>? Data deleted cannot be downloaded back
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

<script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>
