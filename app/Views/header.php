<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src=<?= site_url() . "js_script/header.js"?>></script>
    <link rel="stylesheet" href=<?= site_url() . "css/header.css"?>>
    <title>Hot genre</title>
</head>
<body>
<header>
    <div id="header_not_sticky" class="header_not_sticky">
      <div class="header_top">
        <div></div>

        <a href="<?= url_to('Home::index') ?>">
            <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png"?>" alt="Logo">
        </a>

        <div class="icons_container">

          <div class="icon_container">
            <a href="<?= url_to('ClientController::afficherPanier') ?>">
              <div class="icon_logo_background">
                <img class="icon_logo" src="<?= site_url() . "images/icons/cart.png"?>">
                <img class="hover_image" src="<?= site_url() . "images/icons/cart_blanc.png"?>">
              </div>
            </a>

            <a href="<?= url_to('ClientController::afficherPanier') ?>">
              <h3 class="underline_animation">Mon panier</h3>
            </a>
          </div>

          <div class="icon_container">
            <a href="<?= url_to('ClientController::afficherFavoris') ?>">
              <div class="icon_logo_background">
                <img class="icon_logo" src="<?= site_url() . "images/icons/favoris.png"?>">
                <img class="hover_image" src="<?= site_url() . "images/icons/favoris_blanc.png"?>">
              </div>
            </a>

            <a href="<?= url_to('ClientController::afficherFavoris') ?>">
              <h3 class="underline_animation">Mes favoris</h3>
            </a>
          </div>
          
          <div class="icon_container">
            <a href="<?= url_to('ClientController::monCompte') ?>">
              <div class="icon_logo_background">
                <img class="icon_logo" src="<?= site_url() . "images/icons/account.png"?>">
                <img class="hover_image" src="<?= site_url() . "images/icons/account_blanc.png"?>">
              </div>
            </a>

            <a href="<?= url_to('ClientController::monCompte') ?>">
              <h3 class="underline_animation"><?= ($session["prenom"] != NULL) ? $session["prenom"] : "Mon Compte" ?></h3>
            </a>
          </div>
        </div>
      </div>

      <nav>
        <div class="shop_dropdown">
          <a class="underline_animation" href="<?= url_to('Product::displayAll') ?>">SHOP ˅</a>

          <div class="dropdown_content">
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'sweat') ?>"><div><h3>Sweats</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'tshirt') ?>"><div><h3>T-shirts</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'pantalon') ?>"><div><h3>Pantalons</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'accessoire') ?>"><div><h3>Accessoires</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'poster') ?>"><div><h3>Posters</h3></div></a>
          </div>
        </div>

        <a class="underline_animation" href="">FEED</a>
        <a class="underline_animation" href="">LOOKBOOK</a>
        <a class="underline_animation" href="">QUI SOMMES NOUS?</a>
        <a class="underline_animation" href="">CONTACT</a>
      </nav>
    </div>


    <div id="header_sticky" class="header_sticky isSticky">
      <div>
        <a href="<?= url_to('Home::index') ?>">
            <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png"?>" alt="Logo">
        </a>
      </div>

      <div class="navigation">
        <nav>
          <div class="shop_dropdown">
            <a class="underline_animation" href="<?= url_to('Product::displayAll') ?>">SHOP ˅</a>

            <div class="dropdown_content">
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'sweat') ?>"><div><h3>Sweats</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'tshirt') ?>"><div><h3>T-shirts</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'pantalon') ?>"><div><h3>Pantalons</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'accessoire') ?>"><div><h3>Accessoires</h3></div></a>
            <a href="<?= url_to('Product::trouverToutDeCategorie', 'poster') ?>"><div><h3>Posters</h3></div></a>
            </div>
          </div>


          <a class="underline_animation" href="">FEED</a>
          <a class="underline_animation" href="">LOOKBOOK</a>
          <a class="underline_animation" href="">QUI SOMMES NOUS?</a>
          <a class="underline_animation" href="">CONTACT</a>
        </nav>
      </div>

      <div class="icons_container">

        <a href="<?= url_to('ClientController::afficherPanier') ?>">
          <div class="icon_logo_background">
            <img class="icon_logo" src="<?= site_url() . "images/icons/cart.png"?>">
            <img class="hover_image" src="<?= site_url() . "images/icons/cart_blanc.png"?>">
          </div>
        </a>
        
        <a href="<?= url_to('ClientController::afficherFavoris') ?>">
          <div class="icon_logo_background">
            <img class="icon_logo" src="<?= site_url() . "images/icons/favoris.png"?>">
            <img class="hover_image" src="<?= site_url() . "images/icons/favoris_blanc.png"?>">
          </div>
        </a>

        <a href="<?= url_to('ClientController::monCompte') ?>">
          <div class="icon_logo_background">
            <img class="icon_logo" src="<?= site_url() . "images/icons/account.png"?>">
            <img class="hover_image" src="<?= site_url() . "images/icons/account_blanc.png"?>">
          </div>
        </a>
      </div>
    </div>
  </header>

  <div class="sticky_detection"></div>
</body>
</html>