<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/compte.css"?>>
    <title>Hot genre</title>
</head>
<body>
    <header>
        <div>
            <a href="<?= url_to('Home::index') ?>">
                <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png"?>" alt="Logo">
            </a>
        </div>

        <h3 class="underline_animation"><?= "Bonjour, " . $session["prenom"] . "!" ?></h3>

        <div>
            <a href="<?= url_to('ClientController::deconnexion') ?>">
                <button class="deconnexion" type="submit">Déconnexion</button>
            </a>
        </div>
    </header>

    <div class="nav_container">
        <a href="<?= url_to('ClientController::') ?>">
            <div class="nav_element <?= ($compteAction == "profil" ? "selected" : "") ?>">
                <img class="logo" src="<?= site_url() . "images/icons/compte/profil_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/profil_plein.png"?>" alt="Logo">

                <h4>PROFIL</h4>
            </div>
        </a>

        <a href="<?= url_to('ClientController::afficherFavoris') ?>">
            <div class="nav_element <?= ($compteAction == "favoris" ? "selected" : "") ?>">
                <img class="logo" src="<?= site_url() . "images/icons/compte/favoris_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/favoris_plein.png"?>" alt="Logo">

                <h4>FAVORIS</h4>
            </div>
        </a>

        <a href="<?= url_to('ClientController::afficherPanier') ?>">
            <div class="nav_element <?= ($compteAction == "panier" ? "selected" : "") ?>">
                <img class="logo" src="<?= site_url() . "images/icons/compte/cart_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/cart_plein.png"?>" alt="Logo">

                <h4>PANIER</h4>
            </div>
        </a>

        <a href="<?= url_to('ClientController::afficherHistorique') ?>">
            <div class="nav_element <?= ($compteAction == "historique" ? "selected" : "") ?>">
                <img class="logo" src="<?= site_url() . "images/icons/compte/historique_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/historique_plein.png"?>" alt="Logo">

                <h4>HISTORIQUE</h4>
            </div>
        </a>
        
        <a href="<?= url_to('ClientController::deconnexion') ?>">
            <div class="nav_element">
                <img class="logo" src="<?= site_url() . "images/icons/compte/deconnexion_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/deconnexion_plein.png"?>" alt="Logo">

                <h4>DECONNEXION</h4>
            </div>
        </a>
    </div>

    <section class="compte_action">

    <?php
         switch ($compteAction) {
            case "profil":
                include 'profil.php';
                break;

            case "favoris":
                include 'favoris.php';
                break;

            case "panier":
                include 'panier.php';
                break;

            case "validerCommandeAdresse":
                include 'validerCommandeAdresse.php';
                break;

            case "historique":
                include 'historique.php';
                break;

            case "detailCommande":
                include 'detailCommande.php';
                break;
        }
    ?>

    </section>
</body>
</html>