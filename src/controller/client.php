<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header("Location: ../../index.php");
  exit();
}
// Condition d'ajout client
if (isset($_POST['nom_client'])) {
    try {
        $result  = add_client($_POST['nom_client'], $_POST['prenom_client'], $_POST['email'], $_POST['telephone'], $_POST['adresse'], $_POST['statut']);
        if ($result == 1) {
            return header('Location:../view/client.php?success=CLIENT AJOUTER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/client.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}

// Condition d'ouverture du modal pour modifer un client
if (isset($_GET['put_id_client'])) {
    $put_id_client = $_GET['put_id_client'];
    $put_data_client = http_build_query(['put_data_client' => get_one_client($put_id_client)]);
    return header('Location:../view/client.php?openModal=put_client&put_id_client=' . $put_id_client . '&put_data_client=' . $put_data_client);
}

// Condition pour modifier un client
if (isset($_POST['put_nom_client'])) {
    try {
        $result = update_client($_POST['put_nom_client'], $_POST['prenom_client'], $_POST['email'], $_POST['telephone'], $_POST['adresse'], $_POST['put_statut_client'], $_POST['put_id_client']);
        if ($result == true) {
            return header('Location:../view/client.php?update_success=CLIENT MODIFIER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
    }
}

// Ouverture modal delete client
if (isset($_GET['delete_id_client'])) {
    $delete_id_client = $_GET['delete_id_client'];
    return header('Location:../view/client.php?openModal=delete_client&delete_id_client=' . $delete_id_client);
}

// Suppression client
if (isset($_GET['delete_id_client2'])) {
    try {
        $result = delete_client($_GET['delete_id_client2']);
        if ($result == true) {
            return header('Location:../view/client.php?delete_success=CLIENT SUPPRIMER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/client.php?delete_error=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}
