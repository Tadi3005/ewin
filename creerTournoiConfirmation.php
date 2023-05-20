<?php
session_start();

include 'php/Tournoi.php';
use EWIN\Tournoi;

// Si l'utilisateur a valider la création du tournoi
if (isset($_POST['validerCreationTournoi'])) {
    // Si la session n'existe pas, on redirige vers la page de création de tournoi avec le message d'erreur
    if (!isset($_SESSION['nouveauTournoi'])) {
        $message = 'Le tournoi n\'a pas été créé';
        header('Location: creerTournoi.php?erreur=' . $message);
        exit();
    }
    if ($_SESSION['id'] == null) {
        $message = 'Le tournoi n\'a pas été créé';
        header('Location: creerTournoi.php?erreur=' . $message);
        exit();
    }
    $nouveauTournoi = unserialize($_SESSION['nouveauTournoi']);
    $nouveauTournoi->creerTournoi($_SESSION['id']);
    $message = 'Le tournoi a bien été créé';
    header('Location: consulterTournoi.php?id=' . $nouveauTournoi->getId());
}

// Si l'utilisateur a annuler la création du tournoi
if (isset($_POST['annulerCreationTournoi'])) {
    $message = 'Le tournoi n\'a pas été créé';
    header('Location: creerTournoi.php?erreur=' . $message);
}