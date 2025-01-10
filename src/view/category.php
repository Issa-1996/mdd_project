<?php
session_start();
require_once('../controller/fonction.php');
// Désactiver le cache du navigateur
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header('Location:  /mmd/index.php');
  exit();
}
// Filtre de recherche
if (isset($_POST['search'])) {
  $categories = get_search_categorie($_POST['search']);
  $_SESSION['search_error'] = "";
  if ($categories == "AUCUNE RESULTAT TROUVÉE.") {
    $_SESSION['search_error'] = $categories;
    $categories = get_all_category();
  }
} else {
  $_SESSION['search_error'] = "";
  $categories = get_all_category();
}


get_pagination('categorie');
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
$openModal_delete = isset($_GET['openModal']) && $_GET['openModal'] == 'delete_categorie';
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
</head>

<body>
  <!-- Contenu Dynamique -->
  <div id="main-content">
  </div>
  <div class="container-fluid position-relative d-flex p-0">

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
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['success'] ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php
              }
              if ($_SESSION['error_add']) { ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_add'] ?> <i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['update_success']) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['update_success'] ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['delete_success']) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_success'] ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['delete_error']) { ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_error'] ?> <i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } ?>
              <div class="d-flex align-items-center justify-content-between mb-4">
                <form class="d-flex" role="search" method="POST" action="category.php" id="filterForm">
                  <input type="text" class="form-control bg-dark me-2" placeholder="Recherche" aria-label="Search" name="search" id="search" required>
                  <input type="submit" class="btn btn-success" value="Rechercher">
                </form>
                <h6 class="mb-0">LISTE CATEGORIES DES PRODUITS</h6>
                <!-- <a href="">Tout afficher</a> -->
                <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo"><i class="fa fa-plus" aria-hidden="true"></i>Nouveau Catégorie</button>
              </div>
              <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                  <thead>
                    <tr class="text-white">
                      <!-- <th scope="col"><input class="form-check-input" type="checkbox"></th> -->
                      <!-- <th scope="col">ID</th> -->
                      <th scope="col">Date</th>
                      <th scope="col">Non categorie</th>
                      <th scope="col">Status</th>
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
                  if (isset($categories) && ($categories != "AUCUNE RESULTAT TROUVÉE.")) {
                    while ($categorie = mysqli_fetch_array($categories)) { ?>
                      <tbody>
                        <tr>
                          <!-- <td><input class="form-check-input" type="checkbox"></td> -->
                          <!-- <td><?= $categorie['id_categorie'] ?></td> -->
                          <td><?= $categorie['date_creation'] ?></td>
                          <td><?= $categorie['nom_categorie'] ?></td>
                          <td><?= $categorie['statut'] ?></td>
                          <td>
                            <a class="btn btn-sm btn-warning" href="../controller/categorie.php?put_id_categorie=<?= $categorie['id_categorie'] ?>">Modifier</a>
                            <?php if ($_SESSION['role'] == 'admin') { ?>
                              <a class="btn btn-sm btn-primary" href="../controller/categorie.php?delete_id_categorie=<?= $categorie['id_categorie'] ?>">Supprimer</a>
                            <?php } ?>
                          </td>
                        </tr>
                      </tbody>
                      <!-- Debut Confirmation de suppression catégorie -->
                      <div class=" modal fade" id="delete_categorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content bg-secondary">
                            <div class="modal-header">
                              <span class="modal-title fs-5" id="exampleModalLabel">CONFIRMATION DE SUPPRESSION</span>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              Êtes-vous sûr de vouloir continuer cette action ?
                            </div>
                            <div style="text-align: center; margin-bottom:2%">
                              <a type="button" class="btn btn-outline-danger m-2" data-bs-dismiss="modal">Fermer</a>
                              <a type="button" class="btn btn-outline-success" href="../controller/categorie.php?delete_id_categorie2=<?= $_GET['delete_id_categorie'] ?>">Confirmer</a>
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
    <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
  </div>

  <!-- Début ajout nouveau catégorie -->
  <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">CATEGORIE</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-2">
              <div style="text-align: center;">
                <h5 class="text-primary mb-4">NOUVEAU CATEGORIE</h5>
              </div>
              <form action="../controller/categorie.php" method="POST">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="post_nom_categorie"
                    placeholder="name@example.com" required>
                  <label for="floatingInput">Non Catégorie</label>
                </div>
                <div class="form-floating mb-3">
                  <select class="form-select" name="post_statut"
                    aria-label="Floating label select example" required>
                    <option value="1">Actif</option>
                    <option value="2">Inactif</option>
                  </select>
                  <label for="floatingSelect">Statut du catégorie</label>
                </div>
                <div class="form-floating">
                  <textarea class="form-control" placeholder="Leave a comment here"
                    name="post_description" style="height: 100px;"></textarea>
                  <label for="floatingTextarea">Description</label>
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
  <!-- Fin ajout nouveau catégorie -->

  <!-- Début modifier catégorie -->
  <div class="modal fade" id="put_categorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">CATEGORIE</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-2">
              <div style="text-align: center;">
                <h5 class="text-primary mb-4">MODIFIER CATEGORIE</h5>
              </div>
              <form action="../controller/categorie.php" method="POST">
                <div class="form-floating mb-3">
                  <input type="text" name="put_id_categorie2" value="<?= $_GET['put_id_categorie'] ?>" hidden="true">
                  <input type="text" class="form-control" name="put_nom_categorie"
                    value="<?= $_GET['put_data_categorie']['nom_categorie'] ?>" required>
                  <label for="floatingInput">Non Catégorie</label>
                </div>
                <div class="form-floating mb-3">
                  <select class="form-select" name="put_statut_categorie"
                    aria-label="Floating label select example" required>
                    <?php if ($_GET['put_data_categorie']['statut'] == "actif") { ?>
                      <option selected value="1"><?= $_GET['put_data_categorie']['statut'] ?></option>
                      <option value="2">Inactif</option>
                    <?php } else if ($_GET['put_data_categorie']['statut'] == "inactif") { ?>
                      <option selected value="2"><?= $_GET['put_data_categorie']['statut'] ?></option>
                      <option value="1">Inactif</option>
                    <?php } ?>
                  </select>
                  <label for="floatingSelect">Statut du catégorie</label>
                </div>
                <div class="form-floating">
                  <textarea class="form-control" placeholder="Leave a comment here"
                    name="put_description_categorie" style="height: 100px;"><?= $_GET['put_data_categorie']['description'] ?></textarea>
                  <label for="floatingTextarea">Description</label>
                </div>
                <div style="text-align: center;">
                  <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                  <button type="submit" class="btn btn-outline-success m-2">MODIFIER</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary">Send message</button>
        </div> -->
      </div>
    </div>
  </div>
  <!-- Fin modifier catégorie -->

  <!-- JavaScript Libraries -->
  <script src="../../assets/js/jquery-3.4.1.min.js"></script>
  <script src="../../assets/js/bootstrap.bundle.min.js"></script>
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
        var myModal = new bootstrap.Modal(document.getElementById('delete_categorie'));
        myModal.show();
      <?php endif; ?>
    });
  </script>

</body>

</html>