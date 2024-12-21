<?php
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header('Location:  /mmd/index.php');
  exit();
}?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>mmd</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="keywords" />
  <meta content="" name="description" />

  <!-- Favicon -->
  <link href="../../assets/img/favicon.png" rel="icon" />

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
    href="../../assets/lib/owlcarousel/assets/owl.carousel.min.css"
    rel="stylesheet" />
  <link
    href="../../assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css"
    rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="../../assets/css/style.css" rel="stylesheet" />
</head>

<body>
  <nav
    class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
    <a href="accueil.php" class="navbar-brand d-flex d-lg-none me-4">
      <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
      <i class="fa fa-bars"></i>
    </a>
    <!-- <form class="d-none d-md-flex ms-4">
      <input
        class="form-control bg-dark border-0"
        type="search"
        placeholder="Recherche" />
    </form> -->
    <div class="navbar-nav align-items-center ms-auto">

      <div class="nav-item dropdown">
        <a
          href="#"
          class="nav-link dropdown-toggle"
          data-bs-toggle="dropdown">
          <img
            class="rounded-circle me-lg-2"
            src="../../assets/img/user.jpg"
            alt=""
            style="width: 40px; height: 40px" />
          <span class="d-none d-lg-inline-flex"><?= $_SESSION['prenom'] . " " . $_SESSION['nom'] ?></span>
        </a>
        <div
          class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
          <a href="#" class="dropdown-item">Mon Profil</a>
          <a href="#" class="dropdown-item">Paramétre</a>
          <a href="../controller/deconnexion.php" class="dropdown-item">Déconnexion</a>
        </div>
      </div>
    </div>
  </nav>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/lib/chart/chart.min.js"></script>
  <script src="../../assets/lib/easing/easing.min.js"></script>
  <script src="../../assets/lib/waypoints/waypoints.min.js"></script>
  <script src="../../assets/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="../../assets/lib/tempusdominus/js/moment.min.js"></script>
  <script src="../../assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
  <script src="../../assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

  <!-- Template Javascript -->
  <script src="../../assets/js/main.js"></script>
</body>

</html>