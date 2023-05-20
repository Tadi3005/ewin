<?php
session_start();

require "php/Tournoi.php";

use EWIN\Tournoi;
use EWIN\Rencontre;

$message = "";

// Récupérer l'id de la rencontre et l'id du tournoi
if (isset($_GET['idRencontre']) && isset($_GET['idTournoi'])) {
        $idRencontre = $_GET['idRencontre'];
        $idTournoi = $_GET['idTournoi'];
    } else {
        header("Location: listeTournois.php");
    }

    // Récupérer les informations du tournoi
    $tournoi = new Tournoi();
    $tournoi->setTournoiWithId($idTournoi);

    // Récupérer les informations de la rencontre
    $rencontre = new Rencontre();
    $rencontre->setRencontreWithId($idRencontre, $message);

    // Si un score est envoyé
    if (isset($_POST['scoreJ1']) && isset($_POST['scoreJ2'])) {
        $scoreJ1 = $_POST['scoreJ1'];
        $scoreJ2 = $_POST['scoreJ2'];
        $rencontre->updateScore($scoreJ1, $scoreJ2);
        header("Location: consulterTournoi.php?id=" . $idTournoi);
    }

    // Si un vainqueur est envoyé
    if (isset($_POST['validationVainqueur'])) {
        $vainqueur = $_POST['validationVainqueur'];
        $rencontre->updateVainqueur($vainqueur);
        $tournoi->updateStatutEnCours($message);

        echo $rencontre->getVainqueur();
        // Si la rencontre est la dernière modifier le statut en "Terminé"
        if ($tournoi->lastIdRencontre() == $rencontre->getId()) {
            $tournoi->updateStatutTermine($message);
        }

        // Faire passer le vainqueur à la rencontre suivante
        $idRencontreSuivante = $rencontre->getIdRencontreNext();

        $rencontre->nextRencontreConfiguration($vainqueur, $idRencontreSuivante);

        header("Location: consulterTournoi.php?id=" . $idTournoi);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Edition score</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>

    <main class="mainFormulaire">
        <section class="formulaire">
            <h1>Editer Score</h1>
            <?php if ($rencontre->getIdJoueur2() != null): ?>
            <form method="post" action="">
                <label for="scoreJ1">Score <?php echo $rencontre->getPseudo($rencontre->getIdJoueur1()) ?></label><input id="scoreJ1" name="scoreJ1" type="number" min="0" value="<?php echo $rencontre->getScoreJoueur1() ?>">
                <label for="scoreJ2">Score <?php echo $rencontre->getPseudo($rencontre->getIdJoueur2()) ?></label><input id="scoreJ2" name="scoreJ2" type="number" min="0" value="<?php echo $rencontre->getScoreJoueur2() ?>">
                <button type="submit">Valider</button>
            </form>
            <?php endif; ?>
            <?php if (($rencontre->getScoreJoueur1() != $rencontre->getScoreJoueur2()) || $rencontre->getIdJoueur2() == null): // TODO revoir quand un joueur affronter null ?>
            <form class="validerVainqueur" method="post" action="">
                <h1>Validation vainqueur : </h1>
                <p><?php echo $rencontre->getScoreJoueur1() > $rencontre->getScoreJoueur2() || $rencontre->getIdJoueur2() == null ? $rencontre->getPseudo($rencontre->getIdJoueur1()) : $rencontre->getPseudo($rencontre->getIdJoueur2()) ?></p>
                <button type="submit" name="validationVainqueur" value="<?php echo $rencontre->getScoreJoueur1() > $rencontre->getScoreJoueur2() || $rencontre->getIdJoueur2() == null ? $rencontre->getIdJoueur1() : $rencontre->getIdJoueur2(); ?>">Valider vainqueur</button>
            </form>
            <?php endif; ?>
        </section>
    </main>

    <?php require("inc/footer.inc.php"); ?>
</body>
</html>