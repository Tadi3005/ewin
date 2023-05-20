<?php

namespace EWIN;

use DB\DBLink;
use PDO;

class Rencontre
{
    private $id;
    private $idTournoi;
    private $idJoueur1;
    private $idJoueur2;
    private $scoreJoueur1;
    private $scoreJoueur2;
    private $vainqueur;
    private $idRencontreNext;

    /**
     * Set rencontre
     */
    public function setRencontre($idTournoi, $idJoueur1, $idJoueur2, $scoreJoueur1, $scoreJoueur2, $vainqueur, $idRencontreNext) {
        $this->id= $this->getLastId();
        $this->idTournoi = $idTournoi;
        $this->idJoueur1 = $idJoueur1;
        $this->idJoueur2 = $idJoueur2;
        $this->scoreJoueur1 = $scoreJoueur1;
        $this->scoreJoueur2 = $scoreJoueur2;
        $this->vainqueur = $vainqueur;
        $this->idRencontreNext = $idRencontreNext;
    }

    /**
     * Récupérer le dernier id et l'incrémenter de 1
     */
    public function getLastId()
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT MAX(id) FROM ewin_rencontre");

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
            if ($result) {
                return $result[0] + 1;
            } else {
                return 1;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Ajouter une rencontre dans la base de données
     */
    public function addRencontre(&$message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("INSERT INTO ewin_rencontre (idTournoi, idJoueur1, idJoueur2, scoreJoueur1, scoreJoueur2, vainqueur, idRencontreNext) VALUES (:idTournoi, :idJoueur1, :idJoueur2, :scoreJoueur1, :scoreJoueur2, :vainqueur, :idRencontreNext)");
            $stmt->bindParam(':idTournoi', $this->idTournoi);
            $stmt->bindParam(':idJoueur1', $this->idJoueur1);
            $stmt->bindParam(':idJoueur2', $this->idJoueur2);
            $stmt->bindParam(':scoreJoueur1', $this->scoreJoueur1);
            $stmt->bindParam(':scoreJoueur2', $this->scoreJoueur2);
            $stmt->bindParam(':vainqueur', $this->vainqueur);
            $stmt->bindParam(':idRencontreNext', $this->idRencontreNext);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        echo $message;
    }

    /**
     * Obtenir le pseudo d'un joueur à partir de son id
     */
    public function getPseudo($idJoueur) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT pseudo FROM ewin_users WHERE id = :idJoueur");
            $stmt->bindParam(':idJoueur', $idJoueur);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            if ($result) {
                return $result[0];
            } else {
                return null;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function setRencontreWithId($idRencontre, $message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_rencontre WHERE id = :idRencontre");
            $stmt->bindParam(':idRencontre', $idRencontre);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            if ($result) {
                $this->id = $result[0];
                $this->idTournoi = $result[1];
                $this->idJoueur1 = $result[2];
                $this->idJoueur2 = $result[3];
                $this->scoreJoueur1 = $result[4];
                $this->scoreJoueur2 = $result[5];
                $this->vainqueur = $result[6];
                $this->idRencontreNext = $result[7];
            } else {
                return null;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function updateVainqueur($vainqueur)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_rencontre SET vainqueur = :vainqueur WHERE id = :idRencontre");
            $stmt->bindParam(':vainqueur', $vainqueur);
            $stmt->bindParam(':idRencontre', $this->id);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function getIdRencontreNext()
    {
        return $this->idRencontreNext;
    }

    public function updateScore($scoreJ1, $scoreJ2)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("UPDATE ewin_rencontre SET scoreJoueur1 = :scoreJ1, scoreJoueur2 = :scoreJ2 WHERE id = :idRencontre");
            $stmt->bindParam(':scoreJ1', $scoreJ1);
            $stmt->bindParam(':scoreJ2', $scoreJ2);
            $stmt->bindParam(':idRencontre', $this->id);

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function nextRencontreConfiguration($vainqueur, $idRencontreNext)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Voir si la rencontre numero 1 de la prochaine rencontre est déjà configurée
            $req1 = $bdd->prepare("SELECT idJoueur1 FROM ewin_rencontre WHERE id = :idRencontreNext");
            $req1->bindParam(':idRencontreNext', $idRencontreNext);

            if ($req1->execute()) {
                $result = $req1->fetch();
                if ($result[0] == null) {
                    $stmt = $bdd->prepare("UPDATE ewin_rencontre SET idJoueur1 = :vainqueur WHERE id = :idRencontre");
                    $stmt->bindParam(':vainqueur', $vainqueur);
                    $stmt->bindParam(':idRencontre', $this->idRencontreNext);
                } else {
                    $stmt = $bdd->prepare("UPDATE ewin_rencontre SET idJoueur2 = :vainqueur WHERE id = :idRencontre");
                    $stmt->bindParam(':vainqueur', $vainqueur);
                    $stmt->bindParam(':idRencontre', $this->idRencontreNext);
                }
            }

            // Exécution de la requête
            $stmt->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    public function setIdNextRencontre($idRencontreNext) {
        $this->idRencontreNext = $idRencontreNext;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setIdJoueur2($idJoueur2) {
        $this->idJoueur2 = $idJoueur2;
    }

    public function getIdJoueur1() {
        return $this->idJoueur1;
    }

    public function getIdJoueur2() {
        return $this->idJoueur2;
    }
    public function getIdNextRencontre() {
        return $this->idRencontreNext;
    }

    public function getScoreJoueur1()
    {
        return $this->scoreJoueur1;
    }

    public function getScoreJoueur2()
    {
        return $this->scoreJoueur2;
    }

    public function getVainqueur()
    {
        return $this->vainqueur;
    }


}