<?php
require 'php/Tournoi.php';
use EWIN\Tournoi;

session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Les tournois</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>
    <main>
        <h1 id="titreListeTournoi">Les tournois</h1>
        <section class="rechercheFiltreTournoi">
            <form id="rechercheTournoiConteneur" method="post">
                <label for="rechercheTournoi" id="labelRechercheTournoi">Recherche</label>
                <input id="rechercheTournoi" name="rechercheTournoi" type="text">
                <button type="submit" name="recherche" id="buttonRechercheTournoi"><img src="images/recherche.png" width="10" height="10" alt="RECHERCHE"></button>
            </form>
            <form class="filtreTournoi" method="post">
                <?php if(isset($_SESSION['id'])) :?>
                <label for="mesTournois" id="labelMesTournois">Mes tournois<input type="checkbox" id="mesTournois" name="mesTournois"></label>
                <?php endif; ?>
                <label for="statutTournoi" id="labelStatuttournoi">Statut
                    <select name="statutTournoi" id="statutTournoi">
                        <option value="tousLesStatuts">Tous les statuts</option>
                        <option value="ouvert">Ouvert</option>
                        <option value="termine">Terminé</option>
                        <option value="ferme">Fermé</option>
                        <option value="cloture">Cloturé</option>
                        <option value="genere">Généré</option>
                        <option value="enCours">En-cours</option>
                    </select>
                </label>
                <label for="sportsTournoi" id="labelsportsTournoi">Sports
                    <select name="sportsTournoi" id="sportsTournoi">
                        <option value="tousLesSports">Tous les sports</option>
                        <option value="belotte">Belotte</option>
                        <option value="jeuEchecs">Jeu d'échecs</option>
                        <option value="tennis">Tennis</option>
                        <option value="pingPong">Ping-Pong</option>
                        <option value="fifa">FIFA</option>
                    </select>
                </label>
                <button type="submit" name="filtrer" id="buttonFiltrer">Filtrer</button>
            </form>
        </section>
        <section class="listeTournois">
            <?php
            // Obtenir l'ensemble des tournois
            $tournois = new Tournoi();
            $message = '';

            // Si on a cliqué sur le bouton "Filtrer"
            if (isset($_POST['filtrer']) && isset($_POST['statutTournoi']) && isset($_POST['sportsTournoi'])) {
                $filtreMesTournois = '';
                $idUser = null;

                // Si cochez l'option "Mes tournois"
                if (isset($_POST['mesTournois'])) {
                    $idUser = isset($_SESSION['id']) ? $_SESSION['id'] : null;
                    if ($idUser == null) {
                        $message = 'Vous devez être connecté pour voir vos tournois';
                    }
                }

                // Filtre du statut
                $filtreStatut = '';
                switch ($_POST['statutTournoi']) {
                    case 'ouvert':
                        $filtreStatut = 1;
                        break;
                    case 'termine':
                        $filtreStatut = 2;
                        break;
                    case 'ferme':
                        $filtreStatut = 3;
                        break;
                    case 'enCours':
                        $filtreStatut = 4;
                        break;
                    case 'cloture':
                        $filtreStatut = 5;
                        break;
                    case 'genere':
                        $filtreStatut = 6;
                        break;
                    default:
                        $filtreStatut = 'Tous les statuts';
                        break;
                }

                // Filtre du sport
                $filtreSport = '';
                switch ($_POST['sportsTournoi']) {
                    case 'belotte':
                        $filtreSport = 1;
                        break;
                    case 'jeuEchecs':
                        $filtreSport = 2;
                        break;
                    case 'tennis':
                        $filtreSport = 3;
                        break;
                    case 'pingPong':
                        $filtreSport = 4;
                        break;
                    case 'fifa':
                        $filtreSport = 5;
                        break;
                    default:
                        $filtreSport = 'Tous les sports';
                        break;
                }

                // Tournois avec le filtre
                $tournois = $tournois->tournoisFiltres($idUser, $filtreStatut, $filtreSport, $message);
            } elseif (isset($_POST['recherche'])) {
                // Récupération de la recherche
                $recherche = $_POST['rechercheTournoi'];

                // Récuprérer les tournois avec la recherche
                $tournois = $tournois->tournoisRecherche($recherche, $message);
            } else {
                // Tournoi sans le filtre
                $tournois = $tournois->allTournois($message);
            }

            for ($i = 0; $i < count($tournois); $i++) : ?>
            <section class="tournoi">
                <?php
                // Setter du tournoi
                $tournoi = new Tournoi();
                $tournoi->setTournoi($tournois[$i]->getId(), $tournois[$i]->getNom(), $tournois[$i]->getSportId(), $tournois[$i]->getPlacesDispo(), $tournois[$i]->getIdStatut(), $tournois[$i]->getDateTournoi(), $tournois[$i]->getDateFinInscription(), $tournois[$i]->getEstActif());

                // Vérifier si le tournoi est clôturé
                $dateDateFinInscription = new DateTime($tournoi->getDateFinInscription());
                if($dateDateFinInscription < new DateTime() && $tournoi->getStatut($tournoi->getId(), $message) == 'Ouvert') {
                    $tournoi->updateStatsutCloture();
                    $tournoi->setStatutCloture();
                }

                // Affichage des tournois
                switch ($tournoi->getStatut($tournois[$i]->getId(), $message)) {
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
                <a href="consulterTournoi.php?id=<?php echo $tournoi->getId()?>"><img class="imageSportTournoi" src="images/<?php
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
                    ?>" alt="SportTournoiImage" width="200" height="200"></a>
                <div class="infosTournoi">
                    <div class="dateTournoiConteneur">
                        <img src="images/calendrier.png" alt="CALENDRIER" width="20" height="20">
                        <span class="dateTournoi">
                            <?php
                            $dateTournoi = $tournoi->getDateTournoi();
                            $datetimeTournoi = DateTime::createFromFormat("Y-m-d", $dateTournoi);
                            $new_dateTournoi = $datetimeTournoi->format("d F Y");
                            echo $new_dateTournoi;
                            ?>
                        </span>
                    </div>
                    <h3 class="nomTournoi"><a href="consulterTournoi.php?id=<?php echo $tournoi->getId()?>"><?php echo $tournoi->getNom(); ?></a></h3>
                    <div class="nombreJoueur">
                        <img src="images/joueur.png" alt="JOUEUR" width="30" height="30">
                        <p><?php
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
                    <p class="sportTournoiTextuel">
                        <?php
                            echo $tournoi->getSport($tournoi->getId(), $message);
                        ?>
                    </p>
                </div>
            </section>
            <?php endfor; ?>
        </section>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>