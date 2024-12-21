<?php
require_once('fonction.php');
/***********************DEBUT (LES ATTRIBUTS DE CONNEXION ET AUTRES) ********************************* */

//FONCTION DE CONNEXION
function connecter_utilisateur($login, $mot_de_passe)
{
    global $connexion;

    $sql = "SELECT * FROM utilisateur WHERE login = ? AND statut = ?";
    $stmt = $connexion->prepare($sql);

    if (!$stmt) {
        die("Erreur dans la préparation de la requête : " . $connexion->error);
    }
    $statut = "actif";
    $stmt->bind_param("ss", $login, $statut);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $utilisateur = $result->fetch_assoc();

        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            session_start();
            // Mettre à jour le dernier accès
            $requete_update = "UPDATE utilisateur SET dernier_acces = NOW() WHERE login = ?";
            $stmt_update = $connexion->prepare($requete_update);
            $stmt_update->bind_param("s", $login);
            $stmt_update->execute();
            $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
            $_SESSION['nom_utilisateur'] = $utilisateur['nom'];

            // Enregistrer le log de connexion et stocker l'ID du log
            $id_log = enregistrer_connexion($utilisateur['id_utilisateur']);
            $_SESSION['id_log'] = $id_log;

            return $utilisateur;
        }
    }
    return "UTILISATEUR NON RECONNU"; // Échec de la connexion
}


// FONCTION POUR DECONNEXION
function deconnecter_utilisateur()
{

    if (isset($_SESSION['id_utilisateur']) && isset($_SESSION['id_log'])) {
        $id_log = $_SESSION['id_log'];

        // Enregistrer la déconnexion
        enregistrer_deconnexion($id_log);
    }

    // Supprimer toutes les variables de session
    $_SESSION = array();

    // Si un cookie de session existe, le supprimer
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000, // Expiration dans le passé
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion ou d'accueil
    header("Location: ../../index.php");
    exit;
}


// ENREGISTRER LES INFORMATION DE CONNEXION
function enregistrer_connexion($id_utilisateur)
{
    global $connexion;

    $sql = "INSERT INTO log_connexion (id_utilisateur) VALUES (?)";
    $stmt = $connexion->prepare($sql);

    if (!$stmt) {
        die("Erreur lors de la préparation : " . $connexion->error);
    }

    $stmt->bind_param("i", $id_utilisateur);

    if ($stmt->execute()) {
        // Retourne l'ID du log créé
        return $connexion->insert_id;
    } else {
        die("Erreur lors de l'enregistrement de la connexion : " . $connexion->error);
    }
}

// ENREGISTRER LES INFORMATION DE LA DECONNEXION
function enregistrer_deconnexion($id_log)
{
    global $connexion;

    $sql = "UPDATE log_connexion SET date_deconnexion = NOW() WHERE id_log = ?";
    $stmt = $connexion->prepare($sql);

    if (!$stmt) {
        die("Erreur lors de la préparation : " . $connexion->error);
    }

    $stmt->bind_param("i", $id_log);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Erreur lors de l'enregistrement de la déconnexion : " . $connexion->error);
    }
}

// Pour afficher l'historique des connexions et déconnexions 
function afficher_logs($id_utilisateur = null)
{
    global $connexion;

    $sql = "SELECT log_connexion.*, utilisateur.nom, utilisateur.prenom 
            FROM log_connexion 
            INNER JOIN utilisateur ON log_connexion.id_utilisateur = utilisateur.id_utilisateur";

    if ($id_utilisateur) {
        $sql .= " WHERE log_connexion.id_utilisateur = ?";
        $stmt = $connexion->prepare($sql);

        if (!$stmt) {
            die("Erreur dans la préparation de la requête : " . $connexion->error);
        }

        $stmt->bind_param("i", $id_utilisateur);
    } else {
        $stmt = $connexion->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}


// Fonction pour convertir un nombre en lettre
function convertir_nombre_en_lettres($nombre, $devise = 'Fr cfa')
{
    if (!is_numeric($nombre)) {
        return "Erreur : la valeur fournie n'est pas un nombre.";
    }

    $nombres = [
        0 => 'Zéro',
        1 => 'Un',
        2 => 'Deux',
        3 => 'Trois',
        4 => 'Quatre',
        5 => 'Cinq',
        6 => 'Six',
        7 => 'Sept',
        8 => 'Huit',
        9 => 'Neuf',
        10 => 'Dix',
        11 => 'Onze',
        12 => 'Douze',
        13 => 'Treize',
        14 => 'Quatorze',
        15 => 'Quinze',
        16 => 'Seize',
        20 => 'Vingt',
        30 => 'Trente',
        40 => 'Quarante',
        50 => 'Cinquante',
        60 => 'Soixante',
        80 => 'Quatre-vingt'
    ];

    if ($nombre < 0) {
        return "moins " . convertir_nombre_en_lettres(abs($nombre));
    }

    if ($nombre < 17) {
        return $nombres[$nombre];
    }

    if ($nombre < 20) {
        return 'dix-' . $nombres[$nombre - 10];
    }

    if ($nombre < 100) {
        $dixaines = (int)($nombre / 10) * 10;
        $reste = $nombre % 10;
        if ($reste == 0) {
            return $nombres[$dixaines];
        }
        if ($dixaines == 80) {
            return $nombres[$dixaines] . '-' . $nombres[$reste];
        }
        return $nombres[$dixaines] . '-' . $nombres[$reste];
    }

    if ($nombre < 1000) {
        $centaines = (int)($nombre / 100);
        $reste = $nombre % 100;
        $centaines_texte = ($centaines == 1) ? "Cent" : $nombres[$centaines] . " Cent";
        if ($reste == 0) {
            return $centaines_texte;
        }
        return $centaines_texte . " " . convertir_nombre_en_lettres($reste);
    }

    if ($nombre < 1000000) {
        $milliers = (int)($nombre / 1000);
        $reste = $nombre % 1000;
        $milliers_texte = ($milliers == 1) ? "Mille" : convertir_nombre_en_lettres($milliers) . " Mille";
        if ($reste == 0) {
            return $milliers_texte;
        }
        return $milliers_texte . " " . convertir_nombre_en_lettres($reste);
    }

    if ($nombre < 1000000000) {
        $millions = (int)($nombre / 1000000);
        $reste = $nombre % 1000000;
        $millions_texte = ($millions == 1) ? "Un million" : convertir_nombre_en_lettres($millions) . " Millions";
        if ($reste == 0) {
            return $millions_texte;
        }
        return $millions_texte . " " . convertir_nombre_en_lettres($reste);
    }

    return "Nombre trop grand.";
}

/********************************************************************************** 
Connexion à la base de données MySQL 
 **********************************************************************************/
function connexion_db()
{
    $connexion = mysqli_connect("localhost", "root", "", "mmd");
    // Vérifiez la connexion
    if ($connexion === false) {
        die("Erreur : Impossible de se connecter. " . mysqli_connect_error());
    }
    return $connexion;
}
$connexion = connexion_db();

/********************************************************************************** 
Liste des attributs de la pagination
 **********************************************************************************/
function get_attribut_pagination()
{
    global $page, $items_per_page, $items_per_page2, $offset, $offset2;
    // Déterminer la page actuelle
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max($page, 1); // S'assurer que la page est au moins 1
    $items_per_page = 5;
    $items_per_page2 = 12;
    $offset = ($page - 1) * $items_per_page;
    $offset2 = ($page - 1) * $items_per_page2;
}
get_attribut_pagination();

/********************************************************************************** 
La pagination de toutes les tables de la base de données
 **********************************************************************************/
function get_pagination($table)
{
    global $connexion, $items_per_page, $total_pages;
    $total_items_query = "SELECT COUNT(*) as total FROM $table";
    $result = mysqli_query($connexion, $total_items_query);
    $total_items = mysqli_fetch_assoc($result)['total'];
    $total_pages = ceil($total_items / $items_per_page);
    return $total_pages;
}
function get_pagination_produit($table)
{
    global $connexion, $items_per_page2, $total_pages;
    $total_items_query = "SELECT COUNT(*) as total FROM $table";
    $result = mysqli_query($connexion, $total_items_query);
    $total_items = mysqli_fetch_assoc($result)['total'];
    $total_pages = ceil($total_items / $items_per_page2);
    return $total_pages;
}
/***********************FIN (LES ATTRIBUTS DE CONNEXION ET AUTRES) ********************************* */


/***********************DEBUT (LES FONCTION DE LA TABLE PRODUIT) ********************************* */
/********************************************************************************** 
Ajouter un nouveau produit
 ********************************************************************************* */
// function add_produit($nom_produit, $description, $prix_unitaire, $categorie_id, $prix_achat, $prix_vente, $fournisseur_id, $statut, $image_produit)
// {
//     global $connexion;
//     $image_produit = '../../assets/img/' . $image_produit;
//     $id_utilisateur = $_SESSION['id_utilisateur'];
//     $date_creation = date('Y-m-d H:i:s');
//     $requete = "INSERT INTO `produit` (`nom_produit`, `description`, `prix_unitaire`, `categorie_id`, `prix_achat`, `prix_vente`, `fournisseur_id`, `date_creation`, `statut`, `image_produit`, `id_utilisateur`) VALUES ('$nom_produit', '$description', '$prix_unitaire', '$categorie_id', '$prix_achat', '$prix_vente', '$fournisseur_id', '$date_creation', '$statut', '$image_produit', '$id_utilisateur')";
//     $result = $connexion->prepare($requete);
//     return $result->execute();
// }
function add_produit($nom_produit, $description, $prix_unitaire, $categorie_id, $prix_achat, $prix_vente, $fournisseur_id, $statut, $image_file)
{
    global $connexion;

    try {
        // Dossier cible pour les images
        $target_dir = '../../assets/img/';
        if (!is_dir($target_dir)) {
            die("Erreur : Le dossier cible n'existe pas ou n'est pas accessible : $target_dir");
        }

        // Nom unique pour l'image
        $image_name = time() . '_' . basename($image_file['name']);
        $target_file = $target_dir . $image_name;

        // Vérification des extensions autorisées
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($image_file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            die("Erreur : Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
        }

        // Vérification de la taille du fichier (limite de 2 Mo)
        if ($image_file['size'] > 2 * 1024 * 1024) { // Limite de 2 Mo
            die("Erreur : La taille de l'image dépasse la limite de 2 Mo.");
        }

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
            // Si le fichier est correctement uploadé, on continue
            $image_produit = str_replace(__DIR__ . '/../../', '', $target_file); // Chemin relatif à enregistrer
        } else {
            die("Erreur lors du téléchargement de l'image. Vérifiez les permissions du dossier cible : $target_dir");
        }

        // Préparation des données
        $id_utilisateur = $_SESSION['id_utilisateur'];
        $date_creation = date('Y-m-d H:i:s');

        // Requête SQL pour insérer les données
        $requete = "INSERT INTO `produit` 
                    (`nom_produit`, `description`, `prix_unitaire`, `categorie_id`, `prix_achat`, `prix_vente`, `fournisseur_id`, `date_creation`, `statut`, `image_produit`, `id_utilisateur`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $connexion->prepare($requete);

        // Vérifier si la requête a été correctement préparée
        if (!$stmt) {
            die("Erreur dans la préparation de la requête : " . $connexion->error);
        }

        // Liaison des paramètres avec `bind_param()`
        $stmt->bind_param(
            'ssdiidssssi',
            $nom_produit,
            $description,
            $prix_unitaire,
            $categorie_id,
            $prix_achat,
            $prix_vente,
            $fournisseur_id,
            $date_creation,
            $statut,
            $image_produit,
            $id_utilisateur
        );

        // Exécution de la requête
        if ($stmt->execute()) {
            return $stmt;
        } else {
            return "Erreur lors de l'ajout du produit : " . $stmt->error;
        }
    } catch (Exception $e) {
        die("Erreur lors de l'ajout du produit : " . $e->getMessage());
    }
}





/********************************************************************************** 
Récuperer la liste de tous les produits 
 **********************************************************************************/
function get_all_produit()
{
    global $connexion, $items_per_page2, $offset2;
    // $items_per_page = 20;
    $requete = "SELECT * FROM produit JOIN categorie ON categorie.id_categorie = produit.categorie_id JOIN fournisseur ON fournisseur.id_fournisseur = produit.fournisseur_id LIMIT $items_per_page2 OFFSET $offset2";
    // $requete = "SELECT * FROM produit LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Récuperer la liste de tous les produits sans pagination 
 **********************************************************************************/
function get_all_produit_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM produit";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule produit selon son id 
 **********************************************************************************/
function get_one_produit($id_produit)
{
    global $connexion;
    $requete = "SELECT produit.*, categorie.nom_categorie, fournisseur.nom_fournisseur FROM produit JOIN categorie ON categorie.id_categorie = produit.categorie_id JOIN fournisseur ON fournisseur.id_fournisseur = produit.fournisseur_id where id_produit ='$id_produit'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une produit selon son id
 **********************************************************************************/
function update_produit($nom_produit, $description, $prix_unitaire, $categorie_id, $prix_achat, $prix_vente, $fournisseur_id, $statut, $image_file, $id_produit)
{
    global $connexion;
    if ($image_file) {
        $target_dir = '../../assets/img/';
        if (!is_dir($target_dir)) {
            die("Erreur : Le dossier cible n'existe pas ou n'est pas accessible : $target_dir");
        }

        // Nom unique pour l'image
        $image_name = time() . '_' . basename($image_file['name']);
        $target_file = $target_dir . $image_name;

        // Vérification des extensions autorisées
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($image_file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            die("Erreur : Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
        }

        // Vérification de la taille du fichier (limite de 2 Mo)
        if ($image_file['size'] > 2 * 1024 * 1024) { // Limite de 2 Mo
            die("Erreur : La taille de l'image dépasse la limite de 2 Mo.");
        }

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
            // Si le fichier est correctement uploadé, on continue
            $image_file = str_replace(__DIR__ . '/../../', '', $target_file); // Chemin relatif à enregistrer
        } else {
            die("Erreur lors du téléchargement de l'image. Vérifiez les permissions du dossier cible : $target_dir");
        }
        $requete = "UPDATE produit SET nom_produit = ?, description = ?, prix_unitaire = ?, categorie_id = ?, prix_achat = ?, prix_vente = ?, fournisseur_id = ?, statut = ?, image_produit = ? WHERE id_produit = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param("ssiiiiissi", $nom_produit, $description, $prix_unitaire, $categorie_id, $prix_achat, $prix_vente, $fournisseur_id, $statut, $image_file, $id_produit);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Erreur d'exécution : " . $stmt->error;
        }
    } else {
        $requete = "UPDATE produit SET nom_produit = ?, description = ?, prix_unitaire = ?, categorie_id = ?, prix_achat = ?, prix_vente = ?, fournisseur_id = ?, statut = ? WHERE id_produit = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param("ssiiiiisi", $nom_produit, $description, $prix_unitaire, $categorie_id, $prix_achat, $prix_vente, $fournisseur_id, $statut, $id_produit);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Erreur d'exécution : " . $stmt->error;
        }
    }
}

/**********************************************************************************
Supprimer une Produit selon son ID
 **********************************************************************************/
function delete_produit($id_produit)
{
    global $connexion;
    $requete = "DELETE FROM produit WHERE id_produit = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_produit);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune Fournisseur trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table produit
 **********************************************************************************/
function get_search_produit($search)
{
    global $connexion;
    $requete = "SELECT * FROM produit JOIN categorie ON categorie.id_categorie = produit.categorie_id WHERE nom_produit LIKE '%$search%' OR produit.statut LIKE '%$search%' OR nom_categorie LIKE '%$search%' OR prix_vente LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE PRODUIT) ********************************* */


/***********************FIN (LES FONCTION DE LA TABLE CATEGORIE) ********************************* */
/********************************************************************************** 
La liste de tous les categories 
 **********************************************************************************/
function get_all_category()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT * FROM categorie LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les categories sans la pagination
 **********************************************************************************/
function get_all_category_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM categorie";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule categorie selon son id 
 **********************************************************************************/
function get_one_category($id_categorie)
{
    global $connexion;
    $requete = "SELECT * FROM categorie where id_categorie ='$id_categorie'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une catégorie selon son id
 **********************************************************************************/
function update_category($nom_categorie, $statut, $description, $id_categorie)
{
    global $connexion;
    $requete = "UPDATE categorie SET nom_categorie = ?, statut = ?, description = ? WHERE id_categorie = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("sisi", $nom_categorie, $statut, $description, $id_categorie);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/********************************************************************************** 
Ajouter un nouveau catégorie
 ********************************************************************************* */
function add_categorie($nom_categorie, $description, $statut)
{
    global $connexion;
    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $date = date('Y-m-d H:i:s');
    $requete = "INSERT INTO `categorie` (`nom_categorie`, `description`, `date_creation`, `statut`, `id_utilisateur`) VALUES ('$nom_categorie', '$description', '$date', '$statut', '$id_utilisateur')";
    $result = $connexion->prepare($requete);
    return $result->execute();
}

/**********************************************************************************
Supprimer une catégorie selon son ID
 **********************************************************************************/
function delete_category($id_categorie)
{
    global $connexion;
    $requete = "DELETE FROM categorie WHERE id_categorie = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_categorie);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune catégorie trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table categorie
 **********************************************************************************/
function get_search_categorie($search)
{
    global $connexion;
    $requete = "SELECT * FROM categorie WHERE nom_categorie LIKE '%$search%' OR statut LIKE '%$search%' OR date_creation LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE CATEGORIE) ********************************* */

/***********************DEBUT (LES FONCTION DE LA TABLE FOURNISSEUR) ********************************* */
/********************************************************************************** 
La liste de tous les fournisseur 
 **********************************************************************************/
function get_all_fournisseur()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT * FROM fournisseur LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les fournisseur sans la pagination
 **********************************************************************************/
function get_all_fournisseur_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM fournisseur";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule fournisseur selon son id 
 **********************************************************************************/
function get_one_fournisseur($id_fournisseur)
{
    global $connexion;
    $requete = "SELECT * FROM fournisseur where id_fournisseur ='$id_fournisseur'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une fournisseur selon son id
 **********************************************************************************/
function update_fournisseur($nom_fournisseur, $adresse, $telephone, $email, $site_web, $contact_personne, $statut, $id_fournisseur)
{

    global $connexion;
    $requete = "UPDATE fournisseur SET nom_fournisseur = ?, adresse = ?, telephone = ?, email = ?, site_web = ?, contact_personne = ?, statut = ? WHERE id_fournisseur = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("sssssssi", $nom_fournisseur, $adresse, $telephone, $email, $site_web, $contact_personne, $statut, $id_fournisseur);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/********************************************************************************** 
Ajouter un nouveau fournisseur
 ********************************************************************************* */
function add_fournisseur($nom_fournisseur, $adresse, $telephone, $email, $site_web, $contact_personne, $statut)
{
    global $connexion;
    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $date_creation = date('Y-m-d H:i:s');
    $requete = "INSERT INTO `fournisseur` (`nom_fournisseur`, `adresse`, `telephone`, `email`, `site_web`, `contact_personne`, `date_creation`, `statut`, `id_utilisateur`) VALUES ('$nom_fournisseur', '$adresse', '$telephone', '$email', '$site_web', '$contact_personne', '$date_creation', '$statut', '$id_utilisateur')";
    $result = $connexion->prepare($requete);
    return $result->execute();
}

/**********************************************************************************
Supprimer une Fournisseur selon son ID
 **********************************************************************************/
function delete_fournisseur($id_fournisseur)
{
    global $connexion;
    $requete = "DELETE FROM fournisseur WHERE id_fournisseur = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_fournisseur);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune Fournisseur trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table fournisseur
 **********************************************************************************/
function get_search_fournisseur($search)
{
    global $connexion;
    $requete = "SELECT * FROM fournisseur WHERE nom_fournisseur LIKE '%$search%' OR telephone LIKE '%$search%' OR contact_personne LIKE '%$search%' OR adresse LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE FOURNISSEUR) ********************************* */


/***********************DEBUT (LES FONCTION DE LA TABLE STOCK) ********************************* */
/********************************************************************************** 
La liste de tous les fournisseur 
 **********************************************************************************/
function get_all_stock()
{
    global $connexion, $items_per_page, $offset;
    $items_per_page = 8;
    $requete = "SELECT * FROM stock JOIN produit ON produit.id_produit = stock.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les stock sans la pagination
 **********************************************************************************/
function get_all_stock_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM stock JOIN produit ON produit.id_produit = stock.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule stock selon son id 
 **********************************************************************************/
function get_one_stock($id_stock)
{
    global $connexion;
    $requete = "SELECT * FROM stock JOIN produit ON produit.id_produit = stock.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id where id_stock ='$id_stock'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une stock selon son id
 **********************************************************************************/
function update_stock($produit_id, $quantite_disponible, $emplacement_produit, $id_stock)
{

    global $connexion;
    $date_mise_a_jour = date('Y-m-d H:i:s');
    $requete = "UPDATE stock SET produit_id = ?, quantite_disponible = ?, emplacement_produit = ?, date_mise_a_jour = ? WHERE id_stock = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("iissi", $produit_id, $quantite_disponible, $emplacement_produit, $date_mise_a_jour, $id_stock);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

function update_quantite_stock($quantite_disponible, $id_stock)
{

    global $connexion;
    $date_mise_a_jour = date('Y-m-d H:i:s');
    $requete = "UPDATE stock SET quantite_disponible = ? WHERE id_stock = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("ii", $quantite_disponible, $id_stock);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/********************************************************************************** 
Ajouter un nouveau stock
 ********************************************************************************* */
function add_stock($produit_id, $quantite_disponible, $emplacement_produit)
{
    global $connexion;
    $date_ajout = $date_mise_a_jour = date('Y-m-d H:i:s');
    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $requete = "INSERT INTO `stock` (`produit_id`, `quantite_disponible`, `emplacement_produit`, `date_ajout`, `date_mise_a_jour`, `id_utilisateur`) VALUES ('$produit_id', '$quantite_disponible', '$emplacement_produit', '$date_ajout', '$date_mise_a_jour', '$id_utilisateur')";
    $result = $connexion->prepare($requete);
    return $result->execute();
}

/**********************************************************************************
Supprimer une stock selon son ID
 **********************************************************************************/
function delete_stock($id_stock)
{
    global $connexion;
    $requete = "DELETE FROM stock WHERE id_stock = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_stock);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune stock trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table stock
 **********************************************************************************/
function get_search_stock($search)
{
    global $connexion;
    $requete = "SELECT * FROM stock JOIN produit ON produit.id_produit = stock.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id WHERE nom_produit LIKE '%$search%' OR prix_vente LIKE '%$search%' OR nom_categorie LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE STOCK) ********************************* */



/***********************DEBUT (LES FONCTION DE LA TABLE CLIENT) ********************************* */
/********************************************************************************** 
La liste de tous les fournisseur 
 **********************************************************************************/
function get_all_client()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT * FROM client LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les client sans la pagination
 **********************************************************************************/
function get_all_client_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM client";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule client selon son id 
 **********************************************************************************/
function get_one_client($id_client)
{
    global $connexion;
    $requete = "SELECT * FROM client where id_client ='$id_client'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une client selon son id
 **********************************************************************************/
function update_client($nom_client, $prenom_client, $email_client, $telephone_client, $adresse_client, $statut, $id_client)
{

    global $connexion;
    $requete = "UPDATE client SET nom_client = ?, prenom_client = ?, email_client = ?, telephone_client = ?, adresse_client = ?, statut = ? WHERE id_client = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("sssssii", $nom_client, $prenom_client, $email_client, $telephone_client, $adresse_client, $statut, $id_client);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/********************************************************************************** 
Ajouter un nouveau client
 ********************************************************************************* */
function add_client($nom_client, $prenom_client, $email_client, $telephone_client, $adresse_client, $statut)
{
    global $connexion;
    $date_creation = date('Y-m-d H:i:s');
    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $requete = "INSERT INTO `client` (`nom_client`, `prenom_client`, `email_client`, `telephone_client`, `adresse_client`, `date_creation`, `statut`, `id_utilisateur`) VALUES ('$nom_client', '$prenom_client', '$email_client', '$telephone_client', '$adresse_client', '$date_creation', '$statut', '$id_utilisateur')";
    $result = $connexion->prepare($requete);
    return $result->execute();
}

/**********************************************************************************
Supprimer une client selon son ID
 **********************************************************************************/
function delete_client($id_client)
{
    global $connexion;
    $requete = "DELETE FROM client WHERE id_client = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_client);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune client trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table client
 **********************************************************************************/
function get_search_client($search)
{
    global $connexion;
    $requete = "SELECT * FROM client WHERE nom_client LIKE '%$search%' OR prenom_client LIKE '%$search%' OR email_client LIKE '%$search%' OR telephone_client LIKE '%$search%' OR adresse_client LIKE '%$search%' OR statut LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE CLIENT) ********************************* */



/***********************DEBUT (LES FONCTION DE LA TABLE UTILISATEURS) ********************************* */
/********************************************************************************** 
La liste de tous les utilisateur 
 **********************************************************************************/
function get_all_utilisateur()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT DISTINCT utilisateur.* FROM utilisateur LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les utilisateur sans la pagination
 **********************************************************************************/
function get_all_utilisateur_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM utilisateur";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule utilisateur selon son id 
 **********************************************************************************/
function get_one_utilisateur($id_utilisateur)
{
    global $connexion;
    $requete = "SELECT * FROM utilisateur where id_utilisateur ='$id_utilisateur'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une utilisateur selon son id
 **********************************************************************************/
function update_utilisateur($nom_utilisateur, $prenom_utilisateur, $telephone_utilisateur, $email_utilisateur, $role, $statut, $id_utilisateur)
{

    global $connexion;
    $requete = "UPDATE utilisateur SET nom = ?, prenom = ?, telephone = ?, email = ?, role = ?, statut = ? WHERE id_utilisateur = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("ssssssi", $nom_utilisateur, $prenom_utilisateur, $telephone_utilisateur, $email_utilisateur, $role, $statut, $id_utilisateur);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/********************************************************************************** 
Ajouter un nouveau utilisateur
 ********************************************************************************* */
function inscrire_utilisateur($nom, $prenom, $telephone, $email, $login, $mot_de_passe, $role = 'employe', $statut)
{
    // Connexion à la base de données
    global $connexion;

    // Vérifier si l'login existe déjà
    $sql_verification = "SELECT * FROM utilisateur WHERE login = ?";
    $stmt_verification = $connexion->prepare($sql_verification);

    if (!$stmt_verification) {
        die("Erreur dans la préparation de la requête : " . $connexion->error);
    }

    $stmt_verification->bind_param("s", $login);
    $stmt_verification->execute();
    $result_verification = $stmt_verification->get_result();

    if ($result_verification->num_rows > 0) {
        return "Cet login est déjà utilisé.";
    }

    // Si l'login n'existe pas, insérer un nouvel utilisateur
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT); // Hachage du mot de passe

    $sql_ajout = "INSERT INTO utilisateur (nom, prenom, telephone, email, login, mot_de_passe, role, statut) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_ajout = $connexion->prepare($sql_ajout);

    if (!$stmt_ajout) {
        die("Erreur dans la préparation de la requête : " . $connexion->error);
    }

    // Lier les paramètres
    $stmt_ajout->bind_param("ssssssss", $nom, $prenom, $telephone, $email, $login, $mot_de_passe_hash, $role, $statut);

    // Exécuter la requête
    if ($stmt_ajout->execute()) {
        return $stmt_ajout;
    } else {
        return "Erreur lors de l'inscription : " . $connexion->error;
    }
}

/**********************************************************************************
Supprimer une utilisateur selon son ID
 **********************************************************************************/
function delete_utilisateur($id_utilisateur)
{
    global $connexion;
    $requete = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_utilisateur);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune utilisateur trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table utilisateur
 **********************************************************************************/
function get_search_utilisateur($search)
{
    global $connexion;
    $requete = "SELECT * FROM utilisateur WHERE nom LIKE '%$search%' OR prenom LIKE '%$search%' OR dernier_acces LIKE '%$search%' OR telephone LIKE '%$search%' OR role LIKE '%$search%' OR statut LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE UTLISATEURS) ********************************* */


/***********************DEBUT (LES FONCTION DE LA TABLE COMMANDE) ********************************* */
/********************************************************************************** 
La liste de tous les Commande 
 **********************************************************************************/
function get_all_commande()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT * FROM commande JOIN client ON client.id_client = commande.id_client LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les commande sans la pagination
 **********************************************************************************/
function get_all_commande_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM commande";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule commande selon son id 
 **********************************************************************************/
function get_one_commande($id_commande)
{
    global $connexion;
    $requete = "SELECT * FROM commande where id_commande ='$id_commande'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/********************************************************************************** 
Toute les les information d'une seule commande selon son id 
 **********************************************************************************/
function all_info_commande($id_commande)
{
    global $connexion;
    $requete = "SELECT * FROM commande where id_commande ='$id_commande'";
    $resultat = $connexion->query($requete);
    return $resultat->fetch_assoc();
}

/**********************************************************************************
Modifier les informations d'une commande selon son id
 **********************************************************************************/
function update_commande($nom_commande, $prenom_commande, $email_commande, $telephone_commande, $adresse_commande, $statut, $id_commande)
{

    global $connexion;
    $requete = "UPDATE commande SET nom_commande = ?, prenom_commande = ?, email_commande = ?, telephone_commande = ?, adresse_commande = ?, statut_commande = ? WHERE id_commande = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("sssssii", $nom_commande, $prenom_commande, $email_commande, $telephone_commande, $adresse_commande, $statut, $id_commande);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}
function update_statut_commande($id_commande)
{

    global $connexion;
    $requete = "UPDATE commande SET statut_commande = ? WHERE id_commande = ?";
    $stmt = $connexion->prepare($requete);
    $statut = "validee";
    $stmt->bind_param("si", $statut, $id_commande);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/********************************************************************************** 
Ajouter un nouveau commande
 ********************************************************************************* */
// function add_commande($id_client)
// {
//     global $connexion;
//     $date_commande = date('Y-m-d H:i:s');
//     $requete = "INSERT INTO `commande` (`id_client`,`date_commande`,`montant_total`,`statut_commande`, `user`) VALUES ('$id_client', '$date_commande', '0', 'encoure', 'admin')";
//     $result = $connexion->prepare($requete);
//     return $result->execute();
// }

function add_commande($id_client, $type_commande)
{
    global $connexion;

    // Préparer les données
    $date_commande = date('Y-m-d H:i:s');
    $prefix = "MDD"; // Préfixe pour les commandes
    $suffix = str_pad(rand(1, 99999), 5, "0", STR_PAD_LEFT); // Génère '00001' à '99999'
    $numero_commande = $prefix . '-' . date("Ymd") . '-' . $suffix; // Format ex: CMD20241123000123

    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $requete = "INSERT INTO `commande` (`id_client`, `type_commande`, `date_commande`, `statut_commande`, `id_utilisateur`, `numero_commande`) 
                VALUES ('$id_client', '$type_commande', '$date_commande', 'en_attente', '$id_utilisateur', '$numero_commande')";

    // Exécuter la requête
    if (mysqli_query($connexion, $requete)) {
        // Récupérer l'ID de la commande ajoutée
        $commande_id = mysqli_insert_id($connexion);

        // Requête pour récupérer toutes les informations de la commande ajoutée
        $requete_info = "SELECT * FROM `commande` WHERE `id_commande` = $commande_id";
        $result_info = mysqli_query($connexion, $requete_info);

        if ($result_info) {
            return mysqli_fetch_assoc($result_info); // Retourne les informations de la commande
        } else {
            return false; // Erreur lors de la récupération des informations
        }
    } else {
        return false; // Erreur lors de l'insertion
    }
}



/**********************************************************************************
Supprimer une commande selon son ID
 **********************************************************************************/
function delete_commande($id_commande)
{
    global $connexion;
    $requete = "DELETE FROM commande WHERE id_commande = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_commande);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune commande trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table commande
 **********************************************************************************/
function get_search_commande($search)
{
    global $connexion;
    $requete = "SELECT * FROM commande JOIN client ON client.id_client = commande.id_client WHERE id_commande LIKE '%$search%' OR nom_client LIKE '%$search%' OR prenom_client LIKE '%$search%' OR statut_commande LIKE '%$search%' OR adresse_client LIKE '%$search%' OR telephone_client LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}
/***********************FIN (LES FONCTION DE LA TABLE COMMANDE) ********************************* */


/***********************DEBUT (LES FONCTION DE LA TABLE LIDE DE COMMANDE) ********************************* */
/********************************************************************************** 
La liste de toutes les lignes de Commande 
 **********************************************************************************/
function get_all_ligne_commande()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT * FROM ligne_commande JOIN client ON client.id_client = ligne_commande.id_client LIMIT $items_per_page OFFSET $offset";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
La liste de tous les ligne_commande sans la pagination
 **********************************************************************************/
function get_all_ligne_commande_no_pagination()
{
    global $connexion;
    $requete = "SELECT * FROM ligne_commande";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information d'une seule ligne_commande selon son id 
 **********************************************************************************/
function get_one_ligne_commande($id_ligne_commande)
{
    global $connexion;
    $requete = "SELECT ligne_commande.id_commande, ligne_commande.id_ligne_commande, produit.nom_produit FROM ligne_commande JOIN stock ON stock.id_stock=ligne_commande.id_stock JOIN produit ON produit.id_produit = stock.produit_id where id_commande ='$id_ligne_commande'";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/********************************************************************************** 
Les information des lignes de commandes selon l'id du commande
 **********************************************************************************/
function get_all_ligne_commande_by_commande2($id_commande)
{
    global $connexion;
    $requete = "SELECT * FROM `ligne_commande` JOIN stock ON stock.id_stock = ligne_commande.id_stock JOIN produit ON produit.id_produit = stock.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id JOIN commande ON commande.id_commande=ligne_commande.id_commande JOIN client ON client.id_client = commande.id_client JOIN paiement ON paiement.id_commande = commande.id_commande WHERE commande.id_commande='$id_commande'";
    $resultat = $connexion->query($requete);
    return $resultat;
}
function get_all_ligne_commande_by_commande($id_commande)
{
    global $connexion;
    $requete = "SELECT * FROM `ligne_commande` JOIN stock ON stock.id_stock = ligne_commande.id_stock JOIN produit ON produit.id_produit = stock.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id JOIN commande ON commande.id_commande=ligne_commande.id_commande JOIN client ON client.id_client = commande.id_client WHERE commande.id_commande='$id_commande'";
    $resultat = $connexion->query($requete);
    return $resultat;
}

/**********************************************************************************
Modifier les informations d'une ligne_commande selon son id
 **********************************************************************************/
function update_ligne_commande($nom_ligne_commande, $prenom_ligne_commande, $email_ligne_commande, $telephone_ligne_commande, $adresse_ligne_commande, $statut, $id_ligne_commande)
{

    global $connexion;
    $requete = "UPDATE ligne_commande SET nom_ligne_commande = ?, prenom_ligne_commande = ?, email_ligne_commande = ?, telephone_ligne_commande = ?, adresse_ligne_commande = ?, statut = ? WHERE id_ligne_commande = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("sssssii", $nom_ligne_commande, $prenom_ligne_commande, $email_ligne_commande, $telephone_ligne_commande, $adresse_ligne_commande, $statut, $id_ligne_commande);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
Modifier la quantite d'une ligne_commande selon son id
 **********************************************************************************/
function update_quantite_ligne_commande($prix_reduction, $quantite, $id_ligne_commande)
{
    global $connexion;

    // Correction de la requête : un seul SET, colonnes séparées par des virgules
    $requete = "UPDATE ligne_commande SET prix_reduction = ?, quantite = ? WHERE id_ligne_commande = ?";

    $stmt = $connexion->prepare($requete);

    if (!$stmt) {
        return "Erreur de préparation : " . $connexion->error;
    }

    // Liaison des paramètres : "iii" pour trois entiers
    $stmt->bind_param("iii", $prix_reduction, $quantite, $id_ligne_commande);

    // Exécution de la requête
    if ($stmt->execute()) {
        return true; // Succès
    } else {
        return "Erreur d'exécution : " . $stmt->error; // Échec
    }
}


/********************************************************************************** 
Ajouter un nouveau ligne_commande
 ********************************************************************************* */
function add_ligne_commande($id_commande, $id_produit)
{
    global $connexion;
    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $requete = "INSERT INTO `ligne_commande` (`id_commande`,`id_stock`,`quantite`, `id_utilisateur`) VALUES ('$id_commande', '$id_produit', '1','$id_utilisateur')";
    $result = $connexion->prepare($requete);
    return $result->execute();
}

/**********************************************************************************
Supprimer une ligne_commande selon son ID
 **********************************************************************************/
function delete_ligne_commande($id_ligne_commande)
{
    global $connexion;
    $requete = "DELETE FROM ligne_commande WHERE id_ligne_commande = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("i", $id_ligne_commande);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return "Aucune ligne_commande trouvée avec cet ID.";
        }
    } else {
        return "Erreur d'exécution : " . $stmt->error;
    }
}

/**********************************************************************************
fonction pour rechercher sur la table ligne_commande
 **********************************************************************************/
function get_search_ligne_commande($search)
{
    global $connexion;
    $requete = "SELECT * FROM ligne_commande JOIN produit ON produit.id_produit = ligne_commande.produit_id JOIN categorie ON categorie.id_categorie = produit.categorie_id WHERE nom_produit LIKE '%$search%' OR prix_vente LIKE '%$search%' OR nom_categorie LIKE '%$search%'";
    $resultat = $connexion->query($requete);
    if ($resultat->num_rows > 0) {
        return $resultat;
    } else {
        return "AUCUNE RESULTAT TROUVÉE.";
    }
}

/********************************************************************************** 
Ajouter un nouveau paiement
 ********************************************************************************* */
function add_paiement($id_commande, $mode_paiement, $transaction, $montant_ht, $tva, $montant_ttc)
{
    global $connexion;
    session_start();
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $requete = "INSERT INTO `paiement` (`id_commande`,`mode_paiement`,`transaction`,`montant_ht`, `tva`, `montant_ttc`, `id_utilisateur`) VALUES ('$id_commande', '$mode_paiement', '$transaction', '$montant_ht', '$tva', '$montant_ttc', '$id_utilisateur')";
    $result = $connexion->prepare($requete);
    return $result->execute();
}

/********************************************************************************** 
La liste de toutes les paiement de Commande 
 **********************************************************************************/
function get_all_paiement()
{
    global $connexion, $items_per_page, $offset;
    $requete = "SELECT * FROM paiement JOIN commande ON commande.id_commande = paiement.id_commande";
    $resultat = $connexion->query($requete);
    return $resultat;
}
/***********************FIN (LES FONCTION DE LA TABLE LIGNE DE COMMANDE) ********************************* */


/********************************************************************************** 
Fonction d'affichage du format date
 ********************************************************************************* */
function dateFromat($date)
{
    $timestamp = strtotime($date);
    $date_formatee = date("Y-m-d", $timestamp);
    return $date_formatee;
}
