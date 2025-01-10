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
if (isset($_POST['depart']) && isset($_POST['arriver'])) {
    $reglements = filtre_paiement($_POST['depart'], $_POST['arriver']);
} else {
    $reglements = get_all_paiement();
}
//Verification message success d'ajout
if (isset($_GET['success'])) {
    $_SESSION['success'] = $_GET['success'];
} else {
    $_SESSION['success'] = '';
}
//Verification message erreur du modification du profil
if (isset($_GET['error_update'])) {
    $_SESSION['error_update'] = $_GET['error_update'];
} else {
    $_SESSION['error_update'] = '';
}
$openModal_get = isset($_GET['openModal']) && $_GET['openModal'] == 'get_utilisateur';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>mmd</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../../assets/img/favicon.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../assets/css/style.css" rel="stylesheet">
    <style>
        /* Changer la couleur de l'icône dans les navigateurs basés sur WebKit */
        input[type="date"]::-webkit-calendar-picker-indicator {
            color: red;
            /* Couleur de l'icône */
            background-color: red;
            /* Fond derrière l'icône */
            cursor: pointer;
            /* Changer le curseur */
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #table-to-print,
            #table-to-print * {
                visibility: visible;
            }

            #table-to-print {
                position: absolute;
                top: 0;
                left: 0;
            }
        }
    </style>
</head>

<body>

    <div id="main-content">
    </div>
    <!-- Contenu Dynamique -->
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Sidebar Start -->
        <?php include("side.php"); ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <?php include("nav.php"); ?>
            <!-- Navbar End -->

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <h6 class="mb-2">REGLEMENT</h6>
                                <!-- <h6 class="mb-0">$1234</h6> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <h6 class="mb-2">PRODUIT</h6>
                                <!-- <h6 class="mb-0">$1234</h6> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <h6 class="mb-2">CLIENT</h6>
                                <!-- <h6 class="mb-0">$1234</h6> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <h6 class="mb-2">STOCK</h6>
                                <!-- <h6 class="mb-0">$1234</h6> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->

            <!-- Sales Chart Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded h-100 ">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist" style="display: flex; justify-content:center; align-items:center;">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">RÉGELEMENT</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab"
                                aria-controls="nav-profile" aria-selected="false">PRODUIT COMMANDÉ</button>
                        </div>
                    </nav>
                    <div class="tab-content pt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row g-4">
                                <div class="col-sm-12 col-xl-6 col-md-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="salse-revenue"></canvas>
                                        <p>Diagramme des factures hebdomadaires (en FCFA)</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-6 col-md-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="worldwide-sales"></canvas>
                                        <p>Diagramme de la quantité de produits vendus hebdomadairement</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row g-4">
                                <div class="col-sm-12 col-xl-6 col-md-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="pie-chart"></canvas>
                                        <p>Évolution de la clientèle hebdomadaire</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-6 col-md-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="doughnut-chart" style="margin-top: -20%; margin-bottom:-5%;" width="400" height="400"></canvas>
                                        <p style="text-align: center;">Diagramme de l'état du stock des produits</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Chart End -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <!-- Recent Sales Start -->
                <div class="container-fluid pt-4 px-4">
                    <div class="bg-secondary text-center rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-0">RÉGLEMENT DES FACTURES</h6>
                            <button class="btn btn-light" onclick="printTable()">Imprimer</button>
                            <!-- <a href="">Show All</a> -->
                            <form class="form-inline my-2 my-lg-0" method="POST" action="accueil.php">
                                <div class="row">
                                    <div class="col">
                                        <label for="">DATE DEPART</label>
                                        <input class="form-control mr-sm-2" type="date" name="depart" placeholder="Search" aria-label="Search">
                                    </div>
                                    <div class="col">
                                        <label for="">DATE D'ARRIVER</label>
                                        <input class="form-control mr-sm-2" type="date" name="arriver" placeholder="Search" aria-label="Search">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-outline-success mt-4" type="submit">Filtrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-start align-middle table-bordered table-hover mb-0" id="table-to-print">
                                <thead>
                                    <tr class="text-white">
                                        <th scope="col">N° COMMANDE</th>
                                        <th scope="col">DATE</th>
                                        <th scope="col">PAIEMENT PAR:</th>
                                        <th scope="col">MONTANT HT</th>
                                        <th scope="col">TVA</th>
                                        <th scope="col">MONTANT TTC</th>
                                        <!-- <th scope="col">Action</th> -->
                                    </tr>
                                </thead>
                                <?php $total = $tva = 0;
                                while ($reglement = mysqli_fetch_array($reglements)) {
                                    if ($reglement['mode_paiement'] != "") { ?>
                                        <tbody>
                                            <tr>
                                                <td><?= $reglement['numero_commande'] ?></td>
                                                <td><?= dateFromat($reglement['date_paiement']) ?></td>
                                                <td><?= $reglement['mode_paiement'] ?></td>
                                                <td><?= number_format(htmlspecialchars($reglement['montant_ht']), 2, '.', ' ') ?></td>
                                                <td><?= number_format(htmlspecialchars($reglement['tva']), 2, '.', ' ') ?></td>
                                                <td><?= number_format(htmlspecialchars($reglement['montant_ttc']), 2, '.', ' ') ?></td>
                                                <!-- <td><a class="btn btn-sm btn-primary" href="">Detail</a></td> -->
                                            </tr>
                                        </tbody>
                                <?php $total += $reglement['montant_ttc'];
                                        $tva += $reglement['tva'];
                                    }
                                } ?>
                                <tr class="bg-dark">
                                    <td>TOTAL TTC</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?= number_format(htmlspecialchars($tva), 2, '.', ' ')  ?> Fr cfa</td>
                                    <td><?= number_format(htmlspecialchars($total), 2, '.', ' ')  ?> Fr cfa</td>
                                </tr>
                            </table>
                            <script>
                                function printTable() {
                                    window.print();
                                }
                            </script>
                        </div>
                    </div>
                </div>
                <!-- Recent Sales End -->
            <?php } ?>
            <!-- Footer Start -->
            <?php include("footer.php"); ?>
            <!-- Footer End -->
        </div>
        <!-- Content End -->
        <!-- Mon Profil -->
        <div class="modal fade" id="get_utilisateur" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title fs-5" id="exampleModalLabel">MON PRODIL</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-secondary">
                        <div class="">
                            <div
                                class="row">
                                <div class="col-md-3">
                                    <a href="#" class="">
                                        <h3 class="text-primary">
                                            <img src="../../assets/img/mmd-removebg-preview.png" width="70" alt="">
                                        </h3>
                                    </a>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <h3 style="display: flex; justify-content:center; align-items:center;">MODIFIER MON PROFIL</h3>
                                </div>
                                <?php
                                if ($_SESSION['success']) { ?>
                                    <div class="text-success mb-3" style="display: flex; justify-content:center; align-items:center; margin-top:-3%">
                                        <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['success'] ?><i class="fa fa-exclamation-circle me-2"></i>
                                    </div>
                                <?php
                                }
                                if ($_SESSION['error_update']) { ?>
                                    <div class="text-primary mb-3" style="display: flex; justify-content:center; align-items:center; margin-top:-3%">
                                        <i class="fa fa-exclamation-circle me-2"></i><?= $_SESSION['error_update'] ?> <i class="fa fa-exclamation-circle me-2"></i>
                                    </div>
                                <?php } ?>
                            </div>
                            <form action="../controller/utilisateur.php" method="post">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                type="text"
                                                class="form-control bg-secondary"
                                                id="prenom"
                                                name="get_prenom"
                                                value="<?= $_GET['get_utilisateur']['prenom'] ?>"
                                                required="true" disabled />
                                            <label for="prenom">Prénom</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="get_id_utilisateur" value="<?= $_GET['get_id_utilisateur'] ?>" hidden="true">
                                            <input
                                                type="text"
                                                class="form-control bg-secondary"
                                                id="nom"
                                                name="get_nom"
                                                value="<?= $_GET['get_utilisateur']['nom'] ?>"
                                                required="true" disabled />
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
                                                name="get_telephone"
                                                value="<?= $_GET['get_utilisateur']['telephone'] ?>"
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
                                                name="get_email"
                                                value="<?= $_GET['get_utilisateur']['email'] ?>"
                                                required="true" />
                                            <label for="email">E-mail</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="login"
                                                name="get_login"
                                                value="<?= $_GET['get_utilisateur']['login'] ?>"
                                                required="true" />
                                            <label for="login">Login</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="statut"
                                                name="get_mot_de_passe" />
                                            <label for="confirmation">Mot de passe</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="confirmation"
                                                name="get_confirmation" />
                                            <label for="mot_de_passe">confirmation</label>
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align: center;">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-outline-success">METTRE À JOUR</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Mon Profil -->

        <!-- Back to Top -->
        <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>
    <!-- JavaScript Libraries -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/jquery-3.4.1.min.js"></script>
    <!-- <script src="../../assets/js/jquery-3.4.1.min.js"></script> -->
    <!-- <script src="../../assets/js/bootstrap.bundle.min.js"></script> -->
    <script src="../../assets/lib/chart/chart.min.js"></script>
    <script src="../../assets/lib/easing/easing.min.js"></script>
    <script src="../../assets/lib/waypoints/waypoints.min.js"></script>
    <script src="../../assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../../assets/lib/tempusdominus/js/moment.min.js"></script>
    <script src="../../assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../../assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/main_benefice.js"></script>
    <script src="../../assets/js/clientele.js"></script>
    <script src="../../assets/js/stock.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($openModal_get): ?>
                var myModal = new bootstrap.Modal(document.getElementById('get_utilisateur'));
                myModal.show();
            <?php endif; ?>
        })
    </script>
</body>

</html>