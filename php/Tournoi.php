<?php
namespace EWIN;
require_once 'db_link.inc.php';
require 'User.php';
require 'Rencontre.php';
use DB\DBLink;
use Exception;
use PDO;

class Tournoi
{
    const TABLE_NAME = 'ewin_tournoi';

    private $id;
    private $nom;
    private $sportId;
    private $placesDispo;
    private $idStatut;
    private $dateTournoi;
    private $dateFinInscription;
    private $estActif;

    /**
     * Retourne tous les tournois
     * @param $message
     * @return array|false|void
     */
    public function allTournois($message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE estActif = :estActif ORDER BY dateTournoi ASC");
            $estActif1 = 1;
            $stmt->bindParam(':estActif', $estActif1);
            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Ewin\Tournoi');
            return $result;

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Retourne tous les tournois avec un certains filtres
     * @param $idUser
     * @param $filtreStatut
     * @param $filtreSport
     * @param $message
     * @return array|false|void
     */
    public function tournoisFiltres($idUser, $filtreStatut, $filtreSport, $message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE estActif = :estActif";

            // Tournoi actif
            $estActif1 = 1;

            // Tournoi auquel l'utilisateur participe
            if ($idUser != null) {
                $query .= " AND id IN (SELECT id_tournoi FROM ewin_participer WHERE id_user = :idUser)";
            }

            // Tournoi d'un certain statut
            if ($filtreStatut != 'Tous les statuts') {
                $query .= " AND idStatut = (SELECT id_statut FROM ewin_statut WHERE id_statut = :filtreStatut)";
            }

            // Tournoi d'un certain sport
            if ($filtreSport != 'Tous les sports') {
                $query .= " AND sportId = (SELECT id FROM ewin_sport WHERE id = :filtreSport)";
            }

            $stmt = $bdd->prepare($query . " ORDER BY dateTournoi ASC");
            $stmt->bindParam(':estActif', $estActif1);

            // Bind params pour l'utilisateur et les filtres
            if ($idUser != null) {
                $stmt->bindParam(':idUser', $idUser);
            }

            if ($filtreStatut != 'Tous les statuts') {
                $stmt->bindParam(':filtreStatut', $filtreStatut);
            }

            if ($filtreSport != 'Tous les sports') {
                $stmt->bindParam(':filtreSport', $filtreSport);
            }

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Ewin\Tournoi');
            return $result;

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }


    /**
     * Count le nombre de joueur dans un tournoi
     * @return mixed|void
     */
    public function nbrJoueurInTournoi() {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT COUNT(*) FROM ewin_participer etp JOIN ewin_tournoi et ON etp.id_tournoi = et.id WHERE id_tournoi = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
            return $result[0];

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Retourne les 5 derniers tournois de statut "Ouvert"
     */
    public function derniersTournoisOuverts(&$message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE estActif = :estActif AND idStatut = :idStatut ORDER BY dateTournoi ASC LIMIT 5");
            $estActif1 = 1;
            $stmt->bindParam(':estActif', $estActif1);
            $idStatut1 = 1;
            $stmt->bindParam(':idStatut', $idStatut1);
            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Ewin\Tournoi');
            return $result;

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function creerTournoi($idUser)
    {
        try {
            // Connexion à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête d'insertion du tournoi
            $stmt1 = $bdd->prepare("INSERT INTO ewin_tournoi (nom, sportId, placesDispo, idStatut, dateTournoi, dateFinInscription, estActif) VALUES (:nom, :sportId, :placesDispo, :idStatut, :dateTournoi, :dateFinInscription, :estActif)");
            $stmt1->bindParam(':nom', $this->nom);
            $stmt1->bindParam(':sportId', $this->sportId);
            $stmt1->bindParam(':placesDispo', $this->placesDispo);
            $statutOuvert = 1;
            $stmt1->bindParam(':idStatut', $statutOuvert);
            $stmt1->bindParam(':dateTournoi', $this->dateTournoi);
            $stmt1->bindParam(':dateFinInscription', $this->dateFinInscription);
            $estActif = 1;
            $stmt1->bindParam(':estActif', $estActif);

            // Exécution de la requête d'insertion du tournoi
            $stmt1->execute();

            // Récupération de l'ID du tournoi créé
            $idTournoi = $bdd->lastInsertId();

            // Préparation de la requête d'insertion dans la table ewin_creer
            $stmt2 = $bdd->prepare("INSERT INTO ewin_creer (id_user, id_tournoi) VALUES (:id_user, :id_tournoi)");
            $stmt2->bindParam(':id_user', $idTournoi);
            $stmt2->bindParam(':id_tournoi', $idUser);

            // Exécution de la requête d'insertion dans la table ewin_creer
            $stmt2->execute();

            // Définition de l'ID du tournoi créé dans l'objet
            $this->setId($idTournoi);

            // Récupération du résultat de la requête d'insertion du tournoi
            $result1 = $stmt1->rowCount();

            // Récupération du résultat de la requête d'insertion dans la table ewin_creer
            $result2 = $stmt2->rowCount();

            // Déconnexion de la base de données
            DBLink::disconnect($bdd);

            // Résultat
            if ($result1 == 1 && $result2 == 1) {
                $message = 'success';
                return true;
            } else {
                $message = 'error';
                return false;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        echo $message;
    }


    public function tournoisRecherche($recherche, $message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_tournoi WHERE LOWER(nom) LIKE :recherche");
            $recherche = '%' . strtolower($recherche) . '%';
            $stmt->bindParam(':recherche', $recherche);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Ewin\Tournoi');
            return $result;

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Get id du tournoi
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get nom du tournoi
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Get place disponible du tournoi
     */
    public function getPlacesDispo()
    {
        return $this->placesDispo;
    }

    /**
     * Get statut du tournoi
     */
    public function getStatut($idTournoi, $message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT statut FROM ewin_statut es JOIN ewin_tournoi et ON es.id_statut = et.idStatut WHERE et.id = :idTournoi");
            $stmt->bindParam(':idTournoi', $idTournoi);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            return $result[0];

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Get date du tournoi
     */
    public function getDateTournoi()
    {
        return $this->dateTournoi;
    }

    /**
     * Get date de fin d'inscription
     */
    public function getDateFinInscription()
    {
        return $this->dateFinInscription;
    }

    /**
     * Get id_sport du tournoi
     */
    public function getSportId() {
        return $this->sportId;
    }

    /**
     * Get Sport du tournoi
     */
    public function getSport($idTournoi, $message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT nom_sport FROM ewin_tournoi et JOIN ewin_sport es ON et.sportId = es.id WHERE et.id = :idTournoi");
            $stmt->bindParam(':idTournoi', $idTournoi);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            return $result[0];

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * get id statut du tournoi
     */
    public function getIdStatut() {
        return $this->idStatut;
    }

    /**
     * Get estActif du tournoi
     */
    public function getEstActif()
    {
        return $this->estActif;
    }

    /**
     * Set les attributs du tournoi
     * @param $id
     * @param $nom
     * @param $sportId
     * @param $placeDispo
     * @param $idStatut
     * @param $dateTournoi
     * @param $dateFinInscription
     * @param $estActif
     * @return void
     */
    public function setTournoi($id, $nom, $sportId, $placeDispo, $idStatut, $dateTournoi, $dateFinInscription, $estActif)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->sportId = $sportId;
        $this->placesDispo = $placeDispo;
        $this->idStatut = $idStatut;
        $this->dateTournoi = $dateTournoi;
        $this->dateFinInscription = $dateFinInscription;
        $this->estActif = $estActif;
    }

    /**
     * Set attributs d'un nouveau tournoi
     */
    public function setNouveauTournoi($nom, $sportId, $placeDispo, $dateTournoi, $dateFinInscription)
    {
        $this->nom = $nom;
        $this->sportId = $sportId;
        $this->placesDispo = $placeDispo;
        $this->dateTournoi = $dateTournoi;
        $this->dateFinInscription = $dateFinInscription;
    }

    public function setTournoiWithId($idTournoi)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_tournoi WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $idTournoi);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            $this->id = $result['id'];
            $this->nom = $result['nom'];
            $this->sportId = $result['sportId'];
            $this->placesDispo = $result['placesDispo'];
            $this->idStatut = $result['idStatut'];
            $this->dateTournoi = $result['dateTournoi'];
            $this->dateFinInscription = $result['dateFinInscription'];
            $this->estActif = $result['estActif'];

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Avoir l'ensemble des joueurs d'un tournoi
     */
    public function getJoueurs(&$message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_participer ep JOIN ewin_users eu ON ep.id_user = eu.id WHERE ep.id_tournoi = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Ewin\User');
            return $result;
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    private function setId($idTournoi)
    {
        $this->id = $idTournoi;
    }

    /**
     * Supprimer un tournoi
     * @return void
     */
    public function deleteTournoi()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET estActif = 0 WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        echo $message;
    }

    public function isPlayerInTournoi($idPlayer, &$message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_participer WHERE id_user = :idUser AND id_tournoi = :idTournoi");
            $stmt->bindParam(':idUser', $idPlayer);
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            if ($result) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function addPlayerToTournoi($id, $message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("INSERT INTO ewin_participer (id_user, id_tournoi, date_inscription) VALUES (:idUser, :idTournoi, :dateInscription)");
            $stmt->bindParam(':idUser', $id);
            $stmt->bindParam(':idTournoi', $this->id);
            // date d'aujourd'hui
            $dateDuJouer = time();
            // formatage de la date
            $dateDuJouer = date('Y-m-d', $dateDuJouer);
            $stmt->bindParam(':dateInscription', $dateDuJouer);

            // Exécution de la requête
            $stmt->execute();

            if ($this->isTournoiFull()) {
                $this->closeTournoi();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        echo $message;
    }

    public function isPlayerCanJoinTournoi($id, &$message)
    {
        if ($this->isPlayerInTournoi($id, $message)) {
            return false;
        } else if ($this->isTournoiFull()) {
            return false;
        } else if (!$this->isTournoiOpen()) {
            return false;
        } else if ($this->dateFinInscription < time()) {
            return false;
        } else {
            return true;
        }
    }

    private function isTournoiFull()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT COUNT(*) FROM ewin_participer WHERE id_tournoi = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
            if ($result[0] >= $this->placesDispo) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    private function isTournoiOpen()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_tournoi WHERE id = :idTournoi AND idStatut = :idStatus");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut1 = 1;
            $stmt->bindParam(':idStatus', $idStatut1);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
            if ($result) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    private function closeTournoi()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut2 = 3;
            $stmt->bindParam(':idStatus', $idStatut2);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function deletePlayerFromTournoi($idJoueur, $message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("DELETE FROM ewin_participer WHERE id_user = :idUser AND id_tournoi = :idTournoi");
            $stmt->bindParam(':idUser', $idJoueur);
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            if (!$this->isTournoiFull()) {
                $this->openTournoi();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    private function openTournoi()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut1 = 1;
            $stmt->bindParam(':idStatus', $idStatut1);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function updateStatsutCloture()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut2 = 5;
            $stmt->bindParam(':idStatus', $idStatut2);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function setStatutCloture()
    {
        $this->idStatut = 5;
    }

    public function hasAMatch()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_rencontre WHERE idTournoi = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
            if ($result) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function updateStatutGenere($message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut3 = 6;
            $stmt->bindParam(':idStatus', $idStatut3);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function getRencontres($message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_rencontre WHERE idTournoi = :idTournoi ORDER BY id");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'EWIN\Rencontre');
            return $result;

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function updateStatutEnCours($message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut4 = 4;
            $stmt->bindParam(':idStatus', $idStatut4);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        echo $message;
    }

    public function updateStatutTermine($message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut5 = 2;
            $stmt->bindParam(':idStatus', $idStatut5);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function lastIdRencontre()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT MAX(id) FROM ewin_rencontre WHERE idTournoi = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
            return $result[0];

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function supprimerArbreTournoi()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("DELETE FROM ewin_rencontre WHERE idTournoi = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function updateStatsutFerme()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET idStatut = :idStatus WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $idStatut5 = 3;
            $stmt->bindParam(':idStatus', $idStatut5);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function setNomTournoi($nomTournoi)
    {
        $this->nomTournoi = $nomTournoi;
    }

    public function setSport($sportTournoi)
    {
        $this->sportTournoi = $sportTournoi;
    }

    public function setNbrJoueur($nbrJoueur)
    {
        $this->nbrJoueur = $nbrJoueur;
    }

    public function setDateTournoi($dateTournoi)
    {
        $this->dateTournoi = $dateTournoi;
    }

    public function setDateFinInscription($dateFinInscription)
    {
        $this->dateFinInscription = $dateFinInscription;
    }

    public function updateTournoi()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_tournoi SET nom = :nomTournoi, sportId = :sportTournoi, placesdispo = :nbrJoueur, dateTournoi = :dateTournoi, dateFinInscription = :dateFinInscription WHERE id = :idTournoi");
            $stmt->bindParam(':idTournoi', $this->id);
            $stmt->bindParam(':nomTournoi', $this->nomTournoi);
            $stmt->bindParam(':sportTournoi', $this->sportTournoi);
            $stmt->bindParam(':nbrJoueur', $this->nbrJoueur);
            $stmt->bindParam(':dateTournoi', $this->dateTournoi);
            $stmt->bindParam(':dateFinInscription', $this->dateFinInscription);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }

        echo $message;
    }


}