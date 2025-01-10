<?php
require_once('../../src/controller/fonction.php');

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Requête pour récupérer les données
$sql = "SELECT produit.nom_produit, SUM(stock.quantite_disponible) AS total_quantite FROM stock JOIN produit ON produit.id_produit = stock.produit_id GROUP BY produit.nom_produit ORDER BY produit.nom_produit ASC";
$result = mysqli_query($connexion, $sql);

// Générer une couleur aléatoire unique en format RGBA
function generateUniqueColor(&$existingColors) {
    do {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        $a = rand(50, 100) / 100; // Alpha entre 0.5 et 1
        $color = "rgba($r, $g, $b, $a)";
    } while (in_array($color, $existingColors)); // Recommencer si la couleur existe déjà
    $existingColors[] = $color; // Ajouter la couleur au tableau des couleurs existantes
    return $color;
}

$data = [
    "labels" => [],
    "datasets" => [
        [
            "label" => "Stock disponible",
            "data" => [],
            "backgroundColor" => [], // Couleurs dynamiques ici
        ]
    ]
];

$existingColors = []; // Stocker les couleurs générées pour éviter les doublons

// Remplir les données depuis la base
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data["labels"][] = $row["nom_produit"];
        $data["datasets"][0]["data"][] = $row["total_quantite"];
        $data["datasets"][0]["backgroundColor"][] = generateUniqueColor($existingColors); // Générer une couleur unique
    }
}

// Renvoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Fermer la connexion
mysqli_close($connexion);
?>
