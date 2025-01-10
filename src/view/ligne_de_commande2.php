<?php
session_start();
// Désactiver le cache du navigateur
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
    session_destroy();
    header("Location: ../../index.php");
    exit();
}
require_once('../controller/fonction.php');

if (isset($_GET['put_id_commande'])) {
    $id_commande = $_GET['put_id_commande'];
    $all_ligne_commande = get_all_ligne_commande_by_commande($_GET['put_id_commande']);
    // print_r($all_ligne_commande); die;
} else {
    $id_commande = $_GET['id_commande'];
    $all_ligne_commande = get_all_ligne_commande_by_commande($id_commande);
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
                        <div class="bg-secondary text-center rounded p-4">
                            <h4>VEUILLER DEFINIR LA QUANTITE ET/OU LA REDUCTION</h4>
                            <form action="../controller/commande.php" method="POST" id="myForm">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Produit</th>
                                            <th scope="col">Categorie</th>
                                            <th scope="col">Prix</th>
                                            <th scope="col">Prix Reduction</th>
                                            <th scope="col">qté</th>
                                            <th scope="col">qté_dispo</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    while ($one_ligne_commande = mysqli_fetch_array($all_ligne_commande)) {
                                    ?>
                                        <tbody>
                                            <tr>
                                                <input type="text" name="id_ligne_commande[]" value="<?= $one_ligne_commande['id_ligne_commande'] ?>" hidden>
                                                <td><?= $one_ligne_commande['nom_produit'] ?></td>
                                                <td><?= $one_ligne_commande['nom_categorie'] ?></td>
                                                <td><?= $one_ligne_commande['prix_vente'] ?></td>
                                                <td style="text-align: center;">
                                                    <input class="form-control" style="width: 60%; margin: auto;" type="number" min="0" name="prix_reduction[]" value="<?= $one_ligne_commande['prix_reduction'] ?>" required>
                                                </td>
                                                <input type="text" name="one_commande" value="<?= $id_commande ?>" hidden>
                                                <td style="text-align: center;"><input class="form-control" style="width: 80%; margin: auto;" type="number" min="1" max="<?= $one_ligne_commande['quantite_disponible'] ?>" name="quantite[]" value="<?= $one_ligne_commande['quantite'] ?>" required></td>
                                                <input type="text" name="quantite_disponible[]" value="<?= $one_ligne_commande['quantite_disponible'] ?>" hidden="true">
                                                <input type="text" name="id_stock[]" value="<?= $one_ligne_commande['id_stock'] ?>" hidden="true">
                                                <td><?= $one_ligne_commande['quantite_disponible'] ?></td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                </table>
                                <div style="text-align: center;">
                                    <button type="button" class="btn btn-outline-success m-2" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo">SUIVANT</button>
                                </div>
                                <!-- DEBUT MODAL CONFIRMATION DE CONTINUER -->
                                <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-secondary">
                                            <div class="modal-header">
                                                <span class="modal-title fs-5" id="exampleModalLabel">CONFIRMATION</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <span id="error-message" style="color: red;"></span>
                                            <div class="modal-body">
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

</body>

</html>