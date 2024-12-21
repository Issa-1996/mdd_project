<?php
session_start();

require('../../src/controller/fonction.php');
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

// Appel des fonctions
$all_ligne_commande = get_all_ligne_commande_by_commande2($_GET['id_commande']);
$data_commande = [];
while ($info_commande = mysqli_fetch_array($all_ligne_commande, MYSQLI_ASSOC)) {
    $data_commande[] = $info_commande;
}

// Initialisation du contenu du PDF
$pdfcontent = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <link href="style.css" rel="stylesheet" />
</head>
<body>
    <div class="invoice-container">
        <div class="row">
            <div class="col-md-6">
                <img src="../../img/mmd.jpg" width="100">
            </div>
            <h1><u>PROFORMAT</u></h1>
        </div>
        <header>
            <div class="col-3">
                <span><b>MAME DIARRA DECO - SUARL</b></span>
                <p>
                    Mariste en face ECOBANK, Immeuble Yaye<br> 
                    Maguette Ndiaye N°188<br>
                    fallarte669@gmail.com<br>
                    33 832 64 90<br>
                    77 478 87 28 / +39 3396506062
                </p>
            </div>
        </header>';

// Ajout des informations client
if (!empty($data_commande)) {
    $commande = $data_commande[0]; // Utilisation du premier élément pour les infos client
    $pdfcontent .= '<h6 class="facture">' . htmlspecialchars($commande['prenom_client']) . ' ' . htmlspecialchars($commande['nom_client']) . '<br>
        <p>' . htmlspecialchars($commande['telephone_client']) . '</p></h6>';
}

// Ajout des informations de commande
$pdfcontent .= '<h2><b>N°:</b> FAC/MDD/-000' . htmlspecialchars($_GET['id_commande']) . ' 
    <u><b>DATE</b></u> : ' . date("d-m-Y") . '  
    <b>N.I.N.E.A :</b> 009800328</h2>';

// Début du tableau
$pdfcontent .= '<table class="table">
    <thead>
        <tr>
            <th>Nom Produit</th>
            <th>Qté</th>
            <th>PU</th>
            <th>TVA</th>
            <th>Remise</th>
            <th>Total HT</th>
        </tr>
    </thead>
    <tbody>';

// Ajout des lignes du tableau
foreach ($data_commande as $commande) {
    $prix_unitaire = $commande['prix_reduction'] > 0 ? $commande['prix_reduction'] : $commande['prix_vente'];
    $total_ht = $commande['quantite'] * $prix_unitaire;

    $pdfcontent .= '<tr>
        <td>' . htmlspecialchars($commande['nom_produit']) . '</td>
        <td>' . htmlspecialchars($commande['quantite']) . '</td>
        <td>' . number_format($commande['prix_vente'], 2, '.', ' ') . '</td>
        <td>18%</td>
        <td>' . number_format($commande['prix_reduction'], 2, '.', ' ') . '</td>
        <td>' . number_format($total_ht, 2, '.', ' ') . '</td>
    </tr>';
}

// Fermeture du tableau
$pdfcontent .= '</tbody>
</table>';

// Ajout des totaux (bas de page)
if (!empty($data_commande)) {
    $commande = $data_commande[0];
    $pdfcontent .= '<div class="total">
        <p class="ht"><strong>Total HT :</strong> ' . number_format($commande['montant_ht'], 2, '.', ' ') . ' CFA</p>
        <p class="tva"><strong>TVA :</strong> ' . number_format($commande['tva'], 2, '.', ' ') . ' CFA</p>
        <p class="ttc"><strong>Total TTC :</strong> <b>' . number_format($commande['montant_ttc'], 2, '.', ' ') . ' CFA</b></p>
    </div>';
}

// Ajout des signatures et notes
$pdfcontent .= '<p style="margin-top:5%; margin-bottom:10%;">Arrêtée la présente facture à la somme de : <strong>' . convertir_nombre_en_lettres($commande['montant_ttc']) . '.</strong></p>
    <div class="receptionniste"><u>LE RECEPTIONNISTE</u></div>
    <div class="livreur"><u>LE LIVREUR</u></div>
    </div>
</body>
</html>';

// Génération du PDF
$mpdf->WriteHTML($pdfcontent);
$mpdf->SetDisplayMode('fullpage');
$mpdf->Output();
