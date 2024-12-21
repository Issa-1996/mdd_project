<?php
require_once('../controller/fonction.php');
// Désactiver le cache du navigateur
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header("Location: ../../index.php");
  exit();
}
get_pagination('stock');
// Filtre de recherche
if (isset($_POST['search'])) {
    $produits = get_search_stock($_POST['search']);
} else {
    $produits = get_all_stock();
}


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

$openModal = isset($_GET['openModal']) && $_GET['openModal'] == 'detail_produit';
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
                <?php } ?>
                <div class="row">
                    <div class="d-flex align-items-center justify-content-between mb-0 bg-secondary" style="margin-bottom: -10%;">
                        <form class="d-flex" role="search" method="POST" action="produit.php" id="filterForm">
                            <input type="text" class="form-control bg-dark me-2" placeholder="Recherche" aria-label="Search" name="search" id="search" required>
                            <input type="submit" class="btn btn-success" value="Rechercher">
                        </form>
                        <h6 class="mb-0">LISTE DES PRODUITS</h6>
                    </div>
                    <?php
                    while ($produit = mysqli_fetch_array($produits)) { ?>
                        <div class="col-sm-12 col-xl-3 bg-secondary">
                            <div class="card m-2 p-0 bg-dark" style="width: 14rem; height: 15rem; overflow: hidden;">
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
                                        <b>QUANTITE :</b> <?= $produit['quantite_disponible'] ?>
                                    </p>
                                    <div class="row">
                                        <div class="col">
                                            <a class="btn btn-outline-light" href="../controller/commande.php?id_produit=<?= $produit['id_produit'] ?>">DETAIL</a>
                                        </div>
                                        <div class="col">
                                            <a type="button" class="btn btn-outline-success" href="#">CHOISIR</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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

            <!-- DEBUT MODAL DETAILPRODUIT -->
            <div class="modal fade" id="detail_produit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="modal-title fs-5" id="exampleModalLabel">DETAIL DU PRODUIT</span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-secondary">
                            <div class="col-sm-12 col-xl-12">
                                <div class="bg-secondary rounded h-100 p-2">
                                    <div style="text-align: center;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <img src="<?= $_GET['data_produit']['image_produit'] ?>" alt="" width="200" height="200">
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4" style="display:inline-block;">
                                                        <h6>Nom Produit :</h6>
                                                    </div>
                                                    <div class="col" style="display:inline-block;">
                                                        <p><?= $_GET['data_produit']['nom_produit'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4" style="display:inline-block;">
                                                        <h6>Categorie :</h6>
                                                    </div>
                                                    <div class="col" style="display:inline-block;">
                                                        <p><?= $_GET['data_produit']['nom_categorie'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4" style="display:inline-block;">
                                                        <h6>Description :</h6>
                                                    </div>
                                                    <div class="col" style="display:inline-block;">
                                                        <p><?= $_GET['data_produit']['description'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4" style="display:inline-block;">
                                                        <h6>Prix de vente :</h6>
                                                    </div>
                                                    <div class="col" style="display:inline-block;">
                                                        <p><?= $_GET['data_produit']['prix_vente'] ?> Fr cfa</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4" style="display:inline-block;">
                                                        <h6>Quantite disponible :</h6>
                                                    </div>
                                                    <div class="col" style="display:inline-block;">
                                                        <p><?= $_GET['data_produit']['quantite_disponible'] ?> Fr cfa</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4" style="display:inline-block;">
                                                        <h6>Emplacement :</h6>
                                                    </div>
                                                    <div class="col" style="display:inline-block;">
                                                        <p><?= $_GET['data_produit']['emplacement_produit'] ?> Fr cfa</p>
                                                    </div>
                                                </div>
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
            <?php if ($openModal): ?>
                var myModal = new bootstrap.Modal(document.getElementById('detail_produit'));
                myModal.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>