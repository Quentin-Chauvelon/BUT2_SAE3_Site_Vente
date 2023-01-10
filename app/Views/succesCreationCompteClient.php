<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/succesCreationCompteClient.css"?>>
    <title>Hot genre</title>
</head>
<body>
    <a href="<?= url_to("Home::index") ?>">
        <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
    </a>

    <img class="tick" src="<?= site_url() . "images/tick.png" ?>" alt="Valider">

    <div class="text_container" >
        <h1>Votre compte a été créé avec succès. Vous pouvez dès à présent commencer vos achats !</h1>
        <h2>Retourner sur la page <a href="<?= url_to("ClientController::adresseCommande") ?>">d'accueil</a></h2>
    </div>
</body>
</html>