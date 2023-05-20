<?php
    session_start();

    require 'php/Tournoi.php';
    use EWIN\Tournoi;

    if (isset($_GET['id'])) {
        $idTournoi = $_GET['id'];
        $tournoi = new Tournoi();
        $tournoi->setTournoiWithId($idTournoi);
    } else {
        header('Location: listeTournois.php');
    }

    if (isset($_POST['validerSuprresionArbreTournoi'])) {
        $tournoi->supprimerArbreTournoi();

        $dateFinInscription = $tournoi->getDateFinInscription();

        if ($dateFinInscription < date('Y-m-d')) {
            // cloturé si la date de fin d'inscription est passée
            $tournoi->updateStatsutCloture();
        } else {
            // fermé si la date d'inscription est passé et que la date de fin d'inscription n'est pas dépassé
            $tournoi->updateStatsutFerme();
        }
        header('Location: consulterTournoi.php?id=' . $idTournoi);
    } else if (isset($_POST['annulerSuprresionArbreTournoi'])) {
        header('Location: consulterTournoi.php?id=' . $idTournoi);
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
        echo '<h1> Voulez-vous vraiment supprimer l\'arbre du tournoi : "' . $tournoi->getNom() . '" ? </h1>';
        ?>
        <button type="submit" name="annulerSuprresionArbreTournoi" value="annulerSuprresionArbreTournoi">Annuler</button>
        <button type="submit" name="validerSuprresionArbreTournoi" value="validerSuprresionArbreTournoi">Valider</button>
    </form>
</main>

<?php require("inc/footer.inc.php"); ?>
</body>
</html>