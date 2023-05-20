<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="headerConnexion">
        <img src="images/logo.png" alt="LOGO" width="100" height="100">
    </header>
    <main class="mainFormulaire">
        <section class="formulaire">
            <h1>Connexion</h1>
            <form method="post" action="php/connexion.php">
                <label for="mail">Adresse mail/Pseudo</label><input id="mail" name="mail" type="text" required>
                <label for="passeword">Mot de passe</label><input id="passeword" name="passeword" type="password" required>
                <button type="submit" name="connexion" id="bouttonConnexion"><u>Connexion</u></button>
                <div id="lienInscriptionOubliMDP">
                    <a href="inscription.php">Créer un compte</a>
                    <a href="oubliMotDePasse.php">Mot de passe oublié ?</a>
                </div>
            </form>
            <?php
                if (isset($_GET['message'])) {
                    if ($_GET['message'] == 'error') {
                        echo '<p class="messageErreur">Adresse mail ou mot de passe incorrect</p>';
                    }
                }
            ?>
        </section>
    </main>
    <footer id="footerAccueilPrincipal">
        <p>By Alex Tadino</p>
        <a href="contacter.php">Contacter</a>
    </footer>
</body>
</html>