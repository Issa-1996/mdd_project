<?php
require_once('fonction.php');
if (isset($_POST['login']) && $_POST['login']) {
    $resultat = connecter_utilisateur($_POST['login'], $_POST['mot_de_passe']);
    if (is_array($resultat)) {
        session_start();
        $_SESSION['id_utilisateur'] = $resultat['id_utilisateur'];
        $_SESSION['nom'] = $resultat['nom'];
        $_SESSION['prenom'] = $resultat['prenom'];
        $_SESSION['login'] = $resultat['login'];
        $_SESSION['mot_de_passe'] = $resultat['mot_de_passe'];
        $_SESSION['role'] = $resultat['role'];
        header("Location: ../view/accueil.php");
        exit;
    } else {
        // Erreur de connexion
        header("Location: ../../index.php?error=$resultat");
        exit;
    }
}