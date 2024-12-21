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
  $utilisateurs = get_search_utilisateur($_POST['search']);
  $_SESSION['search_error'] = "";
  if ($utilisateurs == "AUCUNE RESULTAT TROUVÉE.") {
    $_SESSION['search_error'] = $utilisateurs;
    $utilisateurs = get_all_utilisateur();
  }
} else {
  $_SESSION['search_error'] = "";
  $utilisateurs = get_all_utilisateur();
}
get_pagination('utilisateur');
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
$openModal = isset($_GET['openModal']) && $_GET['openModal'] == 'put_utilisateur';
$openModal_delete = isset($_GET['openModal']) && $_GET['openModal'] == 'delete_utilisateur';
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
    <!-- Spinner Start -->
    <!-- <div
      id="spinner"
      class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
      <div
        class="spinner-border text-primary"
        style="width: 3rem; height: 3rem"
        role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div> -->
    <!-- Spinner End -->

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
                <form class="d-flex" role="search" method="POST" action="utilisateur.php" id="filterForm">
                  <input type="text" class="form-control bg-dark me-2" placeholder="Recherche" aria-label="Search" name="search" id="search" required>
                  <input type="submit" class="btn btn-success" value="Rechercher">
                </form>
                <h6 class="mb-0">LISTE DES UTILISATEURS</h6>
                <!-- <a href="">Tout afficher</a> -->
                <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo"><i class="fa fa-plus" aria-hidden="true"></i>Nouveau Utilisateur</button>
              </div>
              <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                  <thead>
                    <tr class="text-white">
                      <!-- <th scope="col"><input class="form-check-input" type="checkbox"></th> -->
                      <th scope="col">ID</th>
                      <th scope="col">Nom utilisateur</th>
                      <th scope="col">Téléphone</th>
                      <th scope="col">Role</th>
                      <th scope="col">Statut</th>
                      <th scope="col">Dernier Access</th>
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
                  if (isset($utilisateurs) && ($utilisateurs != "AUCUNE RESULTAT TROUVÉE.")) {
                    while ($utilisateur = mysqli_fetch_array($utilisateurs)) { ?>
                      <tbody>
                        <tr>
                          <!-- <td><input class="form-check-input" type="checkbox"></td> -->
                          <td><?= $utilisateur['id_utilisateur'] ?></td>
                          <td><?= $utilisateur['prenom'] . " " . $utilisateur['nom'] ?></td>
                          <td><?= $utilisateur['telephone'] ?></td>
                          <td><?= $utilisateur['role'] ?></td>
                          <td><?= $utilisateur['statut'] ?></td>
                          <td><?= $utilisateur['dernier_acces'] ?></td>
                          <td>
                            <a class="btn btn-sm btn-warning" href="../controller/utilisateur.php?put_id_utilisateur=<?= $utilisateur['id_utilisateur'] ?>">Modifier</a>
                            <!-- <a class="btn btn-sm btn-primary" href="../controller/utilisateur.php?delete_id_utilisateur=<?= $utilisateur['id_utilisateur'] ?>">Supprimer</a> -->
                          </td>
                        </tr>
                      </tbody>
                      <!-- Debut Confirmation de suppression utlisateur -->
                      <!-- <div class="modal fade" id="delete_utilisateur" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                              <a type="button" class="btn btn-outline-success" href="../controller/utilisateur.php?delete_id_utilisateur2=<?= $_GET['delete_id_utilisateur'] ?>">Confirmer</a>
                            </div>
                          </div>
                        </div>
                      </div> -->
                      <!-- Fin Confirmation de suppression utlisateur -->
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

  <!-- Début ajout nouveau utilisateur -->
  <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">INSCRIRE UN NOUVEAU UTILISATEUR</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="">
            <div
              class="d-flex align-items-center justify-content-between mb-3">
              <a href="index.html" class="">
                <h3 class="text-primary">
                  <i class="fa fa-user-edit me-2"></i>MDD
                </h3>
              </a>
              <h3>Inscription</h3>
            </div>
            <h6 style="color: red;">NB: LE NUMERO DE TELEPHONE SERA UTILISER COMME LOGIN ET MOT DE PASSE PAR DEFAUT</h6>
            <form action="../controller/utilisateur.php" method="post">
              <div class="row">
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="prenom"
                      name="prenom"
                      placeholder="jhondoe"
                      required="true" />
                    <label for="prenom">Prénom</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="nom"
                      name="nom"
                      placeholder="jhondoe"
                      required="true" />
                    <label for="nom">Nom</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="telephone"
                      name="telephone"
                      placeholder="jhondoe"
                      required="true" />
                    <label for="telephone">Téléphone</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="email"
                      class="form-control"
                      id="email"
                      name="email"
                      placeholder="jhondoe"/>
                    <label for="email">E-mail</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <select
                      type="text"
                      class="form-select"
                      id="role"
                      name="role"
                      placeholder="jhondoe"
                      required="true">
                      <option value="" disabled selected>
                        CHOISIR UN ROLE
                      </option>
                      <option value="employe">ENPLOYER</option>
                      <option value="admin">ADMIN</option>
                    </select>
                    <label for="role">Role</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <select
                      type="text"
                      class="form-select"
                      id="statut"
                      name="statut"
                      placeholder="jhondoe"
                      required="true">
                      <option value="" disabled selected>
                        CHOISIR UN STATUT
                      </option>
                      <option value="actif">ACTIF</option>
                      <option value="inactif">INACTIF</option>
                    </select>
                    <label for="statut">STATUT</label>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                Inscription
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin ajout nouveau utilisateur -->

  <!-- Début modifier utilisateur -->
  <div class="modal fade" id="put_utilisateur" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="exampleModalLabel">MODIFIER UN UTILISATEUR</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-secondary">
          <div class="">
            <div
              class="d-flex align-items-center justify-content-between mb-3">
              <a href="index.html" class="">
                <h3 class="text-primary">
                  <i class="fa fa-user-edit me-2"></i>MDD
                </h3>
              </a>
              <h3>Modification</h3>
            </div>
            <form action="../controller/utilisateur.php" method="post">
              <div class="row">
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="prenom"
                      name="put_prenom"
                      value="<?= $_GET['put_data_utilisateur']['prenom'] ?>"
                      required="true" />
                    <label for="prenom">Prénom</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input type="text" name="put_id" value="<?= $_GET['put_id_utilisateur'] ?>" hidden="true">
                    <input
                      type="text"
                      class="form-control"
                      id="nom"
                      name="put_nom"
                      value="<?= $_GET['put_data_utilisateur']['nom'] ?>"
                      required="true" />
                    <label for="nom">Nom</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="telephone"
                      name="put_telephone"
                      value="<?= $_GET['put_data_utilisateur']['telephone'] ?>"
                      required="true" />
                    <label for="telephone">Téléphone</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <input
                      type="email"
                      class="form-control"
                      id="email"
                      name="put_email"
                      value="<?= $_GET['put_data_utilisateur']['email'] ?>"
                      required="true" />
                    <label for="email">E-mail</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <select
                      type="text"
                      class="form-select"
                      id="role"
                      name="put_role"
                      placeholder="jhondoe"
                      required="true">
                      <option value="<?= $_GET['put_data_utilisateur']['role'] ?>" selected>
                        <?= $_GET['put_data_utilisateur']['role'] ?>
                      </option>
                      <option value="employe">ENPLOYER</option>
                      <option value="admin">ADMIN</option>
                    </select>
                    <label for="role">Role</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating mb-3">
                    <select
                      type="text"
                      class="form-select"
                      id="statut"
                      name="put_statut"
                      placeholder="jhondoe"
                      required="true">
                      <option value="<?= $_GET['put_data_utilisateur']['statut'] ?>" selected>
                        <?= strtoupper($_GET['put_data_utilisateur']['statut']) ?>
                      </option>
                      <option value="actif">ACTIF</option>
                      <option value="inactif">INACTIF</option>
                    </select>
                    <label for="statut">STATUT</label>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                MODIFIER
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin modifier utilisateur -->

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
        var myModal = new bootstrap.Modal(document.getElementById('put_utilisateur'));
        myModal.show();
      <?php endif; ?>
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //   <?php if ($openModal_delete): ?>
    //     var myModal = new bootstrap.Modal(document.getElementById('delete_utilisateur'));
    //     myModal.show();
    //   <?php endif; ?>
    // });
  </script>

</body>

</html>