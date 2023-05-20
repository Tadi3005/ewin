<?php
require "php/Tournoi.php";

use EWIN\Tournoi;

session_start();

if (isset($_GET['id'])) {
    $idTournoi = $_GET['id'];
} else {
    header("Location: listeTournois.php");
}
$message = "";
$tournoi = new Tournoi();
$tournoi->setTournoiWithId($idTournoi);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier tournoi</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>
    <main class="mainFormulaire">
        <section class="formulaire">
            <form method="post" action="modifierTournoiConfirmation.php?id=<?php echo $idTournoi; ?>">
                <label for="nomTournoi">Nom du tournoi</label><input id="nomTournoi" name="nomTournoi" type="text" value="<?php echo $tournoi->getNom(); ?>">
                <label for="sportTournoi">Sport du tournoi</label>
                <select name="sportTournoi" id="sportTournoi">
                    <option value="" disabled>--Choisir un sport--</option>
                    <option value="belotte" <?php if($tournoi->getSport($idTournoi, $message) == "Belotte") echo "selected"; ?>>Belotte</option>
                    <option value="jeuEchecs" <?php if($tournoi->getSport($idTournoi, $message) == "Jeu d’échecs") echo "selected"; ?>>Jeu d'échecs</option>
                    <option value="tennis" <?php if($tournoi->getSport($idTournoi, $message) == "Tennis") echo "selected"; ?>>Tennis</option>
                    <option value="pingPong" <?php if($tournoi->getSport($idTournoi, $message) == "Ping-Pong") echo "selected"; ?>>Ping-Pong</option>
                    <option value="fifa" <?php if($tournoi->getSport($idTournoi, $message) == "Fifa") echo "selected"; ?>>FIFA</option>
                </select>
                <label for="nbrJoueur">Nombre de joueur</label><input id="nbrJoueur" name="nbrJoueur" type="number" value="<?php echo $tournoi->getPlacesDispo(); ?>" min="2">
                <label for="dateTournoi">Date du tournoi</label><input id="dateTournoi" name="dateTournoi" type="date" value="<?php echo $tournoi->getDateTournoi(); ?>">
                <label for="dateFinInscription">Date fin inscription du tournoi</label><input id="dateFinInscription" name="dateFinInscription" type="date" value="<?php echo $tournoi->getDateFinInscription(); ?>">
                <button type="submit">Modifier</button>
            </form>
        </section>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>