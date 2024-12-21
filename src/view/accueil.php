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
$reglements = get_all_paiement();
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
                                <p class="mb-2">Réglement</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Produit</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Client</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Stock</p>
                                <h6 class="mb-0">$1234</h6>
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
                                <div class="col-sm-12 col-xl-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="salse-revenue"></canvas>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="worldwide-sales"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row g-4">
                                <div class="col-sm-12 col-xl-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="pie-chart"></canvas>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-6">
                                    <div class="bg-secondary text-center rounded p-4">
                                        <canvas id="doughnut-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Chart End -->

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">REGEMENT DES FACTURES</h6>
                        <!-- <a href="">Show All</a> -->
                        <form class="form-inline my-2 my-lg-0">
                            <div class="row">
                                <div class="col">
                                    <!-- <label for="">DATE DEPART</label> -->
                                    <input class="form-control mr-sm-2" type="date" placeholder="Search" aria-label="Search">
                                </div>
                                <div class="col">
                                    <!-- <label for="">DATE D'ARRIVER</label> -->
                                    <input class="form-control mr-sm-2" type="date" placeholder="Search" aria-label="Search">
                                </div>
                                <div class="col">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th scope="col">N° COMMANDE</th>
                                    <th scope="col">DATE</th>
                                    <th scope="col">PAIEMENT PAR:</th>
                                    <th scope="col">MONTANT HT</th>
                                    <th scope="col">TVA</th>
                                    <th scope="col">MONTANT TTC</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <?php $total = $tva = 0;
                            while ($reglement = mysqli_fetch_array($reglements)) {
                                if ($reglement['mode_paiement'] != "") { ?>
                                    <tbody>
                                        <tr>
                                            <td><?= $reglement['numero_commande'] ?></td>
                                            <td><?= $reglement['date_paiement'] ?></td>
                                            <td><?= $reglement['mode_paiement'] ?></td>
                                            <td><?= number_format(htmlspecialchars($reglement['montant_ht']), 2, '.', ' ') ?></td>
                                            <td><?= number_format(htmlspecialchars($reglement['tva']), 2, '.', ' ') ?></td>
                                            <td><?= number_format(htmlspecialchars($reglement['montant_ttc']), 2, '.', ' ') ?></td>
                                            <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
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
                                <td><?= number_format(htmlspecialchars($tva), 2, '.', ' ')  ?></td>
                                <td><span><?= number_format(htmlspecialchars($total), 2, '.', ' ')  ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->

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
    <script src="../../assets/js/main_benefice.js"></script>
    <script src="../../assets/js/clientele.js"></script>
    <script src="../../assets/js/stock.js"></script>
</body>

</html>