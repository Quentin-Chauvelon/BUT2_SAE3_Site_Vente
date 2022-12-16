<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/commandeValidee.css"?>>
    <title>Hot genre</title>
</head>
<body>

<a href="<?= url_to('Home::index') ?>">
        <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png" ?>" alt="Logo">
    </a>

    <div class="middle_container">
        <h1>Votre commande a été validée avec succès !</h1>
        <h2>Livraison estimée le <?= date("d/m/Y", mktime(0, 0, 0, date("m"), date("d") + 15, date("Y"))) ?>.</h2>
    </div>

    <div class="bottom_container" >
        <a href="<?= url_to('ClientController::afficherHistorique') ?>">
            <div class="voir_detail">Voir le détail</div>
        </a>

        <h2>Retourner sur la page <a href="<?= url_to('Home::index') ?>">d'accueil</a></h2>
    </div>
</body>
</html>