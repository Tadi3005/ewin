<?php
session_start();
require 'User.php';
use EWIN\User;

if (isset($_POST['mail']) && isset($_POST['passeword'])) {
    $email = $_POST['mail'];
    $password = $_POST['passeword'];
    $user = new User();

// Set the properties
    $user->setCourrielOrPseudo($email);
    $user->setMotDePasse($password);

// Login
    $message = '';
    if($user->login($message)) {
        $user = $user->setUser($user, $message);
        $user->storeUserInSession($user);
        header('Location: ../listeTournois.php');
    } else {
        header('Location: ../connexion.php?message=' . $message . '');
    }
} else {
    header('Location: ../connexion.php?message=Veuillez remplir tous les champs');
}