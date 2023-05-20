<?php
session_start();
require "php/Tournoi.php";
use EWIN\Tournoi;

if($_GET['id']) {
    $idTournoi = $_GET['id'];
} else {
    header("Location: listeTournois.php");
}
$message = '';
$tournoi = new Tournoi();
$tournoi->setTournoiWithId($idTournoi);

if (isset($_POST['validerJoinTournoi'])) {
    $tournoi->addPlayerToTournoi($_SESSION['id'], $message);
    header("Location: consulterTournoi.php?id=" . $idTournoi);
} elseif (isset($_POST['annulerJoinTournoi'])) {
    header("Location: consulterTournoi.php?id=" . $idTournoi);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $tournoi->getNom() ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>

    <main>
        <form action="" method="post">
            <?php
            echo '<h1> Voulez-vous vraiment rejoindre le tournoi "' . $tournoi->getNom() . '" ? </h1>';
            ?>
            <button type="submit" name="annulerJoinTournoi" value="annulerJoinTournoi">Annuler</button>
            <button type="submit" name="validerJoinTournoi" value="validerJoinTournoi">Valider</button>
        </form>
    </main>

    <?php require("inc/footer.inc.php"); ?>
</body>
</html>
