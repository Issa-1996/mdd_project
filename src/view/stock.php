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
  $_SESSION['search_error'] = "";
  if ($stocks == "AUCUNE RESULTAT TROUVÉE.") {
    $_SESSION['search_error'] = $stocks;
    $stocks = get_all_stock();
  }
} else {
  $_SESSION['search_error'] = "";
  $stocks = get_all_stock();
}


$add_stocks = get_all_produit_no_pagination();
$put_stocks = get_all_produit_no_pagination();
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

// Verfication Message de modification
if (isset($_GET['update_success'])) {
  $_SESSION['update_success'] = $_GET['update_success'];
} else {
  $_SESSION['update_success'] = '';
}

// Verfication Message de suppression
if (isset($_GET['delete_success'])) {
  $_SESSION['delete_success'] = $_GET['delete_success'];
} else {
  $_SESSION['delete_success'] = '';
}

// Verfication Message d'erreur suppression
if (isset($_GET['delete_error'])) {
  $_SESSION['delete_error'] = $_GET['delete_error'];
} else {
  $_SESSION['delete_error'] = '';
}
$openModal = isset($_GET['openModal']) && $_GET['openModal'] == 'put_categorie';
$openModal_delete = isset($_GET['openModal']) && $_GET['openModal'] == 'delete_stock';
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
            <div class="bg-secondary text-center rounded p-4">
              <?php
              if ($_SESSION['success']) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['success']; ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <!-- <div class="row" style="justify-content: center;">
                  <div class="col-md-6 alert alert-success" role="alert">
                    <?= $_SESSION['success']; ?>
                  </div>
                </div> -->
              <?php
              }
              if ($_SESSION['error_add']) { ?>
                <!-- <div class="row" style="justify-content: center;">
                  <div class="col-md-6 alert alert-danger" role="alert">
                    <?= $_SESSION['error_add']; ?>
                  </div>
                </div> -->
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_add']; ?><i class="fa fa-exclamation-circle"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['update_success']) { ?>
                <!-- <div class="row" style="justify-content: center;">
                  <div class="col-md-6 alert alert-warning" role="alert">
                    <?= $_SESSION['update_success']; ?>
                  </div>
                </div> -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['update_success']; ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['delete_success']) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_success']; ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <!-- <div class="row" style="justify-content: center;">
                  <div class="col-md-6 alert alert-danger" role="alert">
                    <?= $_SESSION['delete_success']; ?>
                  </div>
                </div> -->
              <?php } else if ($_SESSION['delete_error']) { ?>
                <!-- <div class="row" style="justify-content: center;">
                  <div class="col-md-6 alert alert-danger" role="alert">
                    <?= $_SESSION['delete_error']; ?>
                  </div>
                </div> -->
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_error']; ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } ?>
              <div class="d-flex align-items-center justify-content-between mb-4">
                <form class="d-flex" role="search" method="POST" action="stock.php" id="filterForm">
                  <input type="text" class="form-control bg-dark me-2" placeholder="Recherche" aria-label="Search" name="search" id="search" required>
                  <input type="submit" class="btn btn-success" value="Rechercher">
                </form>
                <h6 class="mb-0">LISTE DU STOCK</h6>
                <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo"><i class="fa fa-plus" aria-hidden="true"></i>Nouveau stock</button>
              </div>
              <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                  <thead>
                    <tr class="text-white">
                      <th scope="col">ID</th>
                      <th scope="col" style="width: 30%;">Nom Produit</th>
                      <th scope="col">Prix de vente</th>
                      <th scope="col">Categorie</th>
                      <th scope="col">Quantite</th>
                      <th scope="col">Image produit</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <?php
                  if (isset($_SESSION['search_error']) && $_SESSION['search_error'] != "") { ?>
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                      <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['search_error']; ?><i class="fa fa-exclamation-circle me-2"></i>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php }
                  if (isset($stocks) && ($stocks != "AUCUNE RESULTAT TROUVÉE.")) {
                    while ($stock = mysqli_fetch_array($stocks)) { ?>
                      <tbody>
                        <tr>
                          <td><?= $stock['id_stock'] ?></td>
                          <td><?= $stock['nom_produit'] ?></td>
                          <td><?= $stock['prix_vente'] ?></td>
                          <td><?= $stock['nom_categorie'] ?></td>
                          <td><?= $stock['quantite_disponible'] ?></td>
                          <td>
                            <img class="img-fluid transition scale-on-hover" style="height: 3rem; overflow: hidden;" src="<?= $stock['image_produit'] ?>" alt="<?= $stock['nom_produit'] ?>" width="80">
                          </td>
                          <td>
                            <a class="btn btn-sm btn-warning" href="../controller/stock.php?put_id_stock=<?= $stock['id_stock'] ?>">Ajuster</a>
                            <?php if ($_SESSION['role'] == 'admin') { ?>
                              <a class="btn btn-sm btn-primary" href="../controller/stock.php?delete_id_stock=<?= $stock['id_stock'] ?>">Retirer</a>
                            <?php } ?>
                          </td>
                        </tr>
                      </tbody>
                      <!-- Debut Confirmation de suppression catégorie -->
                      <div class="modal fade" id="delete_stock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <span class="modal-title fs-5" id="exampleModalLabel">CONFIRMATION DE SUPPRESSION</span>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body bg-secondary">
                              Êtes-vous sûr de vouloir continuer cette action ?
                            </div>
                            <div style="text-align: center; margin-bottom:2%">
                              <a type="button" class="btn btn-outline-danger m-2" data-bs-dismiss="modal">Fermer</a>
                              <a type="button" class="btn btn-outline-success" href="../controller/stock.php?delete_id_stock2=<?= $_GET['delete_id_stock'] ?>">Confirmer</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Fin Confirmation de suppression catégorie -->
                  <?php }
                  } ?>
                </table>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                  <div class="d-flex justify-content-end mt-3">
                    <ul class="pagination">
                      <?php if ($page > 1): ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?= $page - 1 ?>">Précédent</a>
                        </li>
                      <?php endif; ?>
                      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                      <?php endfor; ?>
                      <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?= $page + 1 ?>">Suivant</a>
                        </li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Form End -->

      <!-- Footer Start -->
      <?php include("footer.php"); ?>
      <!-- Footer End -->
    </div>
    <!-- Content End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
  </div>

  <!-- Début ajout nouveau stock -->
  <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">STOCK</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-2">
              <div style="text-align: center;">
                <h5 class="text -primary mb-4">ALIMENTER LE STOCK</h5>
              </div>
              <form action="../controller/stock.php" method="POST">
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <select id="produit_id" class="form-select" name="produit_id[]" multiple required="true">
                        <option disabled>CHOISIR DES PRODUITS</option>
                        <?php while ($stock = mysqli_fetch_array($add_stocks)) { ?>
                          <option value="<?= htmlspecialchars($stock['id_produit']) ?>"><?= htmlspecialchars($stock['nom_produit']) ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="number" class="form-control" name="quantite_disponible"
                        placeholder="Quantite disponible" required="true">
                      <label for="floatingInput">Quantite Disponible</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <select name="emplacement_produit" class="form-select" aria-label="Floating label select example" required="true">
                        <option selected disabled>INDIQUER L'EMPLACEMENT</option>
                        <option value="mmd_siege">BOUTIQUE SIEGE</option>
                        <option value="mmd_edk">BOUTIQUE EDK</option>
                        <option value="mmd_depot">DEPOT STOCK</option>
                        <option value="mmd_parcelle">DEPOT PARCELLE</option>
                        <option value="mmd_foire">DEPOT FOIRE</option>
                      </select>
                      <label for="floatingInput">Emplacement du produit</label>
                    </div>
                  </div>
                </div>
                <div style="text-align: center;">
                  <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                  <button type="submit" class="btn btn-outline-success m-2">ENREGISTRER</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin ajout nouveau stock -->

  <!-- Début modifier stock -->
  <div class="modal fade" id="put_categorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">STOCK</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-2">
              <div style="text-align: center;">
                <h5 class="text -primary mb-4">MODIFIER UN PRODUIT DU STOCK</h5>
              </div>
              <form action="../controller/stock.php" method="POST">
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="text" name="put_id_stock" value="<?= $_GET['put_id_stock'] ?>" hidden="true">
                      <select id="put_produit_id" class="form-select" name="put_produit_id" multiple required="true">
                        <option value="<?= $_GET['put_data_stock']['produit_id'] ?>" selected><?= $_GET['put_data_stock']['nom_produit'] ?></option>
                        <?php while ($stock = mysqli_fetch_array($put_stocks)) { ?>
                          <option value="<?= htmlspecialchars($stock['id_produit']) ?>"><?= htmlspecialchars($stock['nom_produit']) ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="number" class="form-control" name="put_quantite_disponible"
                        placeholder="Quantite disponible" required="true" value="<?= $_GET['put_data_stock']['quantite_disponible'] ?>">
                      <label for="floatingInput">Quantite Disponible</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <select name="put_emplacement_produit" class="form-select" aria-label="Floating label select example" required="true">
                        <option value="<?= $_GET['put_data_stock']['emplacement_produit'] ?>" selected><?= $_GET['put_data_stock']['emplacement_produit'] ?></option>
                        <option value="mmd_siege">BOUTIQUE SIEGE</option>
                        <option value="mmd_edk">BOUTIQUE EDK</option>
                        <option value="mmd_depot">DEPOT STOCK</option>
                      </select>
                      <label for="floatingInput">Emplacement du produit</label>
                    </div>
                  </div>
                </div>
                <div style="text-align: center;">
                  <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                  <button type="submit" class="btn btn-outline-success m-2">MODIFIER</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin modifier stock -->

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
      <?php if ($openModal): ?>
        var myModal = new bootstrap.Modal(document.getElementById('put_categorie'));
        myModal.show();
      <?php endif; ?>
    });

    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($openModal_delete): ?>
        var myModal = new bootstrap.Modal(document.getElementById('delete_stock'));
        myModal.show();
      <?php endif; ?>
    });
  </script>
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

    document.addEventListener('DOMContentLoaded', function() {
      const selectElement = document.querySelector('#put_produit_id');
      const choices = new Choices(selectElement, {
        removeItemButton: true, // Permet de supprimer les options sélectionnées
        placeholder: true,
        placeholderValue: 'CHOISIR UN PRODUIT', // Texte de placeholder
        searchPlaceholderValue: 'Rechercher...', // Texte pour la recherche
        maxItemCount: 100, // Lim ite des sélections, si nécessaire
        shouldSort: false // Garde les options dans l'ordre initial
      });
    });
  </script>

</body>

</html>