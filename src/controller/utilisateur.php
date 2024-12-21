<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header("Location: ../../index.php");
  exit();
}
// Condition d'ajout utilisateur
if (isset($_POST['role'])) {
    try {
        // inscrire_utilisateur($nom, $prenom, $telephone, $email, $login, $mot_de_passe, $role = 'employe', $statut)
        $result  = inscrire_utilisateur($_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['email'], $_POST['telephone'], $_POST['telephone'], $_POST['role'], $_POST['statut']);
        if (is_object($result)) {
            return header('Location:../view/utilisateur.php?success=UTILISATEUR AJOUTER AVEC SUCCESS ');
        } else if ($result == "Cet login est déjà utilisé.") {
            return header('Location:../view/utilisateur.php?error_add=CET LOGIN EST DEJA UTILISE.');
        }
    } catch (Exception $e) {
        return header('Location:../view/utilisateur.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER' . $e->getMessage());
    }
}

// Condition d'ouverture du modal pour modifer un utilisateur
if (isset($_GET['put_id_utilisateur'])) {
    $put_id_utilisateur = $_GET['put_id_utilisateur'];
    $put_data_utilisateur = http_build_query(['put_data_utilisateur' => get_one_utilisateur($put_id_utilisateur)]);
    return header('Location:../view/utilisateur.php?openModal=put_utilisateur&put_id_utilisateur=' . $put_id_utilisateur . '&put_data_utilisateur=' . $put_data_utilisateur);
}

// Condition pour modifier un utilisateur
if (isset($_POST['put_nom'])) {
    try {
        // update_utilisateur($nom_utilisateur, $prenom_utilisateur, $telephone_utilisateur, $email_utilisateur, $role, $statut, $id_utilisateur)
        $result = update_utilisateur($_POST['put_nom'], $_POST['put_prenom'], $_POST['put_telephone'], $_POST['put_email'], $_POST['put_role'], $_POST['put_statut'], $_POST['put_id']);
        if ($result == true) {
            return header('Location:../view/utilisateur.php?update_success=UTILISATEUR MODIFIER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
    }
}

// // Ouverture modal delete utilisateur
// if (isset($_GET['delete_id_utilisateur'])) {
//     $delete_id_utilisateur = $_GET['delete_id_utilisateur'];
//     return header('Location:../view/utilisateur.php?openModal=delete_utilisateur&delete_id_utilisateur=' . $delete_id_utilisateur);
// }

// // Suppression utilisateur
// if (isset($_GET['delete_id_utilisateur2'])) {
//     try {
//         $result = delete_utilisateur($_GET['delete_id_utilisateur2']);
//         if ($result == true) {
//             return header('Location:../view/utilisateur.php?delete_success=utilisateur SUPPRIMER AVEC SUCCESS ');
//         }
//     } catch (Exception $e) {
//         print_r($e->getMessage());
//         // return header('Location:../view/utilisateur.php?delete_error=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
//     }
// }
