<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header("Location: ../../index.php");
  exit();
}
// Condition d'ajout
if (isset($_POST['post_nom_categorie'])) {
    try {
        $result  = add_categorie($_POST['post_nom_categorie'], $_POST['description'], $_POST['post_statut'], 'admin');
        if ($result == 1) {
            return header('Location:../view/category.php?success=CATEGORIE AJOUTER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/category.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}

// Condition d'ouverture du modal pour modifer
if (isset($_GET['put_id_categorie'])) {
    $put_id_categorie = $_GET['put_id_categorie'];
    $put_data_categorie = http_build_query(['put_data_categorie' => get_one_category($put_id_categorie)]);
    return header('Location:../view/category.php?openModal=put_categorie&put_id_categorie=' . $put_id_categorie . '&put_data_categorie=' . $put_data_categorie);
}

// Condition pour modifier
if (isset($_POST['put_id_categorie2'])) {
    $result = update_category($_POST['put_nom_categorie'], $_POST['put_statut_categorie'], $_POST['put_description_categorie'], $_POST['put_id_categorie2']);
    if ($result == true) {
        return header('Location:../view/category.php?update_success=CATEGORIE MODIFIER AVEC SUCCESS');
    }
}

// Ouverture du modal de suppression
if (isset($_GET['delete_id_categorie'])) {
    $delete_id_categorie = $_GET['delete_id_categorie'];
    return header('Location:../view/category.php?openModal=delete_categorie&delete_id_categorie=' . $delete_id_categorie);
}

// Suppression categorie
if (isset($_GET['delete_id_categorie2'])) {
    try {
        $result = delete_category($_GET['delete_id_categorie2']);
        if ($result == true) {
            return header('Location:../view/category.php?delete_success=GATEGORIE SUPPRIMER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/category.php?delete_error=Impossible:OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER '); 
    }
}
