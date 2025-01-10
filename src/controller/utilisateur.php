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
            return header('Location:../view/utilisateur.php?error_add=CE LOGIN EXISTE DÉJA, MERCI DE RESSAYER UN AUTRE.');
        }
    } catch (Exception $e) {
        return header('Location:../view/utilisateur.php?error_add=' . $e->getMessage());
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
        return header('Location:../view/utilisateur.php?error_update='.$e->getMessage());
    }
}

// Ouverture modal modifier mon profil
if (isset($_GET['get_id_utilisateur'])) {
    $get_id_utilisateur = $_GET['get_id_utilisateur'];
    $get_utilisateur = http_build_query(['get_utilisateur' => get_one_utilisateur($get_id_utilisateur)]);
    return header('Location:../view/accueil.php?openModal=get_utilisateur&get_id_utilisateur=' . $get_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
}


// Mettre a jour le profil de l'utilisateur
if (isset($_POST['get_id_utilisateur'])) {
    $get_id_utilisateur = $_POST['get_id_utilisateur'];
    $get_utilisateur = http_build_query(['get_utilisateur' => get_one_utilisateur($get_id_utilisateur)]);
    try {
        if (empty($_POST['get_mot_de_passe'])) {
            $result = update_utilisateur_2($_POST['get_telephone'], $_POST['get_email'], $_POST['get_login'], $_POST['get_id_utilisateur']);
            if ($result) {
                return header('Location:../view/accueil.php?openModal=get_utilisateur&success=MODIFIER AVEC SUCCESS&get_id_utilisateur=' . $get_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
            }
        } else if ($_POST['get_mot_de_passe'] == $_POST['get_confirmation']) {
            if (mb_strlen($_POST['get_mot_de_passe']) < 4) {
                return header('Location:../view/accueil.php?openModal=get_utilisateur&error_update=LE MOT DE PASSE DOIT SUPERIEUR OU EGALE A 4 CARACTERE&get_id_utilisateur=' . $get_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
            } else {
                $result = update_utilisateur_3($_POST['get_telephone'], $_POST['get_email'], $_POST['get_login'], $_POST['get_mot_de_passe'], $_POST['get_id_utilisateur']);
                if ($result) {
                    return header('Location:../view/accueil.php?openModal=get_utilisateur&success=MODIFIER AVEC SUCCESS&get_id_utilisateur=' . $get_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
                }
                // print_r("hello world");
                // die;
            }
        } else {
            return header('Location:../view/accueil.php?openModal=get_utilisateur&error_update=LE MOT DE PASSE ET LA CONFIRMATION DOIVENT ETRE IDENTIQUE&get_id_utilisateur=' . $get_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
        }
    } catch (Exception $e) {
        return header('Location:../view/accueil.php?openModal=get_utilisateur&error_update=' . $e->getMessage() . '&get_id_utilisateur=' . $get_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
    }
}

// ouverture modal supprimer user
if (isset($_GET['delete_id_utilisateur'])) {
    $delete_id_utilisateur = $_GET['delete_id_utilisateur'];
    $get_utilisateur = http_build_query(['get_utilisateur' => get_one_utilisateur($delete_id_utilisateur)]);
    return header('Location:../view/utilisateur.php?openModal=delete_utilisateur&delete_id_utilisateur=' . $delete_id_utilisateur . '&get_utilisateur=' . $get_utilisateur);
}


// // Suppression utilisateur
if (isset($_GET['delete_id_utilisateur2'])) {
    try {
        $result = delete_utilisateur($_GET['delete_id_utilisateur2']);
        if ($result == true) {
            return header('Location:../view/utilisateur.php?delete_success=UTILISATEUR SUPPRIMER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
        // return header('Location:../view/utilisateur.php?delete_error=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}
