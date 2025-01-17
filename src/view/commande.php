<?php
session_start();
require_once('../controller/fonction.php');
// Desactiver le cache du navigateur
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
  $commandes = get_search_commande($_POST['search']);
  $_SESSION['search_error'] = "";
  if ($commandes == "AUCUNE RESULTAT TROUVÉE.") {
    $_SESSION['search_error'] = $commandes;
    $commandes = get_all_commande();
  }
} else {
  $_SESSION['search_error'] = "";
  $commandes = get_all_commande();
}


get_pagination('commande');
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
$openModal = isset($_GET['openModal']) && $_GET['openModal'] == 'put_commande';
$openModal_delete = isset($_GET['openModal']) && $_GET['openModal'] == 'delete_commande';
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
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_add']; ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['update_success']) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['update_success'] ?> <i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['delete_success']) { ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_success'] ?> <i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } else if ($_SESSION['delete_error']) { ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_error']; ?><i class="fa fa-exclamation-circle me-2"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } ?>
              <div class="d-flex align-items-center justify-content-between mb-4">
                <form class="d-flex" role="search" method="POST" action="commande.php" id="filterForm">
                  <input type="text" class="form-control bg-dark me-2" placeholder="Recherche" aria-label="Search" name="search" id="search" required>
                  <input type="submit" class="btn btn-success" value="Rechercher">
                </form>
                <h6 class="mb-0">LISTE DES COMMANDES</h6>
                <!-- <a href="">Tout afficher</a> -->
                <a href="ligne_de_commande.php?type_commande=vente" class="btn btn-outline-primary m-2"><i class="fa fa-plus" aria-hidden="true"></i>Nouvelle Vente</a>
                <a href="ligne_de_commande.php?type_commande=proformat" class="btn btn-dark m-2"><i class="fa fa-plus" aria-hidden="true"></i>PROFORMAT</a>
              </div>
              <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                  <thead>
                    <tr class="text-white">
                      <!-- <th scope="col"><input class="form-check-input" type="checkbox"></th> -->
                      <th scope="col">N° Fact </th>
                      <th scope="col">Prenom & Nom</th>
                      <th scope="col">Téléphone</th>
                      <th scope="col">Type de Commande</th>
                      <!-- <th scope="col">Montant Total</th> -->
                      <th scope="col">Statut</th>
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
                  if (isset($commandes) && ($commandes != "AUCUNE RESULTAT TROUVÉE.")) {
                    while ($commande = mysqli_fetch_array($commandes)) { ?>
                      <tbody>
                        <tr>
                          <!-- <td><input class="form-check-input" type="checkbox"></td> -->
                          <td><?= $commande['numero_commande'] ?></td>
                          <td><?= $commande['prenom_client'] . " " . $commande['nom_client'] ?></td>
                          <td><?= $commande['telephone_client'] ?></td>
                          <td><?= $commande['type_commande'] ?></td>
                          <!-- <td><?= $commande['nom_produit'] ?></td> -->
                          <td><?= $commande['statut_commande'] ?></td>
                          <td>
                            <?php if ($commande['statut_commande'] != 'validee') { ?>
                              <a class="btn btn-sm btn-warning" href="../controller/commande.php?put_id_commande=<?= $commande['id_commande'] ?>">Modifier</a>
                              <?php if ($_SESSION['role'] == 'admin') { ?>
                                <a class="btn btn-sm btn-primary" href="../controller/commande.php?delete_id_commande=<?= $commande['id_commande'] ?>">Supprimer</a>
                            <?php }
                            } ?>
                            <?php if ($commande['statut_commande'] == 'validee') { ?>
                            <a class="btn btn-sm btn-dark" href="ligne_de_commande4.php?id_commande=<?= $commande['id_commande'] ?>">Imprimer</a>
                            <?php } ?>
                          </td>
                        </tr>
                      </tbody>
                      <!-- Debut Confirmation de suppression catégorie -->
                      <div class="modal fade" id="delete_commande" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                              <a type="button" class="btn btn-outline-success" href="../controller/commande.php?delete_id_commande2=<?= $_GET['delete_id_commande'] ?>">Confirmer</a>
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

  <!-- Début modifier commande -->
  <div class="modal fade" id="put_commande" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">COMMANDE</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="col-sm-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-2">
              <div style="text-align: center;">
                <h5 class="text-primary mb-4">NOUVEAU commande</h5>
              </div>
              <form action="../controller/commande.php" method="POST">
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="text" name="put_id_commande" value="<?= $_GET['put_id_commande'] ?>" hidden="true">
                      <input type="text" class="form-control" name="put_nom_commande"
                        value="<?= $_GET['put_data_commande']['nom_commande'] ?>">
                      <label for="floatingInput">Nom commande</label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="prenom_commande"
                        value="<?= $_GET['put_data_commande']['prenom_commande'] ?>">
                      <label for="floatingInput">Prenom commande</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="telephone"
                        value="<?= $_GET['put_data_commande']['telephone_commande'] ?>">
                      <label for="floatingInput">Téléphone commande</label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control" name="email"
                        value="<?= $_GET['put_data_commande']['email_commande'] ?>">
                      <label for="floatingInput">E-mail commande</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="adresse"
                        value="<?= $_GET['put_data_commande']['adresse_commande'] ?>">
                      <label for="floatingInput">Adresse commande</label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-floating mb-3">
                      <select class="form-select" name="put_statut_commande"
                        aria-label="Floating label select example">
                        <?php if ($_GET['put_data_commande']['statut'] == "actif") { ?>
                          <option selected value="1"><?= $_GET['put_data_commande']['statut'] ?></option>
                          <option value="2">Inactif</option>
                        <?php } else if ($_GET['put_data_commande']['statut'] == "inactif") { ?>
                          <option selected value="2"><?= $_GET['put_data_commande']['statut'] ?></option>
                          <option value="1">Inactif</option>
                        <?php } ?>
                      </select>
                      <label for="floatingSelect">Statut du commande</label>
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
  <!-- Fin modifier commande -->

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
        var myModal = new bootstrap.Modal(document.getElementById('put_commande'));
        myModal.show();
      <?php endif; ?>
    });

    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($openModal_delete): ?>
        var myModal = new bootstrap.Modal(document.getElementById('delete_commande'));
        myModal.show();
      <?php endif; ?>
    });
  </script>

</body>

</html>