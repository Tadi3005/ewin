<?php
namespace EWIN;
require_once 'db_link.inc.php';

use DB\DBLink;
use Exception;
use PDO;

class User
{
    const TABLE_NAME = 'ewin_users';

    private $id;
    private $courriel;
    private $pseudo;
    private $nom;
    private $prenom;
    private $motDePasse;
    private $estActif;
    private $estOrganisateur;
    private $urlPhoto;

    /**
     * Connexion d'un utilisateur
     * @param $user User utilisateur à inscrire
     * @param $message string message d'erreur
     * @return bool true si l'inscription est réussie, false sinon
     */
    public function login(&$message) {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);

            // Hachage du mot de passe entré par l'utilisateur
            $password = md5($this->motDePasse);

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE (courriel = :courriel OR pseudo= :pseudo) AND motDePasse = :password");
            $stmt->bindParam(':courriel', $this->courriel);
            $stmt->bindParam(':pseudo', $this->pseudo);
            $stmt->bindParam(':password', $password);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        // Déconexion à la base de données
        DBLink::disconnect($bdd);

        // Vérification des informations d'identification
        if($result) {
            $message = 'success';
            return true;
        } else {
            $message = 'error';
            return false;
        }
    }

    /**
     * Inscription d'un utilisateur
     * @param $user User utilisateur à inscrire
     * @param $message string message d'erreur
     * @return bool true si l'inscription est réussie, false sinon
     */
    public function register($user, &$message) {
        try {
            // hash du mot de passe
            $user->motDePasse = md5($user->motDePasse);

            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);

            // Préparation de la requête
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (courriel, pseudo, nom, prenom, motDePasse, estActif, estOrganisateur, urlPhoto) 
            VALUES (:courriel, :pseudo, :nom, :prenom, :motDePasse, :estActif, :estOrganisateur, :urlPhoto)");
            $stmt->bindParam(':courriel', $user->courriel);
            $stmt->bindParam(':pseudo', $user->pseudo);
            $stmt->bindParam(':nom', $user->nom);
            $stmt->bindParam(':prenom', $user->prenom);
            $stmt->bindParam(':motDePasse', $user->motDePasse);
            $stmt->bindParam(':estActif', $user->estActif);
            $stmt->bindParam(':estOrganisateur', $user->estOrganisateur);
            $stmt->bindParam(':urlPhoto', $user->urlPhoto);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->rowCount();

            // Déconnexion à la base de données
            DBLink::disconnect($bdd);

            // Résultat
            if($result == 1) {
                $message = 'success';
                return true;
            } else {
                $message = 'error';
                return false;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }

    /**
     * Vérifie si l'utilisateur existe déjà
     * @return bool true si l'utilisateur existe, false sinon
     */
    public function exist(&$message) {
        $bdd = DBLink::connect2db(MYDB, $message);

        // Préparation de la requête
        $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE (courriel = :courriel OR pseudo= :pseudo)");
        $stmt->bindParam(':courriel', $this->courriel);
        $stmt->bindParam(':pseudo', $this->pseudo);

        // Exécution de la requête
        $stmt->execute();

        // Récupération du résultat
        $result = $stmt->fetch();

        // Déconexion à la base de données
        DBLink::disconnect($bdd);

        // Resultat
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Modifier le mot de passe d'un utilisateur
     */
    public function updatePassword($nouveauMotDePasse, &$message) {
        $result = 0;
        try {
            $dbb = DBLink::connect2db(MYDB, $message);

            // Préparation de la requête
            $stmt = $dbb->prepare("UPDATE " . self::TABLE_NAME . " SET motDePasse = :motDePasse WHERE courriel = :courriel");
            $stmt->bindParam(':motDePasse', $nouveauMotDePasse);
            $stmt->bindParam(':courriel', $this->courriel);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->rowCount();


        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        // Déconexion à la base de données
        DBLink::disconnect($dbb);

        // Résultat
        if($result == 1) {
            $message = 'success';
            return true;
        } else {
            $message = 'error';
            return false;
        }
    }

    public function editerProfil($nom, $prenom, $pseudo, $courriel, $photoProfil)
    {
        $result = 0;
        try {
            $dbb = DBLink::connect2db(MYDB, $message);

            // Préparation de la requête
            $stmt = $dbb->prepare("UPDATE " . self::TABLE_NAME . " SET nom = :nom, prenom = :prenom, pseudo = :pseudo, courriel = :courriel, urlPhoto = :urlPhoto WHERE courriel = :courriel");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':courriel', $courriel);
            $stmt->bindParam(':urlPhoto', $photoProfil);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->rowCount();
        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        // Déconexion à la base de données
        DBLink::disconnect($dbb);

        // Résultat
        if($result == 1) {
            $message = 'success';
            return true;
        } else {
            $message = 'error';
            return false;
        }
    }

    public function updateSessionAfterEditProfil($nom, $prenom, $pseudo, $courriel, $photoProfil)
    {
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['courriel'] = $courriel;
        $_SESSION['urlPhoto'] = $photoProfil;
    }

    /**
     * Récupère les informations d'un utilisateur
     * @param $user User utilisateur à inscrire
     * @return bool true si l'utilisateur existe, false sinon
     */
    public function setUser($user, $message)
    {
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT id, courriel, pseudo, nom, prenom, motDePasse, estActif, estOrganisateur, urlPhoto FROM " . self::TABLE_NAME . " WHERE (courriel = :courriel OR pseudo= :pseudo)");
            $stmt->bindParam(':courriel', $user->courriel);
            $stmt->bindParam(':pseudo', $user->pseudo);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Ewin\User');
            $result = $stmt->fetch();

            // Déconnexion de la base de données
            DBLink::disconnect($bdd);

            // Retourne le résultat
            return $result;

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
        return false;
    }

    public function storeUserInSession($user) {
        $_SESSION['id'] = $user->id;
        $_SESSION['pseudo'] = $user->pseudo;
        $_SESSION['courriel'] = $user->courriel;
        $_SESSION['nom'] = $user->nom;
        $_SESSION['prenom'] = $user->prenom;
        $_SESSION['estActif'] = $user->estActif;
        $_SESSION['estOrganisateur'] = $user->estOrganisateur;
        $_SESSION['urlPhoto'] = $user->urlPhoto;
    }

    /**
     * Set courriel or pseudo
     * @param $emailOrPseudo string courriel ou pseudo
     * @return void
     */
    public function setCourrielOrPseudo($emailOrPseudo)
    {
        if (filter_var($emailOrPseudo, FILTER_VALIDATE_EMAIL)) {
            $this->courriel = $emailOrPseudo;
        } else {
            $this->pseudo = $emailOrPseudo;
        }
    }

    /**
     * Set courriel
     * @param $email string courriel
     * @return void
     */
    public function setCourriel($email)
    {
        $this->courriel = $email;
    }

    /**
     * Set pseudo
     * @param $pseudo string pseudo
     * @return void
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Set password
     * @param $password string mot de passe
     * @return void
     */
    public function setMotDePasse($password)
    {
        $this->motDePasse = $password;
    }

    /**
     * Set nom
     * @param $nom string nom
     * @return void
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Set prenom
     * @param $prenom string prenom
     * @return void
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Set estActif
     * @param $estActif bool est actif
     * @return void
     */
    public function setEstActif($estActif) {
        $this->estActif = $estActif;
    }

    /**
     * Set estOrganisateur
     * @param $estOrganisateur bool est organisateur
     * @return void
     */
    public function setEstOrganisateur($estOrganisateur) {
        $this->estOrganisateur = $estOrganisateur;
    }

    /**
     * Set photoProfil
     * @param $photoProfil string nom du fichier
     * @return void
     */
    public function setUrlPhoto($urlPhoto)
    {
        $this->urlPhoto = $urlPhoto;
    }

    /**
     * Get name
     */
    public function getName() {
        return $this->nom;
    }

    /**
     * Get pseudo
     */
    public function getPseudo() {
        return $this->pseudo;
    }

    /**
     * Get url photo
     */
    public function getUrlPhoto() {
        return $this->urlPhoto;
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    public function setUserWithId($id, $message)
    {
        try {
            // Connection à la base de données
            $bdd = DBLink::connect2db(MYDB, $message);
            $bdd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            // Préparation de la requête
            $stmt = $bdd->prepare("SELECT * FROM ewin_users WHERE id = :idUser");
            $stmt->bindParam(':idUser', $id);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch();

            if ($result) {
                $this->id = $result[0];
                $this->courriel = $result[1];
                $this->pseudo = $result[2];
                $this->nom = $result[3];
                $this->prenom = $result[4];
                $this->motDePasse = $result[5];
                $this->estActif = $result[6];
                $this->estOrganisateur = $result[7];
                $this->urlPhoto = $result[8];
            } else {
                return null;
            }

        } catch (Exception $e) {
            $message .= $e->getMessage();
        }
    }
}