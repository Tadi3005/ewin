<?php
    session_start();
    require 'php/Tournoi.php';
    use eWin\Tournoi;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil eWin</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <header id="headerAccueilPrincipal">
        <img src="images/logo.png" alt="LOGO" width="100" height="100">
        <div id="connexionInscription">
            <a href="contacter.php">Contacter</a>
            <a href="connexion.php">Connexion</a>
            <a href="inscription.php">Inscription</a>
        </div>
    </header>
    <main id="mainAccueil">
        <a href="listeTournois.php" id="lienTousLesTournois">Tous les tournois</a>
        <section class="listeTournois">
            <?php
            $cinqDerniersTournois = new Tournoi();
            $message = '';
            $tournois = $cinqDerniersTournois->derniersTournoisOuverts($message);

            // Vérifier que le tableau est non vide avant de l'utiliser
            if (!empty($tournois)):
                for ($i = 0; $i < count($tournois); $i++) :
            ?>
                <section class="tournoi">
                    <?php
                    switch ($tournois[$i]->getStatut($tournois[$i]->getId(), $message)) {
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
                    }
                    ?>
                    <a href="consulterTournoi.php"><img class="imageSportTournoi" src="images/<?php
                        switch ($tournois[$i]->getSport($tournois[$i]->getId(), $message)) {
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
                        ?>" alt="SportTournoiImage" width="200" height="200">
                    </a>
                    <div class="infosTournoi">
                        <div class="dateTournoiConteneur">
                            <img src="images/calendrier.png" alt="CALENDRIER" width="20" height="20">
                            <span class="dateTournoi">
                                <?php
                                $dateTournoi = $tournois[$i]->getDateTournoi();
                                $datetimeTournoi = DateTime::createFromFormat("Y-m-d", $dateTournoi);
                                $new_dateTournoi = $datetimeTournoi->format("d F Y");
                                echo $new_dateTournoi;
                                ?>
                            </span>
                        </div>
                        <h3 class="nomTournoi">
                            <a class="lienNomTournoi" href="consulterTournoi.php">
                                <?php
                                echo $tournois[$i]->getNom();
                                ?>
                            </a>
                        </h3>
                        <div class="nombreJoueur">
                            <img src="images/joueur.png" alt="JOUEUR" width="30" height="30">
                            <?php
                            $nombreDePlaceRestantes = $tournois[$i]->getPlacesDispo() - $tournois[$i]->nbrJoueurInTournoi();
                            if ($nombreDePlaceRestantes == 0) {
                                echo 'Le tournoi est complet !';
                            } else {
                                echo $tournois[$i]->getPlacesDispo() . ' joueurs (' . $nombreDePlaceRestantes . ' places restantes)';
                            }
                            ?>
                        </div>
                        <p class="dateFinInscription">Inscription jusqu'au :
                            <?php
                            $dateDateFinInscription = $tournois[$i]->getDateFinInscription();
                            $datetimeFinInscription = DateTime::createFromFormat("Y-m-d", $dateDateFinInscription);
                            $new_dateFinInscription = $datetimeFinInscription->format("d F Y");
                            echo $new_dateFinInscription;
                            ?>
                        </p>
                        <p class="sportTournoiTextuel">
                            <?php
                            echo $tournois[$i]->getSport($tournois[$i]->getId(), $message);
                            ?>
                        </p>
                    </div>
                </section>
            <?php
                endfor;
            else:
                echo '<p> Aucun tournoi n\'est disponible pour le moment </p>';
            endif;
            ?>
        </section>
    </main>
    <footer id="footerAccueilPrincipal">
        <p>By Alex Tadino</p>
        <a href="contacter.php">Contacter</a>
    </footer>
</body>
</html>