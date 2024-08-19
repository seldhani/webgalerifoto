<?php 
session_start();
include 'koneksi.php';

if(isset($_POST['tambah'])) {
    $judulfoto = $_POST['JudulFoto'];
    $deskripsifoto = $_POST['DeskripsiFoto'];
    $tanggalunggah = date('Y-m-d'); // Format tanggal yang benar untuk SQL
    $albumid = $_POST['AlbumID'];
    $userid = $_SESSION['userid'];
    $foto = $_FILES['LokasiFile']['name'];
    $tmp = $_FILES['LokasiFile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namafoto = rand().'-'.$foto;

    // Pindahkan file yang diunggah ke direktori tujuan
    if(move_uploaded_file($tmp, $lokasi.$namafoto)) {

        // Debug: Check if userid is set
        if (!isset($userid)) {
            echo "<script>
            alert('UserID is not set in session');
            location.href='../index.php';
            </script>";
            exit();
        }

        // Insert new photo into database
        $sql = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID, UserID) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param('ssssss', $judulfoto, $deskripsifoto, $tanggalunggah, $namafoto, $albumid, $userid);

        if ($stmt->execute()) {
            echo "<script>
            alert('Photo saved');
            location.href='../admin/foto.php';
            </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>
        alert('Failed to upload photo');
        location.href='../admin/foto.php';
        </script>";
    }
}

if(isset($_POST['edit'])) {
    $fotoid = $_POST['FotoID'];
    $judulfoto = $_POST['JudulFoto'];
    $deskripsifoto = $_POST['DeskripsiFoto'];
    $tanggalunggah = date('Y-m-d'); // Format tanggal yang benar untuk SQL
    $albumid = $_POST['AlbumID'];
    $userid = $_SESSION['userid'];
    $foto = $_FILES['LokasiFile']['name'];
    $tmp = $_FILES['LokasiFile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namafoto = rand().'-'.$foto;
    
        // Debug: Check if userid is set
        if (!isset($userid)) {
            echo "<script>
            alert('UserID is not set in session');
            location.href='../index.php';
            </script>";
            exit();
        }
    
        // Update album yang ada
        $sql = "UPDATE foto SET JudulFoto = ?, DeskripsiFoto = ?, TanggalUnggah = ?, AlbumID = ? WHERE FotoID =?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param('sssii', $judulfoto, $deskripsifoto, $tanggalunggah, $albumid, $fotoid);
    
        if ($stmt->execute()) {
            echo "<script>
            alert('Photo edited');
            location.href='../admin/foto.php';
            </script>";
        }   else {
                $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE FotoID = ?");
                $data = mysqli_fetch_array($query);
                if (is_file('../assets/img/'.$data['LokasiFile'])) {
                    unlink('../assets/img/'.$data['LokasiFile']);
            }
            if(move_uploaded_file($tmp, $lokasi.$namafoto));
            $sql = "UPDATE foto SET JudulFoto = ?, DeskripsiFoto = ?, TanggalUnggah = ?, LokasiFile = ?, AlbumID = ? WHERE FotoID =?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('sssii', $judulfoto, $deskripsifoto, $tanggalunggah, $namafoto, $albumid, $fotoid);
        }
        if ($stmt->execute()) {
            echo "<script>
            alert('Photo edited');
            location.href='../admin/foto.php';
            </script>";
        }

    
    
        $stmt->close();

}

if(isset($_POST['hapus'])) {
    $fotoid = $_POST['FotoID'];

    // Hapus album dari database
    $sql = "DELETE FROM foto WHERE FotoID = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $fotoid);

    if ($stmt->execute()) {
        echo "<script>
        alert('Photo deleted');
        location.href='../admin/foto.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
