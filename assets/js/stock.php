<?php
require_once('../../src/controller/fonction.php');

// Connexion à la base de données
// $connexion = mysqli_connect($host, $user, $password, $dbname);

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Requête pour récupérer les données
$sql = "SELECT produit.nom_produit, SUM(stock.quantite_disponible) AS total_quantite FROM stock JOIN produit ON produit.id_produit = stock.produit_id GROUP BY produit.nom_produit ORDER BY produit.nom_produit ASC";
$result = mysqli_query($connexion, $sql);

$data = [
    "labels" => [],
    "datasets" => [
        [
            "label" => "Stock disponible",
            "data" => [],
            "backgroundColor" => [
                "rgba(235, 22, 22, .20)",
                "rgba(22, 235, 22, .7)",
                "rgba(235, 22, 22, .4)",
                "rgba(22, 22, 235, .7)",
                "rgba(235, 22, 235, .5)",
            ],
        ]
    ]
];


// Remplir les données depuis la base
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data["labels"][] = $row["nom_produit"];
        $data["datasets"][0]["data"][] = $row["total_quantite"];
    }
}

// Renvoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Fermer la connexion
mysqli_close($connexion);
