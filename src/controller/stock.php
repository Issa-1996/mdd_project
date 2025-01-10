<?php
require_once('fonction.php');
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['mot_de_passe'])) {
  session_destroy();
  header("Location: ../../index.php");
  exit();
}
// Condition d'ajout stock
if (isset($_POST['quantite_disponible'])) {
    $countSuccess = 0;
    try {
        foreach ($_POST['produit_id'] as $produit_id) {
            if ($produit_id) {
                $countSuccess++;
                add_stock($produit_id, $_POST['quantite_disponible'], $_POST['min'], $_POST['max'], $_POST['emplacement_produit']);
            }
        }
        if ($countSuccess != 0) {
            return header('Location:../view/stock.php?success=STOCK ALIMENTER AVEC SUCCESS');
        }
    } catch (Exception $e) {
        return header('Location:../view/stock.php?error_add=ERREUR => CE PRODUIT EXISTE DEJA DANS LE STOCK  ');
    }
}

// Condition d'ouverture du modal pour modifer un stock
if (isset($_GET['put_id_stock'])) {
    // var_dump($_GET['put_id_stock']); die;
    $put_id_stock = $_GET['put_id_stock'];
    $put_data_stock = http_build_query(['put_data_stock' => get_one_stock($put_id_stock)]);
    return header('Location:../view/stock.php?openModal=put_categorie&put_id_stock=' . $put_id_stock . '&put_data_stock=' . $put_data_stock);
}

// Condition pour modifier un stock
if (isset($_POST['put_quantite_disponible'])) {
    try {
        //($produit_id, $quantite_disponible, $emplacement_produit, $id_stock);
        $result = update_stock($_POST['put_produit_id'], $_POST['put_quantite_disponible'], $_POST['min'], $_POST['max'], $_POST['put_emplacement_produit'], $_POST['put_id_stock']);
        if ($result == true) {
            return header('Location:../view/stock.php?update_success=PRODUIT MODIFIER AVEC SUCCESS');
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
    }
}

// Ouverture modal delete stock
if (isset($_GET['delete_id_stock'])) {
    $delete_id_stock = $_GET['delete_id_stock'];
    return header('Location:../view/stock.php?openModal=delete_stock&delete_id_stock=' . $delete_id_stock);
}

// Suppression stock
if (isset($_GET['delete_id_stock2'])) {
    try {
        $result = delete_stock($_GET['delete_id_stock2']);
        if ($result == true) {
            return header('Location:../view/stock.php?delete_success=PRODUIT RETIRER DU STOCK AVEC SUCCESS');
        }
    } catch (Exception $e) {
        return header('Location:../view/stock.php?delete_error=Impossible: OUPS => UNE ERREUR S\'EST PRODUITE, MERCI DE REESSAYER');
    }
}
