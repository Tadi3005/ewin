<?php
session_start();
require "php/Tournoi.php";

use EWIN\Rencontre;
use EWIN\Tournoi;
use EWIN\User;

    if($_GET['id']) {
        $idTournoi = $_GET['id'];
    } else {
        header("Location: listeTournois.php");
    }
    $message = '';
    $tournoi = new Tournoi();
    $tournoi->setTournoiWithId($idTournoi);

    if ($tournoi->hasAMatch()) {
        $rencontres = $tournoi->getRencontres($message);
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
        <?php if(isset($rencontres) && $rencontres[count($rencontres) - 1]->getVainqueur() != null): ?>
        <div id="messageVainqueurTournoi">
            <img src="images/coupe.png" width="70" height="70" alt="COUPE">
            <p>Le vainqueur du tournoi est <?php echo $rencontres[count($rencontres) - 1]->getPseudo($rencontres[count($rencontres) - 1]->getVainqueur());?></p>
            <img src="images/coupe.png" width="70" height="70" alt="COUPE">
        </div>
        <?php endif; ?>
        <section class="titreUpdateStatut">
            <div class="titreUpdate">
                <h1>
                    <?php echo $tournoi->getNom()?>
                    <u><?php
                        if (isset($_SESSION['id'])) {
                            if ($tournoi->isPlayerInTournoi($_SESSION['id'], $message)) {
                                echo '(inscrit)';
                            }
                        }
                        ?>
                    </u>
                </h1>
                <?php if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 0 && $tournoi->isPlayerCanJoinTournoi($_SESSION['id'], $message)): ?>
                    <a href="rejoindreTournoi.php?id=<?php echo $tournoi->getId()?>"><img src="images/input-icon.png" alt="DELETE" width="50" height="50"></a>
                <?php endif; ?>
                <?php if(isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1):?>
                    <?php if ($tournoi->getIdStatut() == 2): ?>
                    <a href="deleteTournoi.php?id=<?php echo $tournoi->getId(); ?>"><img src="images/delete-button.svg" alt="DELETE" width="50" height="50"></a>
                    <?php endif; ?>
                    <a href="modifierTournoi.php?id=<?php echo $tournoi->getId(); ?>"><img src="images/modifier.png" alt="MODIFIER" width="50" height="50"></a>
                <?php endif; ?>
            </div>
            <?php
            switch ($tournoi->getStatut($tournoi->getId(), $message)) {
                case 'Ouvert':
                    echo '<span class="statutTournoi" id="statutOuvert">ouvert</span>';
                    break;
                case 'Termine':
                    echo '<span class="statutTournoi" id="statutTermine">terminé</span>';
                    break;
                case 'Ferme':
                    echo '<span class="statutTournoi" id="statutFerme">fermé</span>';
                    break;
                case 'En-cours':
                    echo '<span class="statutTournoi" id="statutEnCours">en-cours</span>';
                    break;
                case 'Cloture':
                    echo '<span class="statutTournoi" id="statutCloture">cloturé</span>';
                    break;
                case 'Genere':
                    echo '<span class="statutTournoi" id="statutGenere">généré</span>';
                    break;
            }
            ?>
        </section>
        <section class="infosTournoiConsulterTournoi">
            <img src="images/<?php
            switch ($tournoi->getSport($tournoi->getId(), $message)) {
                case 'Belotte':
                    echo 'belotte.jpg';
                    break;
                case 'Jeu d’échecs':
                    echo 'echec.jpg';
                    break;
                case 'Tennis':
                    echo 'tennis.jpg';
                    break;
                case 'Ping-Pong':
                    echo 'pingPong.jpg';
                    break;
                case 'Fifa':
                    echo 'fifa.jpg';
                    break;
            }
            ?>" alt="SportTournoiImage" width="300" height="200">
            <div>
                <p>Sport : <?php echo $tournoi->getSport($idTournoi, $message)?></p>
                <div class="infosDateTournoi">
                    <img src="images/calendrier.png" alt="CALENDRIER" width="30" height="30">
                    <span>
                        <?php
                        $dateTournoi = $tournoi->getDateTournoi();
                        $datetimeTournoi = DateTime::createFromFormat("Y-m-d", $dateTournoi);
                        $new_dateTournoi = $datetimeTournoi->format("d F Y");
                        echo $new_dateTournoi;
                        ?>
                    </span>
                </div>
                <div class="joueurInfosTournoi">
                    <img src="images/joueur.png" alt="JOUEUR" width="30" height="30">
                    <p>
                        <?php
                        $nombreDePlaceRestantes = $tournoi->getPlacesDispo() - $tournoi->nbrJoueurInTournoi();
                        if ($nombreDePlaceRestantes == 0) {
                            echo 'Le tournoi est complet !';
                        } else {
                            echo $tournoi->getPlacesDispo() . ' joueurs (' . $nombreDePlaceRestantes . ' places restantes)';
                        }
                        ?>
                    </p>
                </div>
                <p class="dateFinInscription">Inscription jusqu'au :
                <?php
                $dateDateFinInscription = $tournoi->getDateFinInscription();
                $datetimeFinInscription = DateTime::createFromFormat("Y-m-d", $dateDateFinInscription);
                $new_dateFinInscription = $datetimeFinInscription->format("d F Y");
                echo $new_dateFinInscription;
                ?>
                </p>
            </div>
        </section>
        <ul class="listeJoueur">
            <?php
            $joueurs = $tournoi->getJoueurs($message);
            if ($joueurs == null) {
                echo '<p>Aucun joueur inscrit pour le moment</p>';
            }
            for ($i = 0; $i < count($joueurs); $i++):
                $joueur = new User();
                $joueur = $joueurs[$i];
            ?>
            <li>
                <p><?php echo $joueur->getPseudo(); ?></p>
                <img src="upload/<?php echo $joueur->getUrlPhoto() ?>" alt="JOUEUR" width="70" height="70">
                <?php if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1 && $tournoi->getIdStatut() != 6 && $tournoi->getIdStatut() != 4 && $tournoi->getIdStatut() != 2): ?>
                <a href="deleteJoueur.php?id=<?php echo $tournoi->getId()?>&idJoueur=<?php echo $joueur->getId()?>"><img src="images/delete-button.svg" alt="DELETE" width="30" height="30"></a>
                <?php endif; ?>
            </li>
            <?php endfor; ?>
        </ul>
        <?php if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1 && !$tournoi->hasAMatch() && count($joueurs) >= 2): // TODO vérifier aussi qu'il y a plus de deux participants avant la date de fin d'inscription?>
        <form action="genererTournoi.php?id=<?php echo $tournoi->getId() ?>" method="post">
            <button type="submit" name="genererArbre" value="genererArbre">Générer l'arbre</button>
        </form>
        <?php endif; ?>

        <?php
        if ($tournoi->hasAMatch()):

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

                // Initialiser le première id
                $idRencontre = $rencontres[0]->getId();
            ?>
            <ul class="arbre">
                <?php for ($i = 1; $i <= $nbTours; $i++): ?>
                    <li class="level">
                        <?php for ($j = 0; $j < nombreRencontreparTour($nbTours, $i); $j++):
                            $rencontre = new Rencontre();
                            $rencontre->setRencontreWithId($idRencontre, $message);
                            ?>
                            <ul class="rencontre">
                                <li>
                                    <span>
                                        <?php
                                        $joueur1 = new User();
                                        $joueur1->setUserWithId($rencontre->getIdJoueur1(), $message);
                                        if ($joueur1->getId() == null) {
                                            echo 'null';
                                        } else {
                                            echo $joueur1->getPseudo();
                                        }
                                        ?>
                                    </span>
                                    <?php if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1 && $rencontre->getVainqueur() == null && !($rencontre->getIdJoueur1() == null && $rencontre->getIdJoueur2() == null) && !($rencontre->getIdJoueur1() == null xor $rencontre->getIdJoueur2() == null && $i >= 2)): ?>
                                    <a href="editerRencontre.php?idRencontre=<?php echo $rencontre->getId()?>&idTournoi=<?php echo $idTournoi?>">
                                        <?php
                                        echo $rencontre->getScoreJoueur1();
                                        ?>
                                    </a>
                                    <?php
                                    else:
                                        echo $rencontre->getScoreJoueur1();
                                    endif;
                                    ?>
                                </li>
                                <li>
                                    <span>
                                        <?php
                                        $joueur2 = new User();
                                        $joueur2->setUserWithId($rencontre->getIdJoueur2(), $message);

                                        if ($joueur2->getId() == null) {
                                            echo 'null';
                                        } else {
                                            echo $joueur2->getPseudo();
                                        }
                                        ?>
                                    </span>
                                    <?php
                                    if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1 && $rencontre->getVainqueur() == null && !($rencontre->getIdJoueur1() == null && $rencontre->getIdJoueur2() == null) && !($rencontre->getIdJoueur1() == null xor $rencontre->getIdJoueur2() == null && $i >= 2)): ?>
                                        <a href="editerRencontre.php?idRencontre=<?php echo $rencontre->getId()?>&idTournoi=<?php echo $idTournoi?>">
                                            <?php
                                            echo $rencontre->getScoreJoueur2();
                                            ?>
                                        </a>
                                    <?php
                                    else:
                                        echo $rencontre->getScoreJoueur2();
                                    endif;
                                    ?>
                                </li>
                            </ul>
                        <?php
                        $idRencontre++;
                        endfor;
                        ?>
                    </li>
                <?php endfor; ?>
            </ul>
        <?php if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1 && $tournoi->getIdStatut() != 2 && $tournoi->getIdStatut() != 4 ): ?>
        <form action="supressionArbreTournoi.php?id=<?php echo $idTournoi ?>" method="post">
            <button type="submit" class="suprimerArbre">Supprimer arbre</button>
        </form>
        <?php endif; ?>
        <?php else: ?>
        <p id="aucunArbreGenere">Aucun arbre n'a été généré pour le moment</p>
        <?php endif; ?>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>

