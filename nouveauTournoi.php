<?php
session_start();

include 'php/Tournoi.php';
use EWIN\Tournoi;

// Variable
$message = '';
$nouveauTournoi = new Tournoi();

// Traitement
if (isset($_POST['nomTournoi'], $_POST['sportTournoi'], $_POST['nbrJoueur'], $_POST['dateTournoi'], $_POST['dateFinInscription'])) {
    $nomTournoi = $_POST['nomTournoi'];
    $sportTournoi = $_POST['sportTournoi'];
    $nbrJoueur = $_POST['nbrJoueur'];
    $dateTournoi = $_POST['dateTournoi'];
    $dateFinInscription = $_POST['dateFinInscription'];
    $dateTimeDateTournoi = new DateTime($dateTournoi);
    $dateTimeFinInscriptionTournoi = new DateTime($dateFinInscription);

    // La date du tournoi (obligatoire et supérieur à la date de fin des inscriptions)
    if ($dateTimeDateTournoi >= $dateTimeFinInscriptionTournoi) {
        // La date de fin des inscriptions (obligatoire, supérieur à la date du jour)
        if ($dateTimeFinInscriptionTournoi >= new DateTime(date('Y-m-d'))) {
            // ID du sport
            switch ($sportTournoi) {
                case 'belotte':
                    $sportTournoi = 1;
                    break;
                case 'jeuEchecs':
                    $sportTournoi = 2;
                    break;
                case 'tennis':
                    $sportTournoi = 3;
                    break;
                case 'pingPong':
                    $sportTournoi = 4;
                    break;
                case 'fifa':
                    $sportTournoi = 5;
                    break;
            }
            $nouveauTournoi->setNouveauTournoi($nomTournoi, $sportTournoi, $nbrJoueur, $dateTournoi, $dateFinInscription);
            $_SESSION['nouveauTournoi'] = serialize($nouveauTournoi);
        } else {
            $message = 'La date de fin des inscriptions doit être supérieure à la date du jour';
        }
    } else {
        $message = 'La date du tournoi doit être supérieure à la date de fin des inscriptions';
    }
} else {
    $message = 'Veuillez remplir tous les champs';
}

// Si le message n'est pas vide, on redirige vers la page de création de tournoi avec le message d'erreur
if ($message != '') {
    header('Location: creerTournoi.php?erreur=' . $message);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer tournoi</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>
    <main>
        <form action="creerTournoiConfirmation.php" method="post">
            <?php
            echo '<h1> Voulez vous vraiment créer le tournoi "' . $nouveauTournoi->getNom() . '" ? </h1>';
            ?>
            <button type="submit" name="annulerCreationTournoi" value="annulerCreationTournoi">Annuler</button>
            <button type="submit" name="validerCreationTournoi" value="validerCreationTournoi">Valider</button>
        </form>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>
