<?php
if (isset($_POST['deconnexion'])) {
    session_destroy();
    header('Location: index.php');
}
?>
<header>
    <a href="index.php"><img src="images/logo.png" alt="LOGO" width="50" height="50"></a>
    <nav>
        <ul class="nav">
            <li><a href="listeTournois.php">Liste des tournois</a></li>
            <?php
                if (isset($_SESSION['estOrganisateur']) && $_SESSION['estOrganisateur'] == 1) {
                    echo '<li><a href="creerTournoi.php">Créer un tournoi</a></li>';
                }
            ?>
            <li><a href="editerProfil.php">Editer profil</a></li>
            <li><a href="contacter.php">Contacter</a></li>
        </ul>
    </nav>
    <section class="infosConnecte">
        <p>
            <?php
                if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])) {
                    echo $_SESSION['prenom'] . " " . $_SESSION['nom'];
                } else {
                    echo "Visiteur" . " ". "<a href='connexion.php'>(Se connecter)</a>";
                }
            ?>
        </p>
        <?php
        if (isset($_SESSION['urlPhoto'])) {
            echo '<a href="editerProfil.php"><img src="upload/'.  $_SESSION['urlPhoto']  . '" alt="PROFIL" width="70" height="70"></a>';
        } else {
            echo '<img src="images/photoProfil.png" alt="PROFIL" width="70" height="70"></a>';
        }
        if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])) : ?>
        <form method="post">
            <input type="submit" name="deconnexion" value="Déconnexion" class="deconnexion">
        </form>
        <?php endif; ?>
    </section>
</header>

