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
        .hidden {
            display: none;
        }

        .form-check-label {
            /* opacity: 0; */
            transform: translateY(10px);
            transition: opacity 0.6s ease, transform 0.6s ease, box-shadow 0.3s ease;
            line-height: 1.5;
            border: 2px solid white;
            border-radius: 1.8rem;
        }

        .form-check-label:hover {
            animation: scale 0.15s ease;
            transform: scale(1.5);
            color: white;
            font-weight: bold;
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
                            <h4>CHOISIR UN MODE DE PAIEMENT</h4>
                            <form action="../controller/commande.php" method="POST">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <!-- <th scope="col">#ID</th> -->
                                            <th scope="col">Produit</th>
                                            <th scope="col">Categorie</th>
                                            <th scope="col">Prix</th>
                                            <th scope="col">Quantite</th>
                                            <th scope="col">Remise</th>
                                            <th scope="col">Montant HT</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $total_ht = 0;
                                    while ($one_ligne_commande = mysqli_fetch_array($all_ligne_commande)) {
                                    ?>
                                        <tbody>
                                            <tr>
                                                <!-- <th scope="row"><?= $one_ligne_commande['id_ligne_commande'] ?></th> -->
                                                <!-- <input type="text" name="id_ligne_commande[]" value="<?= $one_ligne_commande['id_ligne_commande'] ?>" hidden> -->
                                                <td><?= $one_ligne_commande['nom_produit'] ?></td>
                                                <input type="text" name="id_commande_produit_paie" value="<?= $id_commande ?>" hidden>
                                                <td><?= $one_ligne_commande['nom_categorie'] ?></td>
                                                <td><?= number_format($one_ligne_commande['prix_vente'], 2, '.', ' ') ?></td>
                                                <td><?= $one_ligne_commande['quantite'] ?></td>
                                                <?php if ($one_ligne_commande['prix_reduction'] != 0.00) { ?>
                                                    <td><?= number_format($one_ligne_commande['prix_vente'] - $one_ligne_commande['prix_reduction'], 2, '.', ' ') ?></td>
                                                    <td><?= number_format($total = $one_ligne_commande['prix_reduction'] * $one_ligne_commande['quantite'], 2, '.', ' ')  ?></td>
                                                <?php } else { ?>
                                                    <td><?= number_format($one_ligne_commande['prix_reduction'], 2, '.', ' ') ?></td>
                                                    <td><?= number_format($total = $one_ligne_commande['prix_vente'] * $one_ligne_commande['quantite'], 2, '.', ' ') ?></td>
                                                <?php }
                                                $total_ht += $total; ?>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                    <tr style="background-color:black">
                                        <th>TOTAL HT</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><?= number_format($total_ht, 2, '.', ' ') ?> Fr cfa</th>
                                        <input type="text" name="montant_total" value="<?= $total_ht ?>" hidden="true">
                                    </tr>
                                </table>
                                <div style="text-align: center;">
                                    <!-- <button type="submit" class="btn btn-outline-success m-2">SUIVANT</button> -->
                                </div>
                                <hr>
                                <input type="text" name="type_commande" value="<?= $one_commande['type_commande'] ?>" hidden>
                                <?php if (isset($one_commande['type_commande']) && $one_commande['type_commande'] == "vente") { ?>
                                    <span>CHOISIR MODE DE PAIEMENT</span>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-check">
                                                <label class="form-check-label" for="option1">
                                                    <input class="form-check-input" type="radio" name="mode_paiement" id="option1" value="wave" hidden>
                                                    <img class="rounded-circle" src="../../assets/img/WAVE.png" alt="" width="50">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-check">
                                                <label class="form-check-label" for="option2">
                                                    <input class="form-check-input" type="radio" name="mode_paiement" id="option2" value="om" hidden>
                                                    <img class="rounded-circle" src="../../assets/img/om.png" alt="" width="50">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-check">
                                                <label class="form-check-label" for="option3">
                                                    <input class="form-check-input" type="radio" name="mode_paiement" id="option3" value="virement" hidden>
                                                    <img class="rounded-circle" src="../../assets/img/mastercard.webp" alt="" width="50">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-check">
                                                <label class="form-check-label" for="option4">
                                                    <input class="form-check-input" type="radio" name="mode_paiement" id="option4" value="especes" hidden>
                                                    <img class="rounded-circle" src="../../assets/img/mille.jpg" alt="" width="50" height="50">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display:flex; justify-content:center; align-items: center">
                                        <div class="col-md-6">
                                            <!-- Champs dynamiques -->
                                            <div id="dynamicFields" class="mt-4">
                                                <div id="field1" class="hidden">
                                                    <label for="input1" class="form-label">ID TRANSACTION WAVE :</label>
                                                    <input type="text" name="transaction_wave" class="form-control" id="input1" placeholder="ENTREZ ID TRANSACTION">
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo" class="btn btn-outline-success m-2">CONFIRMER</button>
                                                </div>
                                                <div id="field2" class="hidden">
                                                    <label for="input2" class="form-label">REFERENCE TRANSACTION OM :</label>
                                                    <input type="text" name="transaction_om" class="form-control" id="input2" placeholder="ENTREZ REFERENCE TRANSACTION OM">
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo" class="btn btn-outline-success m-2">CONFIRMER</button>
                                                </div>
                                                <div id="field3" class="hidden">
                                                    <label for="input3" class="form-label">ENTRER LE NUMERO DU VIREMENT</label>
                                                    <input type="text" name="transaction_virement" class="form-control" id="input3" placeholder="ENTREZ N° TRANSACTION">
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo" class="btn btn-outline-success m-2">CONFIRMER</button>
                                                </div>
                                                <div id="field4" class="hidden">
                                                    <label for="input4" class="form-label">VOUS AVEZ CHOISI LE PAIEMENT PAR ESPECE</label>
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo" class="btn btn-outline-success m-2">CONFIRMER</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else if (isset($one_commande['type_commande']) && $one_commande['type_commande'] == "proformat") { ?>
                                    <div id="field4">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModals" data-bs-whatever="@mdo" class="btn btn-outline-success m-2">CONTINUER</button>
                                    </div>
                                <?php } ?>
                                <!-- DEBUT MODAL CONFIRMATION DE CONTINUER -->
                                <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <span class="modal-title fs-5" id="exampleModalLabel">CONFIRMATION</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <span id="error-message" style="color: red;"></span>
                                            <div class="modal-body bg-secondary">
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
            // Fonction pour afficher ou cacher les champs dynamiques
            const radioButtons = document.querySelectorAll('input[name="mode_paiement"]');
            const dynamicFields = document.getElementById('dynamicFields');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', () => {
                    // Cacher tous les champs
                    dynamicFields.querySelectorAll('div').forEach(field => {
                        field.classList.add('hidden');
                    });

                    // Afficher le champ correspondant
                    const selectedField = document.getElementById('field' + radio.id.slice(-1));
                    if (selectedField) {
                        selectedField.classList.remove('hidden');
                    }
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>