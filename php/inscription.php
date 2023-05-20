<?php
require 'User.php';
use EWIN\User;
$user = new User();
$erreur = '';

$email = isset($_POST['mail']) ? $_POST['mail'] : $erreur = 'pas de courriel';
$password = isset($_POST['passeword']) ? $_POST['passeword'] : $erreur = 'pas de mot de passe';
$reconfirmerPassword = isset($_POST['reconfirmerPasseword']) ? $_POST['reconfirmerPasseword'] : $erreur = 'pas de mot de passe';
$pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : $erreur = 'pas de pseudo';
$nom = isset($_POST['nomFamille']) ? $_POST['nomFamille'] : $erreur = 'pas de nom';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : $erreur = 'pas de prenom';
$photoProfil = isset($_FILES['photoProfil']) ? $_FILES['photoProfil'] : null;

if ($photoProfil) {
// Vérifier si le fichier téléversé a été téléversé sans erreur.
    if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_NO_FILE) {
        if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_INI_SIZE) {
            if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_FORM_SIZE) {
                if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_PARTIAL) {
                    // Vérifier la taille du fichier téléversé
                    if ($_FILES['photoProfil']['size'] <= 10000000) {
                        if (getimagesize($_FILES['photoProfil']['tmp_name'])) {
                            $tailleImage = getimagesize($_FILES['photoProfil']['tmp_name']);
                            if ($tailleImage[0] == $tailleImage[1]) {
                                $extensions_autorisees = array('jpg', 'jpeg', 'png');
                                if (in_array(pathinfo($_FILES['photoProfil']['name'])['extension'], $extensions_autorisees)) {
                                    $extension = pathinfo($_FILES['photoProfil']['name'], PATHINFO_EXTENSION);
                                    $nomFichier = uniqid().'.'.$extension;
                                    $destination = '../upload/' . $nomFichier;
                                    if (move_uploaded_file($_FILES['photoProfil']['tmp_name'], $destination)) {
                                        $user->setUrlPhoto($nomFichier);
                                        exit();
                                    } else {
                                        $erreur = 'erreur lors du téléversement';
                                    }
                                } else {
                                    $erreur = 'extension non autorisée';
                                }
                            } else {
                            $erreur = 'image non carrée';
                            }
                        } else {
                            $erreur = 'fichier non image';
                        }
                    } else {
                        $erreur = 'fichier trop volumineux';
                    }
                } else {
                    $erreur = 'fichier partiellement téléchargé';
                }
            } else {
                $erreur = 'taille fichier dépassant la limite';
            }
        } else {
            $erreur = 'pas de fichier spécifié';
        }
    } else {
        $erreur = 'pas de fichier spécifié';
    }
}
if ($erreur == '') {
    if ($password == $reconfirmerPassword) {
        if (strlen($password) >= 8 && strlen($password) <= 20) {
            $user->setCourriel($email);
            $user->setMotDePasse($password);
            $user->setPseudo($pseudo);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEstActif(1);
            $user->setEstOrganisateur(0);
            $message = '';
            if (!$user->exist($erreur)) {
                if ($user->register($user, $message)) {
                    header('Location: ../inscription.php?message=' . $message . '');
                    exit();
                } else {
                    $erreur = 'erreur lors de l\'inscription';
                }
            } else {
                $erreur = 'le courriel ou le pseudo est déjà utilisé';
            }
        } else {
            $erreur = 'le mot de passe doit contenir entre 8 et 20 caractères';
        }
    } else {
        $erreur = 'les mots de passe ne correspondent pas';
    }
}
header('Location: ../inscription.php?message=' . $erreur . '');
?>