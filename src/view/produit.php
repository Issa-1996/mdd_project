<?php
require_once('../controller/fonction.php');
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
get_pagination_produit('produit');
// Filtre de recherche
if (isset($_POST['search'])) {
  $produits = get_search_produit($_POST['search']);
  $_SESSION['search_error'] = "";
  if ($produits == "AUCUNE RESULTAT TROUVÉE.") {
    $_SESSION['search_error'] = $produits;
    $produits = get_all_produit();
  }
} else {
  $_SESSION['search_error'] = "";
  $produits = get_all_produit();
}

// Listes des categories et fournisseur qui sont appeler dans le select d'ajout produit
$categories = get_all_category_no_pagination();
$put_categories = get_all_category_no_pagination();
$fournisseurs = get_all_fournisseur_no_pagination();
$put_fournisseurs = get_all_fournisseur_no_pagination();

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
$openModal_produit = isset($_GET['openModal']) && $_GET['openModal'] == 'put_produit';
$openModal_produit_ = isset($_GET['openModal']) && $_GET['openModal'] == 'delete_produit';
$openModal_produit_get = isset($_GET['openModal']) && $_GET['openModal'] == 'get_produit';
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
      transform: scale(1.1);
      /* Agrandit légèrement l'image */
    }

    .scale-on-hover {
      transition: transform 0.3s ease;
      /* Animation fluide */
    }
  </style>

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
        <?php
        if ($_SESSION['success']) { ?>
          <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['success'] ?> <i class="fa fa-exclamation-circle me-2"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php
        }
        if ($_SESSION['error_add']) { ?>
          <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_add'] ?><i class="fa fa-exclamation-circle me-2"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php } else if ($_SESSION['update_success']) { ?>
          <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['update_success'] ?> <i class="fa fa-exclamation-circle me-2"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php } else if ($_SESSION['delete_success']) { ?>
          <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_success'] ?><i class="fa fa-exclamation-circle me-2"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php } else if ($_SESSION['delete_error']) { ?>
          <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['delete_error'] ?><i class="fa fa-exclamation-circle me-2"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php } ?>
        <div class="row">
          <div class="d-flex align-items-center justify-content-between mb-0 bg-secondary" style="margin-bottom: -10%;">
            <form class="d-flex" role="search" method="POST" action="produit.php" id="filterForm">
              <input type="text" class="form-control bg-dark me-2" placeholder="Recherche" aria-label="Search" name="search" id="search" required>
              <input type="submit" class="btn btn-success" value="Rechercher">
            </form>
            <h6 class="mb-0">LISTE DES PRODUITS</h6>
            <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo"><i class="fa fa-plus" aria-hidden="true"></i>Nouveau Produit</button>
          </div>
          <?php
          if (isset($_SESSION['search_error']) && $_SESSION['search_error'] != "") { ?>
            <div class="alert alert-primary alert-dismissible fade show" role="alert" style="display: flex; justify-content:center; align-items:center;">
              <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['search_error']; ?><i class="fa fa-exclamation-circle me-2"></i>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php }
          if (isset($produits) && ($produits != "AUCUNE RESULTAT TROUVÉE.")) {
            while ($produit = mysqli_fetch_array($produits)) { ?>
              <div class="col-sm-12 col-xl-2 col-sm-3  bg-secondary">
                <div class="card m-2 p-0 bg-dark" style="width: 10rem; height: 15rem; overflow: hidden;">
                  <div class="card-img-container" style="height: 8rem; overflow: hidden;">
                    <img
                      class="img-fluid transition scale-on-hover"
                      src="<?= $produit['image_produit'] ?>"
                      alt=""
                      height="100" />
                  </div>
                  <!-- <img src="" alt="" /> -->
                  <div class="card-body p-2">
                    <h5
                      class="card-title text-center text-white mb-1"
                      style="font-size: 14px;">
                      <?= $produit['nom_produit'] ?>
                    </h5>
                    <p class="card-text mb-1" style="font-size: 12px;">
                      <b>Categorie :</b> <?= $produit['nom_categorie'] ?> <br>
                      <b>Prix :</b> <?= $produit['prix_vente'] ?> Fr cfa <br>
                      <b>Statut :</b> <?= $produit[9] ?>
                    </p>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                      <div class="row">
                        <div class="col" style="display:flex; justify-content:center; align-items:center; gap: 10px;">
                          <a class="btn btn-outline-warning p-1" href="../controller/produit.php?put_id_produit=<?= $produit['id_produit'] ?>">Ajuster</a>
                          <a class="btn btn-outline-primary p-1" href="../controller/produit.php?delete_id_produit=<?= $produit['id_produit'] ?>">Retirer</a>
                        </div>
                      </div>
                    <?php } else if ($_SESSION['role'] == 'employe') { ?>
                      <div class="row">
                        <div class="col" style="display:flex; justify-content:center; align-items:center; gap: 10px;">
                          <a class="btn btn-outline-warning p-1" href="../controller/produit.php?put_id_produit=<?= $produit['id_produit'] ?>">Modifier</a>
                          <a class="btn btn-outline-primary p-1" href="../controller/produit.php?get_id_produit=<?= $produit['id_produit'] ?>">Detail</a>
                        </div>
                        <!-- <div class="col" style="display: flex; justify-content:right; align-items:right;">
                        </div> -->
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <!-- Debut Confirmation de suppression catégorie -->
              <div class="modal fade" id="delete_produit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                      <a type="button" class="btn btn-outline-success" href="../controller/produit.php?delete_id_produit2=<?= $_GET['delete_id_produit'] ?>">Confirmer</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin Confirmation de suppression catégorie -->
          <?php }
          } ?>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
          <div class="d-flex justify-content-end mt-3 ">
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
      <!-- Form End -->

      <!-- Début ajout nouveau Produit -->
      <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title fs-5" id="exampleModalLabel">PRODUIT</span>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-secondary">
              <div class="col-sm-12 col-xl-12">
                <div class="bg-secondary rounded h-100 p-2">
                  <div style="text-align: center;">
                    <h5 class="text-primary mb-4">NOUVEAU PRODUIT</h5>
                  </div>
                  <form action="../controller/produit.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="nom_produit"
                            placeholder="Nom Produit" required>
                          <label for="floatingInput">Non Produit</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="description"
                            placeholder="Description du produit">
                          <label for="floatingInput">Description Produit</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="number" class="form-control" name="prix_achat"
                            placeholder="prix_achat">
                          <label for="floatingInput">Prix d'achat</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="number" class="form-control" name="prix_unitaire"
                            placeholder="prix_unitaire">
                          <label for="floatingInput">Prix Unitaire</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="number" class="form-control" name="prix_vente"
                            placeholder="prix_vente" required>
                          <label for="floatingInput">Prix de vente</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <select class="form-select" name="post_statut"
                            aria-label="Floating label select example" required>
                            <option value="1">Actif</option>
                            <option value="2">Inactif</option>
                          </select>
                          <label for="floatingSelect">Statut du produit</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <select class="form-select" name="categorie_id" aria-label="Floating label select example" required>
                            <option value="" disabled selected>CHOISIR UN CATEGORIE</option>
                            <?php while ($categorie = mysqli_fetch_array($categories)) { ?>
                              <option value="<?= $categorie['id_categorie'] ?>"><?= $categorie['nom_categorie'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <select class="form-select" name="fournisseur_id" aria-label="Floating label select example" required>
                            <option value="" disabled selected>CHOISIR UN FOURNISSEUR</option>
                            <?php while ($fournisseur = mysqli_fetch_array($fournisseurs)) { ?>
                              <option value="<?= $fournisseur['id_fournisseur'] ?>"><?= $fournisseur['nom_fournisseur'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="file" class="form-control" name="image_produit" id="image_produit"
                            placeholder="image">
                          <label for="floatingInput">Image Produit</label>
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
      <!-- Fin ajout nouveau Fournisseur -->

      <!-- Début modifier Fournisseur -->
      <div class="modal fade" id="put_produit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title fs-5" id="exampleModalLabel">PRODUIT</span>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-secondary">
              <div class="col-sm-12 col-xl-12">
                <div class="bg-secondary rounded h-100 p-2">
                  <div style="text-align: center;">
                    <h5 class="text-primary mb-4">Modifier un Produit</h5>
                  </div>
                  <form action="../controller/produit.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="text" name="put_id_categorie2" value="<?= $_GET['put_id_produit'] ?>" hidden="true" required>
                          <input type="text" class="form-control" name="put_nom_produit" value="<?= $_GET['put_data_produit']['nom_produit'] ?>"
                            placeholder="Nom Produit" required>
                          <label for="floatingInput">Non Produit</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="put_description"
                            value="<?= $_GET['put_data_produit']['description'] ?>">
                          <label for="floatingInput">Description Produit</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="number" class="form-control" name="put_prix_achat" value="<?= $_GET['put_data_produit']['prix_achat'] ?>">
                          <label for="floatingInput">Prix d'achat</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="number" class="form-control" name="put_prix_unitaire" value="<?= $_GET['put_data_produit']['prix_unitaire'] ?>">
                          <label for="floatingInput">Prix Unitaire</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="number" class="form-control" name="put_prix_vente" value="<?= $_GET['put_data_produit']['prix_vente'] ?>" min="1" required>
                          <label for="floatingInput">Prix de vente</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="col">
                          <div class="form-floating mb-3">
                            <select class="form-select" name="put_statut"
                              aria-label="Floating label select example" required>
                              <?php if ($_GET['put_data_produit']['statut'] == "actif") { ?>
                                <option selected value="1"><?= $_GET['put_data_produit']['statut'] ?></option>
                                <option value="2">Inactif</option>
                              <?php } else if ($_GET['put_data_produit']['statut'] == "inactif") { ?>
                                <option selected value="2"><?= $_GET['put_data_produit']['statut'] ?></option>
                                <option value="1">Inactif</option>
                              <?php } ?>
                            </select>
                            <label for="floatingSelect">Statut du catégorie</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <select class="form-select" name="put_categorie_id"
                            aria-label="Floating label select example" required>
                            <option value="<?= $_GET['put_data_produit']['categorie_id'] ?>" selected><?= $_GET['put_data_produit']['nom_categorie'] ?></option>
                            <?php while ($categorie = mysqli_fetch_array($put_categories)) { ?>
                              <option value="<?= $categorie['id_categorie'] ?>"><?= $categorie['nom_categorie'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating mb-3">
                          <select class="form-select" name="put_fournisseur_id"
                            aria-label="Floating label select example">
                            <option value="<?= $_GET['put_data_produit']['fournisseur_id'] ?>" selected><?= $_GET['put_data_produit']['nom_fournisseur'] ?></option>
                            <?php while ($fournisseur = mysqli_fetch_array($put_fournisseurs)) { ?>
                              <option value="<?= $fournisseur['id_fournisseur'] ?>"><?= $fournisseur['nom_fournisseur'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="file" class="form-control" name="put_image_produit" id="put_image_produit">
                          <label for="floatingInput">Image Produit</label>
                        </div>
                      </div>
                    </div>
                    <div style="text-align: center;">
                      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">FERMER</button>
                      <button type="submit" class="btn btn-outline-success m-2">MODIFIER</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin modifier Produit -->

      <!-- DEBUT MODAL DETAILPRODUIT -->
      <div class="modal fade" id="get_produit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title fs-5" id="exampleModalLabel">DETAIL</span>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-secondary">
              <div class="col-sm-12 col-xl-12">
                <div class="bg-secondary rounded h-100 p-2">
                  <div style="text-align: center;">
                    <div class="row">
                      <div class="col-md-3">
                        <img src="<?= $_GET['get_data_produit']['image_produit'] ?>" alt="" width="200" height="200">
                      </div>
                      <div class="col-md-9">
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Nom Produit :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['nom_produit'] ?></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Description :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['description'] ?></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Prix unitaire :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['prix_unitaire'] ?> Fr cfa</p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Prix d'achat :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['prix_achat'] ?> Fr cfa</p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Prix de vente :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['prix_vente'] ?> Fr cfa</p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Categorie :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['nom_categorie'] ?></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Fournisseur :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['nom_fournisseur'] ?></p>
                          </div>
                        </div>
                        <!-- <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Quantite disponible :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['quantite_disponible'] ?> Fr cfa</p>
                          </div>
                        </div> -->
                        <!-- <div class="row">
                          <div class="col-md-4" style="display:inline-block;">
                            <h6>Emplacement :</h6>
                          </div>
                          <div class="col" style="display:inline-block;">
                            <p><?= $_GET['get_data_produit']['emplacement_produit'] ?> Fr cfa</p>
                          </div>
                        </div> -->
                      </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL DETAILPRODUIT -->

      <!-- Footer Start -->
      <?php include("footer.php"); ?>
      <!-- Footer End -->
    </div>
    <!-- Content End -->

    <!-- Back to Top -->
    <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
  </div>

  <!-- JavaScript Libraries -->
  <script src="../../assets/js/jquery-3.4.1.min.js"></script>
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
      <?php if ($openModal_produit): ?>
        var myModal = new bootstrap.Modal(document.getElementById('put_produit'));
        myModal.show();
      <?php endif; ?>
    });
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($openModal_produit_): ?>
        var myModal = new bootstrap.Modal(document.getElementById('delete_produit'));
        myModal.show();
      <?php endif; ?>
    });
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($openModal_produit_get): ?>
        var myModal = new bootstrap.Modal(document.getElementById('get_produit'));
        myModal.show();
      <?php endif; ?>
    });
  </script>
</body>

</html>