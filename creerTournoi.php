<?php
session_start();
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
    <main class="mainFormulaire">
        <section class="formulaire">
            <h1>Créer un tournoi</h1>
        <form method="post" action="nouveauTournoi.php">
            <label for="nomTournoi">Nom du tournoi</label><input id="nomTournoi" name="nomTournoi" type="text" required>
            <label for="sportTournoi">Sport du tournoi</label>
            <select name="sportTournoi" id="sportTournoi" required>
                <option value="">--Choisir un sport--</option>
                <option value="belotte">Belotte</option>
                <option value="jeuEchecs">Jeu d'échecs</option>
                <option value="tennis">Tennis</option>
                <option value="pingPong">Ping-Pong</option>
                <option value="fifa">FIFA</option>
            </select>
            <label for="nbrJoueur">Nombre de joueur</label><input id="nbrJoueur" name="nbrJoueur" type="number" value="10" min="2" required>
            <label for="dateTournoi">Date du tournoi</label><input id="dateTournoi" name="dateTournoi" type="date" required>
            <label for="dateFinInscription">Date de fin d'inscription</label><input id="dateFinInscription" name="dateFinInscription" type="date" required>
            <button type="submit">Valider</button>
        </form>
        <?php
        if (isset($_GET['erreur'])) {
            echo '<p class="messageErreur">' . $_GET['erreur'] . '</p>';
        }
        if (isset($_GET['message'])) {
            echo '<p class="messageSucces">' . $_GET['message'] . '</p>';
        }
        ?>
        </section>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>