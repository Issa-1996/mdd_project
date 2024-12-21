<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
    session_destroy();
    header("Location: ../../index.php");
    exit();
}
// Condition d'ajout produit
if (isset($_POST['nom_produit'])) {
    // print_r($_FILES['image_produit']['name']); die;
    try {
        $result  = add_produit($_POST['nom_produit'], $_POST['description'], $_POST['prix_unitaire'], $_POST['categorie_id'], $_POST['prix_achat'], $_POST['prix_vente'], $_POST['fournisseur_id'], $_POST['post_statut'], $_FILES['image_produit']);
        if ($result == 1) {
            return header('Location:../view/produit.php?success=PRODUIT AJOUTER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
        // return header('Location:../view/produit.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER');
    }
}

// Condition d'ouverture du modal pour modifer un produit
if (isset($_GET['put_id_produit'])) {
    $put_id_produit = $_GET['put_id_produit'];
    $put_data_produit = http_build_query(['put_data_produit' => get_one_produit($put_id_produit)]);
    return header('Location:../view/produit.php?openModal=put_produit&put_id_produit=' . $put_id_produit . '&put_data_produit=' . $put_data_produit);
}

// Condition pour modifier un produit
if (isset($_POST['put_id_categorie2'])) {
    try {
        $result = update_produit($_POST['put_nom_produit'], $_POST['put_description'], $_POST['put_prix_unitaire'], $_POST['put_categorie_id'], $_POST['put_prix_achat'], $_POST['put_prix_vente'], $_POST['put_fournisseur_id'], $_POST['put_statut'], $_FILES['put_image_produit'], $_POST['put_id_categorie2']);
        if ($result == true) {
            return header('Location:../view/produit.php?update_success=PRODUIT MODIFIER AVEC SUCCESS');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
    }
}

// Ouverture modal delete produit
if (isset($_GET['delete_id_produit'])) {
    $delete_id_produit = $_GET['delete_id_produit'];
    return header('Location:../view/produit.php?openModal=delete_produit&delete_id_produit=' . $delete_id_produit);
}

// Suppression produit
if (isset($_GET['delete_id_produit2'])) {
    try {
        $result = delete_produit($_GET['delete_id_produit2']);
        if ($result == true) {
            return header('Location:../view/produit.php?delete_success=PRODUIT SUPPRIMER AVEC SUCCESS');
        }
    } catch (Exception $e) {
        return header('Location:../view/produit.php?delete_error=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER');
    }
}

if (isset($_GET['get_id_produit'])) {
    $id_produit = $_GET['get_id_produit'];
    $get_data_produit = http_build_query(['get_data_produit' => get_one_produit($id_produit)]);
    return header('Location:../view/produit.php?openModal=get_produit&get_id_produit=' . $id_produit . '&get_data_produit=' . $get_data_produit);
}
