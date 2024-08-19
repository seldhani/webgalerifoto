<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Photo</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="index.php"><strong>Home</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
    
    </div>
   
  </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body bg-light">
                    <div class="text-center">
                        <h5>Register</h5>
                    </div>
                    <form action="config/Aksiregister.php" method="POST">
                        <label class="form-label">Username</label>
                        <input type="text" name="Username" class="form-control" placeholder="Adieu.png" required>
                        <label class="form-label">Password</label>
                        <input type="password" name="Password" class="form-control" placeholder="secret" required>
                        <label class="form-label">Email</label>
                        <input type="email" name="Email" class="form-control" placeholder="mygmail@gmail.com" required>
                        <label class="form-label">Full Name</label>
                        <input type="text" name="NamaLengkap" class="form-control" placeholder="Zero Bridgerton" required>
                        <label class="form-label">Address</label>
                        <input type="text" name="Alamat" class="form-control" placeholder="My house" required>
                        <div class="d-grid mt-2">
                            <button class="btn btn-primary" type="submit" name="send">Register</button>
                        </div>
                    </form>
                    <hr>
                    <p>Have an account? <a href="login.php">Login</a> here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; Selma Ramadhani</p>
</footer>

    <script type="text/javascript" src="assets/css/bootstrap.min.js"></script>
</body>
</html>