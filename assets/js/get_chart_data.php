<?php
require_once('../../src/controller/fonction.php');

// Connexion à la base de données
// $connexion = mysqli_connect($host, $user, $password, $dbname);

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Requête pour récupérer les données
// $sql = "SELECT (ligne_commande.quantite), (commande.date_commande) FROM `commande` JOIN ligne_commande ON ligne_commande.id_commande=commande.id_commande WHERE commande.type_commande ='vente' AND commande.date_commande >= DATE_SUB(CURDATE(), INTERVAL 7 DAY);";
$sql = "SELECT DATE(commande.date_commande) AS date_commande, SUM(ligne_commande.quantite) AS total_quantite FROM commande JOIN ligne_commande ON ligne_commande.id_commande = commande.id_commande WHERE commande.type_commande = 'vente' AND commande.date_commande >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(commande.date_commande)
ORDER BY DATE(commande.date_commande) ASC";
$result = mysqli_query($connexion, $sql);

$data = [
    "labels" => [],
    "datasets" => [
        [
            "label" => "Quantité Produit",
            "data" => [],
            "backgroundColor" => "rgba(235, 22, 22, .7)",
            "fill" => true,
        ]
    ]
];

// Remplir les données depuis la base
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data["labels"][] = $row["date_commande"];
        $data["datasets"][0]["data"][] = $row["total_quantite"];
    }
}

// Renvoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Fermer la connexion
mysqli_close($connexion);
