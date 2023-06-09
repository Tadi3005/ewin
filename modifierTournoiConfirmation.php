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

if (isset($_POST['nomTournoi']) && isset($_POST['sportTournoi']) && isset($_POST['nbrJoueur']) && isset($_POST['dateTournoi']) && isset($_POST['dateFinInscription'])) {
    $nomTournoi = $_POST['nomTournoi'];
    $sportTournoi = $_POST['sportTournoi'];
    $nbrJoueur = $_POST['nbrJoueur'];
    $dateTournoi = $_POST['dateTournoi'];
    $dateFinInscription = $_POST['dateFinInscription'];
} else {
    $message = "Veuillez remplir tous les champs.";
    header("Location: modifierTournoi.php?id=" . $idTournoi . "&message=" . $message);
}

if (!empty($_POST['nomTournoi']) && !empty($_POST['sportTournoi']) && !empty($_POST['nbrJoueur']) && !empty($_POST['dateTournoi']) && !empty($_POST['dateFinInscription'])) {
    $nomTournoi = $_POST['nomTournoi'];
    $sportTournoi = $_POST['sportTournoi'];
    $nbrJoueur = $_POST['nbrJoueur'];
    $dateTournoi = $_POST['dateTournoi'];
    $dateFinInscription = $_POST['dateFinInscription'];

    // Vérification des données invalides
    if ($dateTournoi < $dateFinInscription) {
        $message = "La date du tournoi doit être supérieure à la date de fin des inscriptions.";
        header("Location: modifierTournoi.php?id=" . $idTournoi . "&message=" . $message);
    } elseif ($nbrJoueur < $tournoi->getPlacesDispo()) {
        $message = "Le nombre de participants ne peut pas être supérieur aux places disponibles.";
        header("Location: modifierTournoi.php?id=" . $idTournoi . "&message=" . $message);
    }elseif ($dateFinInscription > date("yyyy-mm-dd")) {
        $message = "La date de fin des inscriptions doit être supérieure à la date du jour.";
        header("Location: modifierTournoi.php?id=" . $idTournoi . "&message=" . $message);
    } elseif ($dateTournoi > date("yyyy-mm-dd")) {
        $message = "La date du tournoi doit être supérieure à la date du jour.";
        header("Location: modifierTournoi.php?id=" . $idTournoi . "&message=" . $message);
    } elseif ($dateTournoi < $dateFinInscription) {
        $message = "La date du tournoi doit être supérieure à la date de fin des inscriptions.";
        header("Location: modifierTournoi.php?id=" . $idTournoi . "&message=" . $message);
    } else {
        $tournoi->setNomTournoi($nomTournoi);
        switch ($sportTournoi) {
            case "belotte":
                $sportTournoi = 1;
                break;
            case "jeuEchecs":
                $sportTournoi = 2;
                break;
            case "tennis":
                $sportTournoi = 3;
                break;
            case "pingPong":
                $sportTournoi = 4;
                break;
            case "fifa":
                $sportTournoi = 5;
                break;
        }
        $tournoi->setSport($sportTournoi);
        $tournoi->setNbrJoueur($nbrJoueur);
        $tournoi->setDateTournoi($dateTournoi);
        $tournoi->setDateFinInscription($dateFinInscription);
        $tournoi->updateTournoi();
        header("Location: consulterTournoi.php?id=" . $idTournoi);
    }
} else if (isset($_POST['annulerModificationTournoi'])) {
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
    <?php if (!empty($message)) : ?>
        <p class="error-message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <h1> Voulez-vous vraiment modifier ce tournoi ? </h1>
        <input type="hidden" name="nomTournoi" value="<?php echo $nomTournoi ?>">
        <input type="hidden" name="sportTournoi" value="<?php echo $sportTournoi ?>">
        <input type="hidden" name="nbrJoueur" value="<?php echo $nbrJoueur ?>">
        <input type="hidden" name="dateTournoi" value="<?php echo $dateTournoi ?>">
        <input type="hidden" name="dateFinInscription" value="<?php echo $dateFinInscription ?>">
        <button type="submit" name="annulerModificationTournoi" value="annulerModificationTournoi">Annuler</button>
        <button type="submit" name="validerModificationTournoi" value="validerModificationTournoi">Valider</button>
    </form>
</main>

<?php require("inc/footer.inc.php"); ?>
</body>
</html>
