<?php
session_start();
// Désactiver le cache du navigateur
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header('Location:  /mmd/index.php');
  exit();
}
require_once('../controller/fonction.php');

// Filtre de recherche
if (isset($_POST['search'])) {
  $stocks = get_search_stock($_POST['search']);
} else {
  $stocks = get_all_stock();
}
$liste_produit_stock = get_all_stock_no_pagination();
get_pagination('stock');
//Verification message success d'ajout
if (isset($_GET['success'])) {
  $_SESSION['success'] = $_GET['success'];
} else {
  $_SESSION['success'] = '';
}
//Verification message erreur d'ajout
if (isset($_GET['error_add'])) {
  $_SESSION['error_add'] = $_GET['error_add'];
} else {
  $_SESSION['error_add'] = '';
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
  <link href="../../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
  <link
    href="../../assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css"
    rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="../../assets/css/style.css" rel="stylesheet" />
  <style>
    .scale-on-hover:hover {
      transform: scale(3.3);
      /* Agrandit légèrement l'image */
    }

    .scale-on-hover {
      transition: transform 0.3s ease;
      /* Animation fluide */
    }

    .choices__inner {
      background-color: black !important;
    }

    .choices__input {
      background-color: black !important;
    }

    .choices__list {
      background-color: black !important;
    }
  </style>
  <!-- CSS de Choices.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

  <!-- JavaScript de Choices.js -->
  <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
</head>

<body>
  <!-- Contenu Dynamique -->
  <div id="main-content">
  </div>
  <div id="main-content" class="container-fluid position-relative d-flex p-0">
    <!-- Sidebar Start -->
    <?php include("side.php"); ?>
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
      <!-- Navbar Start -->
      <?php include("nav.php"); ?>
      <!-- Navbar End -->

      <!-- Form Start -->
      <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
          <!-- Recent Sales Start -->
          <div class="container-fluid pt-4 px-4">
            <?php
            if ($_SESSION['success']) { ?>
              <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['success'] ?><i class="fa fa-exclamation-circle me-2"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php
            }
            if ($_SESSION['error_add']) { ?>
              <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_add']; ?><i class="fa fa-exclamation-circle me-2"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php } ?>
            <?php if (empty($_GET['put_data_commande'])) { ?>
              <div class="bg-secondary text-center rounded p-4">
                <h4>VEUILLER CHOISIR LES PRODUITS</h4>
                <form action="../controller/commande.php" method="POST" id="myForm">
                  <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                      <div class="form-floating mb-3">
                        <input type="text" name="id_commande" value="<?= $_GET['id_commande']; ?>" hidden>
                        <select id="produit_id" class="form-select" name="produit_id[]" multiple required>
                          <option disabled>CHOISIR DES PRODUITS</option>
                          <?php while ($produit_stock = mysqli_fetch_array($liste_produit_stock)) {
                            if ($produit_stock['quantite_disponible'] > 0) { ?>
                              <option value="<?= htmlspecialchars($produit_stock['id_stock']) ?>"><?= htmlspecialchars($produit_stock['nom_produit']) ?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div style="text-align: center;">
                    <button type="button" class="btn btn-outline-success m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo">SUIVANT</button>
                  </div>
                  <!-- DEBUT MODAL CONFIRMATION DE CONTINUER -->
                  <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <span class="modal-title fs-5" id="exampleModalLabel">CONFIRMATION</span>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <span id="error-message" style="color: red;"></span>
                        <div class="modal-body bg-secondary">
                          VOUS ETES DE VOULOIR CONTINUER ?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">FERMER</button>
                          <button type="submit" class="btn btn-primary">CONFIRMER</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin MODAL CONFIRMATION DE CONTINUER -->
                </form>
              </div>
            <?php } else { ?>
              <div class="bg-secondary text-center rounded p-4">
                <h2>MODIFIER LIGNE DE COMMANDE (PARTIE 2)</h2>
                <form action="../controller/commande.php" method="POST" id="myForm">
                  <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                      <div class="form-floating mb-3">
                        <input type="text" name="put_id_commande" value="<?= $_GET['put_id_commande']; ?>" hidden>
                        <select id="produit_id" class="form-select" name="produit_id[]" multiple required>
                          <option disabled>CHOISIR DES PRODUITS</option>
                          <?php
                          $data = $_GET['put_data_commande'];
                          for ($i = 0; $i < count($data); $i++) { ?>
                          <option value="<?= $data[$i]['id_ligne_commande'] ?>" selected><?= $data[$i]['nom_produit'] ?></option>
                          <?php } ?>
                          <?php while ($produit_stock = mysqli_fetch_array($liste_produit_stock)) {
                            if ($produit_stock['quantite_disponible'] > 0) { ?>
                              <option value="<?= $produit_stock['id_stock'] ?>"><?= htmlspecialchars($produit_stock['nom_produit']) ?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div style="text-align: center;">
                    <button type="button" class="btn btn-outline-success m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo">SUIVANT</button>
                  </div>
                  <!-- DEBUT MODAL CONFIRMATION DE CONTINUER -->
                  <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <span class="modal-title fs-5" id="exampleModalLabel">CONFIRMATION</span>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <span id="error-message" style="color: red;"></span>
                        <div class="modal-body bg-secondary">
                          VOUS ETES DE VOULOIR CONTINUER ?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">FERMER</button>
                          <button type="submit" class="btn btn-primary">CONFIRMER</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin MODAL CONFIRMATION DE CONTINUER -->
                </form>
              </div>
            <?php } ?>
          </div>
        </div>
        <!-- Footer Start -->
        <?php include("footer.php"); ?>
        <!-- Footer End -->
      </div>
      <!-- Content End -->

      <!-- Back to Top -->
      <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
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
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.querySelector('#produit_id');
        const choices = new Choices(selectElement, {
          removeItemButton: true, // Permet de supprimer les options sélectionnées
          placeholder: true,
          placeholderValue: 'CHOISIR UN PRODUIT', // Texte de placeholder
          searchPlaceholderValue: 'Rechercher...', // Texte pour la recherche
          maxItemCount: 100, // Lim ite des sélections, si nécessaire
          shouldSort: false // Garde les options dans l'ordre initial
        });
      });
      const form = document.getElementById('myForm');
      const phoneInput = document.getElementById('produit_id');
      const errorMessage = document.getElementById('error-message');

      phoneInput.addEventListener('invalid', function(event) {
        event.preventDefault(); // Empêche le message par défaut
        errorMessage.textContent = "VEULLER CHOISIR AU MOIN UN PRODUIT.";
      });

      phoneInput.addEventListener('input', function() {
        errorMessage.textContent = ""; // Supprime l'erreur lorsque l'utilisateur tape
      });

      form.addEventListener('submit', function(event) {
        if (!phoneInput.checkValidity()) {
          event.preventDefault();
        }
      });
    </script>

</body>

</html>