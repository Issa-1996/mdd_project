<?php
require_once('../../src/controller/fonction.php');

// Connexion à la base de données
// $connexion = mysqli_connect($host, $user, $password, $dbname);

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Requête pour récupérer les données
$sql = "SELECT DATE(date_creation) AS jour, COUNT(*) AS nombre_clients_ajoutes FROM client GROUP BY DATE(date_creation) ORDER BY DATE(date_creation) DESC";
$result = mysqli_query($connexion, $sql);

$data = [
    "labels" => [],
    "datasets" => [
        [
            "label" => "Nombre de client",
            "data" => [],
            "backgroundColor" => "rgba(235, 22, 22, .7)"
        ]
    ]
];

// Remplir les données depuis la base
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data["labels"][] = $row["jour"];
        $data["datasets"][0]["data"][] = $row["nombre_clients_ajoutes"];
    }
}

// Renvoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Fermer la connexion
mysqli_close($connexion);
