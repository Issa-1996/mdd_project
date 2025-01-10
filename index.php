<?php
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
}
if (isset($_GET['error'])) {
  $_SESSION['error'] = $_GET['error'];
} else {
  $_SESSION['error'] = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>mmd</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="keywords" />
  <meta content="" name="description" />

  <!-- Favicon -->
  <link href="assets/img/favicon.png" rel="icon" />

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
    rel="stylesheet" />

  <!-- Icon Font Stylesheet -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
    rel="stylesheet" />

  <!-- Libraries Stylesheet -->
  <link
    href="assets/lib/owlcarousel/assets/owl.carousel.min.css"
    rel="stylesheet" />
  <link
    href="assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css"
    rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
  <div class="container-fluid position-relative d-flex p-0">

    <!-- Sign In Start -->
    <div class="container-fluid">
      <div
        class="row h-100 align-items-center justify-content-center"
        style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
          <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
            <div
              class="d-flex align-items-center justify-content-between mb-3">
              <a href="#" class="">
                <h3 class="text-primary">
                  <img src="assets/img/mmd-removebg-preview.png" width="70" alt="">
                </h3>
              </a><br>
              <h3>CONNEXION</h3>
            </div> 
            <?php
                    if ($_SESSION['error']) { ?>
              <div class="alert alert-primary" role="alert" style="font-size: 12px;">
                <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?><i class="fa fa-exclamation-circle me-2"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php
                    } ?>
            <form action="src/controller/connexion.php" method="post">
              <div class="form-floating mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="login"
                  placeholder="name@example.com"
                  name="login"
                  required />
                <label for="login">Identifiant</label>
              </div>
              <div class="form-floating mb-4">
                <input
                  type="password"
                  class="form-control"
                  id="mot_de_passe"
                  placeholder="Password"
                  name="mot_de_passe"
                  required />
                <label for="mot_de_passe">Mot de passe</label>
              </div>
              <div
                class="d-flex align-items-center justify-content-between mb-4"></div>
              <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                Connexion
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/lib/chart/chart.min.js"></script>
  <script src="assets/lib/easing/easing.min.js"></script>
  <script src="assets/lib/waypoints/waypoints.min.js"></script>
  <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="assets/lib/tempusdominus/js/moment.min.js"></script>
  <script src="assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
  <script src="assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

  <!-- Template Javascript -->
  <script src="assets/js/main.js"></script>
</body>

</html>