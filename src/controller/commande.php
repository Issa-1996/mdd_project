<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
    session_destroy();
    header('Location:  /mmd/index.php');
    exit();
}
// Condition d'ajout commande
if (isset($_POST['id_client'])) {
    try {
        $result  = add_commande($_POST['id_client'], $_POST['type_commande']);
        if ($result) {
            return header('Location:../view/ligne_de_commande0.php?success=COMMANDE AJOUTER AVEC SUCCESS, VEUILLER CHOISIR LES PRODUITS&id_commande=' . $result['id_commande']);
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
        // return header('Location:../view/ligne_de_commande.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}

// Condition d'ouverture du modal pour modifer un commande
if (isset($_GET['put_id_commande'])) {
    $put_id_commande = $_GET['put_id_commande'];
    $info_ligne_commande = get_one_ligne_commande($put_id_commande);
    if ($info_ligne_commande->num_rows == 0) {
        return header('Location:../view/ligne_de_commande0.php?id_commande=' . $put_id_commande);
    } else {
        return header('Location:../view/ligne_de_commande2.php?openModal=put_commande&put_id_commande=' . $put_id_commande);
    }
}


// // Condition pour modifier un commande
// if (isset($_POST['put_id_commande'])) {
//     print_r($_POST); die();
//     try {
//         $result = update_commande($_POST['put_nom_commande'], $_POST['prenom_commande'], $_POST['email'], $_POST['telephone'], $_POST['adresse'], $_POST['put_statut_commande'], $_POST['put_id_commande']);
//         if ($result == true) {
//             return header('Location:../view/commande.php?update_success=commande MODIFIER AVEC SUCCESS ');
//         }
//     } catch (Exception $e) {
//         print_r($e->getMessage());
//     }
// }

// Ouverture modal delete commande
if (isset($_GET['delete_id_commande'])) {
    $delete_id_commande = $_GET['delete_id_commande'];
    return header('Location:../view/commande.php?openModal=delete_commande&delete_id_commande=' . $delete_id_commande);
}

// Suppression commande
if (isset($_GET['delete_id_commande2'])) {
    try {
        $result = delete_commande($_GET['delete_id_commande2']);
        if ($result == true) {
            return header('Location:../view/commande.php?delete_success=COMMANDE SUPPRIMER AVEC SUCCESS ');
        }
    } catch (Exception $e) {
        return header('Location:../view/commande.php?delete_error=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}

// Condition d'ouverture du modal pour detail d'une ligne de commande
if (isset($_POST['id_commande'])) {
    try {
        $id_commande = $_POST['id_commande'];
        $compt_error = 0;
        foreach ($_POST['produit_id'] as $produit_id) {
            if ($produit_id) {
                $result = add_ligne_commande($id_commande, $produit_id);
            } else {
                $compt_error++;
            }
        }
        if ($compt_error == 0) {
            return header('Location:../view/ligne_de_commande2.php?success=PRODUITS AJOUTER AVEC SUCCESS, VEUILLER INDIQUER LES QUANTITES&id_commande=' . $id_commande);
        }
    } catch (Exception $e) {
        return header('Location:../view/ligne_de_commande2.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER ');
    }
}

// Modifier la Quante produit selectionner pour la commande
if (isset($_POST['id_ligne_commande'])) {
    foreach ($_POST['id_ligne_commande'] as $key => $id_ligne_commande) {
        $quantite = $_POST['quantite'][$key];
        $quantite_disponible = $_POST['quantite_disponible'][$key];
        $quantite_dispo = $quantite_disponible - $quantite;
        $reduction = $_POST['prix_reduction'][$key];
        $id_stock = $_POST['id_stock'][$key];
        $info_commande = get_one_commande($_POST['one_commande']);
        if ($info_commande['type_commande'] == 'vente') {
            update_quantite_stock($quantite_dispo, $id_stock);
        }
        $result = update_quantite_ligne_commande($reduction, $quantite, $id_ligne_commande);
    }
    if ($result) {
        return header('Location:../view/ligne_de_commande3.php?success=VEUILLER CHOISIR LE MODE DE PAIEMENT&id_commande=' . $_POST['one_commande']);
    }
}

// MODE DE PAIEMENT
if (isset($_POST['id_commande_produit_paie'])) {
    try {
        $montant_total = $_POST['montant_total'];
        $tva = $montant_total * 0.18;
        $montant_total_ttc = $montant_total;
        $id_commande = $_POST['id_commande_produit_paie'];
        $montant_total = $montant_total - $tva;
        update_statut_commande($id_commande);
        if ($_POST['mode_paiement'] == 'wave') {
            $result = add_paiement($id_commande, 'wave', $_POST['transaction_wave'], $montant_total, $tva, $montant_total_ttc);
            if ($result) {
                return header('Location:../view/ligne_de_commande4.php?success=PAIEMENT VALIDER AVEC SUCCESS !!!&id_commande=' . $id_commande);
            }
        } else if ($_POST['mode_paiement'] == 'om') {
            $result = add_paiement($id_commande, 'orange_money', $_POST['transaction_om'], $montant_total, $tva, $montant_total_ttc);
            if ($result) {
                return header('Location:../view/ligne_de_commande4.php?success=PAIEMENT VALIDER AVEC SUCCESS !!!&id_commande=' . $id_commande);
            }
        } else if ($_POST['mode_paiement'] == 'virement') {
            $result = add_paiement($id_commande, 'virement', $_POST['transaction_virement'], $montant_total, $tva, $montant_total_ttc);
            if ($result) {
                return header('Location:../view/ligne_de_commande4.php?success=PAIEMENT VALIDER AVEC SUCCESS !!!&id_commande=' . $id_commande);
            }
        } else if ($_POST['mode_paiement'] == 'especes') {
            $result = add_paiement($id_commande, 'especes', NULL, $montant_total, $tva, $montant_total_ttc);
            if ($result) {
                return header('Location:../view/ligne_de_commande4.php?success=PAIEMENT VALIDER AVEC SUCCESS !!!&id_commande=' . $id_commande);
            }
        } else {
            $result = add_paiement($id_commande, '', NULL, $montant_total, $tva, $montant_total_ttc);
            if ($result) {
                return header('Location:../view/ligne_de_commande4.php?success=FACTURE PROFORMAT POUR TELECHARGER OU IMPRIMER !!!&id_commande=' . $id_commande);
            }
        }
    } catch (Exception $e) {
        $chaine = $e->getMessage();
        $mot = "Duplicate";
        if (strpos($chaine, $mot) !== false) {
            return header('Location:../view/ligne_de_commande4.php?error_add=OUPS => PAIEMENT DEJA VALIDER!!!&id_commande=' . $id_commande);
        } else {
            return header('Location:../view/ligne_de_commande4.php?error_add=OUPS => UNE ERREUR S\'EST PRODUITE, VEUILLER REESSAYER!!!&id_commande=' . $id_commande);
        }
    }
}
