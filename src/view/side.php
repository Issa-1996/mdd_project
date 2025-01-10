<?php
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header('Location:  /mmd/index.php');
  exit();
} ?>
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
  <!-- Sidebar Start -->
  <div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
      <a href="accueil.php" class="navbar-brand mx-4 mb-3">
        <!-- <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>DarkPan</h3> -->
        <h3 class="text-primary">
          <!-- <i class="fa fa-user-edit me-2"></i> -->
          <img
            class="rounded-circle"
            src="../../assets/img/mmd.jpg"
            alt=""
            style="width: 40px; height: 40px" />
          <img src="" alt="" />
          MDD
        </h3>
      </a>
      <div class="d-flex align-items-center ms-4 mb-4">
        <div class="position-relative">
          <!-- <img
            class="rounded-circle"
            src="../../assets/img/user.jpg"
            alt=""
            style="width: 40px; height: 40px" /> -->
          <div
            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
        </div>
        <div class="ms-3">
          <h6 class="mb-0"><?= $_SESSION['prenom'] . " " . $_SESSION['nom'] ?></h6>
          <span><?= $_SESSION['role'] ?></span>
        </div>
      </div>
      <div class="navbar-nav w-100">
        <a href="accueil.php" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>ACCUEIL</a>
        <div class="nav-item dropdown">
          <a
            href="#"
            class="nav-link dropdown-toggle"
            data-bs-toggle="dropdown"><i class="fa fa-tachometer-alt me-2"></i>VENTES</a>
          <div class="dropdown-menu bg-transparent border-0">
            <a href="commande.php" class="nav-item nav-link">VENTE</a>
            <a href="client.php" class="nav-item nav-link">CLIENT</a>
            <a href="stock.php" class="nav-item nav-link">STOCK</a>
          </div>
          <div class="nav-item dropdown">
            <a
              href="#"
              class="nav-link dropdown-toggle"
              data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>PRODUITS</a>
            <div class="dropdown-menu bg-transparent border-0">
              <a href="produit.php" class="nav-item nav-link">PRODUIT</a>
              <a href="category.php" class="nav-item nav-link">CATEGORIE</a>
              <a href="fournisseur.php" class="nav-item nav-link">FOURNISSEUR</a>
            </div>
          </div>
        </div>
        <?php if($_SESSION['role'] == 'admin'){ ?>
        <a href="utilisateur.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>UTILISATEUR</a>
        <?php } ?>
    </nav>
  </div>
  <!-- Sidebar End -->

  <!-- <script src="../../assets/js/jquery-3.4.1.min.js"></script> -->
   <script src="../../assets/js/bootstrap.bundle.min.js"></script>
   <script src="../../assets/js/jquery-3.4.1.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> -->
  <script src="../../assets/lib/chart/chart.min.js"></script>
  <script src="../../assets/lib/easing/easing.min.js"></script>
  <script src="../../assets/lib/waypoints/waypoints.min.js"></script>
  <script src="../../assets/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="../../assets/lib/tempusdominus/js/moment.min.js"></script>
  <script src="../../assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
  <script src="../../assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

  <!-- Template Javascript -->
  <!-- <script src="../../assets/js/main.js"></script> -->
  <script>
    $(document).ready(function() {
      // Récupérer l'URL de la page actuelle
      var currentUrl = window.location.pathname;

      // Vérifier chaque lien de menu
      var isAnyActive = false; // Vérifie si un menu est actif
      $('.nav-item.nav-link').each(function() {
        var link = $(this).attr('href');
        // Si le lien correspond à l'URL actuelle
        if (currentUrl.includes(link)) {
          $(this).addClass('active'); // Ajouter la classe 'active'
          isAnyActive = true; // Indiquer qu'un menu est actif
        } else {
          $(this).removeClass('active'); // Supprimer 'active' des autres
        }
      });

      // Si aucun menu n'est actif, activer par défaut accueil.php
      if (!isAnyActive) {
        $('.nav-item.nav-link[href="accueil.php"]').addClass('active');
      }

      // Gestion du clic pour changer l'état actif
      $('.nav-item.nav-link').on('click', function() {
        $('.nav-item.nav-link').removeClass('active');
        $(this).addClass('active');
      });
    });
  </script>
</body>

</html>