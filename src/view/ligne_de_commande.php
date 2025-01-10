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
$clients = get_all_client_no_pagination();

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="main-content">
    </div>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Sidebar Start -->
        <?php include("side.php"); ?>
        <!-- Sidebar End -->

        <div class="content">
            <!-- Navbar Start -->
            <?php include("nav.php"); ?>
            <!-- Navbar End -->

            <!-- Form Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <!-- Recent Sales Start -->
                    <div class="container-fluid pt-4 px-4">
                        <?php if ($_SESSION['error_add']) { ?>
                            <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_add'] ?><i class="fa fa-exclamation-circle me-2"></i>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <div class="bg-secondary text-center rounded p-4">
                            <h4>VEUILLER CHOISIR UN CLIENT</h4>
                            <form action="../controller/commande.php" method="POST">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="type_commande" value="<?= $_GET['type_commande'] ?>" hidden>
                                            <select class="form-select" name="id_client"
                                                aria-label="Floating label select example" required oninvalid="this.setCustomValidity('VEUILLER CHOISIR UN CLIENT.')" oninput="this.setCustomValidity('')">
                                                <option value="" selected disabled>SELECTIONNER UN CLIENT</option>
                                                <?php while ($client = mysqli_fetch_array($clients)) { ?>
                                                    <option value="<?= $client['id_client'] ?>"><?= $client['prenom_client'] . " " . $client['nom_client'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <label for="floatingSelect">Statut du commande</label>
                                        </div>
                                    </div>
                                </div>
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
            </div>
            <!-- Footer Start -->
            <?php include("footer.php"); ?>
            <!-- Footer End -->
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