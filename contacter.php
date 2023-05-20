<?php
    session_start();
    require 'php/envoieCourriel.php';

    if (isset($_POST['message'])) {
        $intitule = $_POST['intitule'];
        $message = $_POST['message'];
        if (isset($_SESSION['courriel'])) {
            $courriel = $_SESSION['courriel'];
        } else if (isset($_POST['mail'])) {
            $courriel = $_POST['mail'];
        } else {
            $resultat = 'Veuillez fournir une adresse e-mail';
            return;
        }
        envoieCourriel($courriel, $intitule, $message, $resultat);
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
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
            <h1>Contacter</h1>
                <form method="post">
                    <label for="mail">Adresse mail</label><input id="mail" name="mail" type="email" value="<?php echo isset($_SESSION['courriel']) ? $_SESSION['courriel'] : ''; ?>">
                    <label for="intitule">Intitulé</label><input id="intitule" name="intitule" type="text">
                    <label for="message">Message</label><textarea id="message" rows="10" name="message" required></textarea>
                    <button type="submit">Envoyer</button>
                </form>
            <?php
            if (isset($resultat)) {
                echo '<p class="messageSucces">' . $resultat . '</p>';
            }
            ?>
        </section>
    </main>
    <footer id="footerAccueilPrincipal">
        <p>By Alex Tadino</p>
    </footer>
</body>
</html>