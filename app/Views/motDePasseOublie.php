<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/connexionCompte.css"?>>
    <script src=<?= site_url() . "js_script/connexionCompte.js"?>></script>
    <title>Hot genre</title>
</head>

<?php
if (!isset($compteNonExistant)) {
    $erreurTexte = "";
} else if ($compteNonExistant) {
    $erreurTexte = "Il ne semble pas d'y avoir de compte correspondant à cette adresse mail. Essayez de vous <a href=".url_to('ClientController::inscription').">inscrire</a> plutôt.";
} else {
    $erreurTexte = "Un email vous a été envoyé. Veuillez suivre les instructions pour réinitialiser votre mot de passe.";
}

?>

<body>
<div class="page_container">
    <div class="left_side_container">
        <img src="<?= site_url() . "images/compteImage.jpg" ?>">
    </div>

    <div class="right_side_container">
        <div class="form">

            <a href="<?= url_to('Home::index') ?>">
                <img src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
            </a>

            <h3>Mot de passe oublié ?</h3>

            <h5 class="erreur_texte"><?= ($erreurTexte != "") ? $erreurTexte : "" ?></h5>

            <form action=<?= url_to('ClientController::envoyerMailChangementMDP') ?> method="post">
                <div>
                    <label for="email">Adresse email *</label>
                    <input type="email" name="email" id="email" placeholder=" " maxlength="255" required/>
                    <h5>Votre adresse email n'est pas valide</h5>
                </div>

                <button type="submit">Réinitialiser mon mot de passe</button>

                <h4 class="connexion_compte">Vous n'avez pas encore de compte ? <a href="<?= url_to('ClientController::inscription') ?>">Inscrivez-vous</a> plutôt.</h4>
            </form>
        </div>
    </div>
</div>
</body>
</html>