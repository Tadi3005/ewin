<?php
session_start();
require "php/Tournoi.php";

use EWIN\Tournoi;
use EWIN\Rencontre;

if($_GET['id']) {
    $idTournoi = $_GET['id'];
} else {
    header("Location: listeTournois.php");
}
$message = '';
$tournoi = new Tournoi();
$tournoi->setTournoiWithId($idTournoi);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Generer un tournoi</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>
    <?php
    // Variables pour l'algorithme
    $joueurs = $tournoi->getJoueurs($message);
    // Algorithme de génération du tournoi
        // 1. Calculer le nombre de joueurs
        $nbJoueurs = count($joueurs);
        // 2. Calculer le nombre de tours
        $nbTours = ceil(log($nbJoueurs, 2));
        // 3. Calculer le nombre de rencontre du tournoi
        $nbRencontres = pow(2, $nbTours) - 1;

        function nombreRencontreparTour($nbTours, $numTour) {
            return pow(2, $nbTours) / pow(2, $numTour);
        }

        // 4. Pour le premier tour (n=1), attribuer un joueur pour toutes les rencontres afin que toutes les rencontres en possèdent au moins un.
        $rencontres = array();
        $joueurActuel = 0;

        for ($i = 0; $i < nombreRencontreparTour($nbTours, 1); $i++) {
            $rencontre = new Rencontre();
            $rencontre->setRencontre($idTournoi, $joueurs[$i]->getId(), null, 0, 0, null, $rencontre->getLastId() + nombreRencontreparTour($nbTours, 1));
            array_push($rencontres, $rencontre);
            $joueurActuel++;
        }

        // 5. Affecter le reste des joueurs à un tournoi
        for ($i = 0; $i < nombreRencontreparTour($nbTours, 1); $i++) {
            if (isset($joueurs[$joueurActuel])) {
                $rencontres[$i]->setIdJoueur2($joueurs[$joueurActuel]->getId());
                $joueurActuel++;
            }
        }

        // 6. Affichage du tournoi
        ?>
        <ul class="arbre">
            <li class="level">
                <?php for ($i = 0; $i < nombreRencontreparTour($nbTours, 1); $i++): ?>
                    <ul class="rencontre">
                        <li>
                            <span>
                                <?php
                                // Afficher le pseudo du joueur 1
                                echo $rencontres[$i]->getPseudo($rencontres[$i]->getIdJoueur1());
                                ?>
                            </span>
                        </li>
                        <li>
                            <span>
                                <?php
                                // Afficher le pseudo du joueur 2
                                if ($rencontres[$i]->getIdJoueur2() == null) {
                                    echo "Null";
                                } else {
                                    echo $rencontres[$i]->getPseudo($rencontres[$i]->getIdJoueur2());
                                }
                                ?>
                            </span>
                        </li>
                    </ul>
                <?php endfor; ?>
            </li>
        </ul>
        <form action="" method="post">
            <h1> Voulez-vous vraiment générer cette arbre ? </h1>
            <button type="submit" name="annulerGenreationArbre" value="annulerGenreationArbre">Annuler</button>
            <button type="submit" name="validerGenerationArbre" value="validerGenerationArbre">Valider</button>
        </form>
        <?php
        if (isset($_POST['validerGenerationArbre'])) {

            // Modfier l'id de la rencontre suivante pour chaque rencontre
            if (count($rencontres) > 1) {
                $idNextRencontre = $rencontres[0]->getIdNextRencontre();
                for ($i = 0; $i < count($rencontres) - 1; $i += 2) {
                    $rencontres[$i]->setIdNextRencontre($idNextRencontre);
                    $rencontres[$i + 1]->setIdNextRencontre($idNextRencontre);
                    $idNextRencontre += 1;
                }
            } else {
                $rencontres[0]->setIdNextRencontre(null);
            }

            // Ajouter chaque rencontre dans la base de données
            foreach ($rencontres as $rencontre) {
                $rencontre->addRencontre($message);
            }

            // Créer les autres rencontres du tournoi
                // Calculer le nombre de rencontres restantes
                $nbRencontresRestantes = $nbRencontres - nombreRencontreparTour($nbTours, 1);

                if ($nbRencontresRestantes == 1) {
                    // Créer la rencontre finale
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, null);
                    $rencontre->addRencontre($message);
                }

                if ($nbRencontresRestantes == 3) {
                    // Créer les deux demi-finales
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, $rencontre->getLastId() + 2);
                    $rencontre->addRencontre($message);
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, $rencontre->getLastId() + 1);
                    $rencontre->addRencontre($message);

                    // Créer la rencontre finale
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, null);
                    $rencontre->addRencontre($message);
                }

                if ($nbRencontresRestantes > 3) {
                    for ($i = 0; $i < $nbRencontresRestantes - 3; $i++) {
                        $rencontre = new Rencontre();
                        $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, $rencontre->getLastId() + 1);
                        $rencontre->addRencontre($message);
                    }

                    // Ajouter les 3 dernières rencontres
                    // Créer les deux demi-finales
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, $rencontre->getLastId() + 2);
                    $rencontre->addRencontre($message);
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, $rencontre->getLastId() + 1);
                    $rencontre->addRencontre($message);

                    // Créer la rencontre finale
                    $rencontre = new Rencontre();
                    $rencontre->setRencontre($idTournoi, null, null, 0, 0, null, null);
                    $rencontre->addRencontre($message);
                }


            // Mettre à jour le statut du tournoi
            $tournoi->updateStatutGenere($message);

            // Redirection vers la consultation du tournoi
            header("Location: consulterTournoi.php?id=$idTournoi");
        }
        ?>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>
