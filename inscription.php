<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="headerInscription">
        <img src="images/logo.png" alt="LOGO" width="100" height="100">
    </header>
    <main class="mainFormulaire">
        <section class="formulaire">
            <h1>Inscription</h1>
            <form class="formInscription" method="post" action="php/inscription.php" enctype="multipart/form-data">
                <div>
                    <label for="mail">Adresse mail</label><input id="mail" name="mail" type="email" required>
                    <label for="passeword">Mot de passe</label><input id="passeword" name="passeword" type="password" required>
                    <label for="reconfirmerPasseword">Reconfirmer votre mot de passe</label><input id="reconfirmerPasseword" name="reconfirmerPasseword" type="password" required>
                    <label for="pseudo">Pseudo</label><input id="pseudo" name="pseudo" type="text" required>
                </div>
                <div>
                    <label for="prenom">Prenom</label><input id="prenom" name="prenom" type="text" required>
                    <label for="nomFamille">Nom de famille</label><input id="nomFamille" name="nomFamille" type="text" required>
                    <label for="photoProfil">Photo de profil</label><input id="photoProfil" name="photoProfil" type="file" accept="image/*">
                    <button type="submit" name="inscription">Inscription</button>
                </div>
            </form>
            <?php
            if (isset($_GET['message'])) {
                if ($_GET['message'] == 'success') {
                    echo '<p class="messageSucces">Votre compte a été créé avec succès ! <a href="connexion.php">Se connecter</a></p>';
                } else {
                    echo '<p class="messageErreur">' . $_GET['message'] . '</p>';
                }
            }
            ?>
        </section>
    </main>
    <footer id="footerAccueilPrincipal">
        <p>By Alex Tadino</p>
    </footer>
</body>
</html>