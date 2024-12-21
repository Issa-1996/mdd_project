<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header("Location: ../../index.php");
  exit();
}
// Condition d'ajout fournisseur
if (isset($_POST['nom_fournisseur'])) {
    try {
        $result  = add_fournisseur($_POST['nom_fournisseur'], $_POST['adresse'], $_POST['telephone'], $_POST['email'], $_POST['site_web'], $_POST['contact_personne'], $_POST['post_statut']);
        if ($result == 1) {
            return header('Location:../view/fournisseur.php?success=FOURNISSEUR AJOUTER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/fournisseur.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}

// Condition d'ouverture du modal pour modifer un fournisseur
if (isset($_GET['put_id_fournisseur'])) {
    // var_dump($_GET['put_id_fournisseur']); die;
    $put_id_fournisseur = $_GET['put_id_fournisseur'];
    $put_data_fournisseur = http_build_query(['put_data_fournisseur' => get_one_fournisseur($put_id_fournisseur)]);
    return header('Location:../view/fournisseur.php?openModal=put_fournisseur&put_id_fournisseur=' . $put_id_fournisseur . '&put_data_fournisseur=' . $put_data_fournisseur);
}

// Condition pour modifier un fournisseur
if (isset($_POST['put_nom_fournisseur'])) {
    try {
        $result = update_fournisseur($_POST['put_nom_fournisseur'], $_POST['put_adresse'], $_POST['put_telephone'], $_POST['put_email'], $_POST['put_site_web'], $_POST['put_contact_personne'], $_POST['put_statut'], $_POST['put_id_fournisseur']);
        if ($result == true) {
            return header('Location:../view/fournisseur.php?update_success=FOURNISSEUR MODIFIER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
    }
}

// Ouverture modal delete fournisseur
if (isset($_GET['delete_id_fournisseur'])) {
    $delete_id_fournisseur = $_GET['delete_id_fournisseur'];
    return header('Location:../view/fournisseur.php?openModal=delete_fournisseur&delete_id_fournisseur=' . $delete_id_fournisseur);
}

// Suppression fournisseur
if (isset($_GET['delete_id_fournisseur2'])) {
    try {
        $result = delete_fournisseur($_GET['delete_id_fournisseur2']);
        if ($result == true) {
            return header('Location:../view/fournisseur.php?delete_success=FOURNISSEUR RETIRER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/fournisseur.php?delete_error=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}
