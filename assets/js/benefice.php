<?php
require_once('../../src/controller/fonction.php');

// Connexion à la base de données
// $connexion = mysqli_connect($host, $user, $password, $dbname);

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Requête pour récupérer les données
// $sql = "SELECT (commande.date_commande), (paiement.montant_ttc) FROM `commande` JOIN paiement ON paiement.id_commande=commande.id_commande WHERE commande.type_commande ='vente'";
$sql = "SELECT DATE(commande.date_commande) AS date_commande, SUM(paiement.montant_ttc) AS total_montant FROM commande JOIN paiement ON paiement.id_commande = commande.id_commande WHERE commande.type_commande = 'vente' GROUP BY DATE(commande.date_commande) ORDER BY DATE(commande.date_commande) ASC";
$result = mysqli_query($connexion, $sql);

$data = [
    "labels" => [],
    "datasets" => [
        [
            "label" => "Montant TTC Commande",
            "data" => [],
            "backgroundColor" => "rgba(235, 22, 22, .7)",
        ]
    ]
];

// Remplir les données depuis la base
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data["labels"][] = $row["date_commande"];
        $data["datasets"][0]["data"][] = $row["total_montant"];
    }
}

// Renvoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Fermer la connexion
mysqli_close($connexion);
