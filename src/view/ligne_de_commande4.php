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
$id_commande = $_GET['id_commande'];
$all_ligne_commande = get_all_ligne_commande_by_commande($id_commande);
$one_commande = get_one_commande($id_commande);

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

    <style>
        .solution-card {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.6s ease, transform 0.6s ease, box-shadow 0.3s ease;
        }

        .solution-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Effet de survol */
        .solution-card:hover {
            transform: scale(1.05);
            /* Zoom */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            /* Ombre plus marquée */
        }

        .solution-icon {
            font-size: 50px;
            color: #5A9;
            transition: color 0.3s ease;
        }

        .solution-card:hover .solution-icon {
            color: #3A7;
        }

        .solution-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .solution-description {
            color: #777;
        }
    </style>
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
                            <h2 class="text-center mb-4">Mode impression</h2>
                            <div class="row">
                                <?php if (isset($one_commande['type_commande']) && $one_commande['type_commande'] == "vente") { ?>
                                    <div class="col-md-4">
                                        <a href="../../assets/facture/facture.php?id_commande=<?= $id_commande ?>" target="_blank">
                                            <div class="solution-card text-center p-4 bg-white shadow rounded">
                                                <div class="solution-icon">📄</div>
                                                <div class="solution-title">Facture</div>
                                                <p class="solution-description">Des solutions de paiement sécurisées.</p>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <a href="../../assets/facture/proformat.php?id_commande=<?= $id_commande ?>" target="_blank">
                                        <div class="solution-card text-center p-4 bg-white shadow rounded">
                                            <div class="solution-icon">📝</div>
                                            <div class="solution-title">Préformat</div>
                                            <p class="solution-description">Facilitez vos livraisons avec nos outils avancés.</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="../../assets/facture/bon_livraison.php?id_commande=<?= $id_commande ?>" target="_blank">
                                        <div class="solution-card text-center p-4 bg-white shadow rounded">
                                            <div class="solution-icon">📦</div>
                                            <div class="solution-title">Bon de livraison</div>
                                            <p class="solution-description">Obtenez des rapports détaillés sur vos transactions.</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
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
        <script>
            // Fonction pour activer l'animation lorsque l'élément entre dans la vue
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            });

            // Cible les cartes de solutions
            const solutionCards = document.querySelectorAll('.solution-card');
            solutionCards.forEach((card) => observer.observe(card));
        </script>

</body>

</html>