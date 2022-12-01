<header>
    <div id="header_not_sticky" class="header_not_sticky">
      <div class="header_top">
        <div></div>

        <a href="<?= url_to('Home::index') ?>">
            <img class="logo" src="images/logos/logo hg noir.png" alt="Logo">
        </a>

        <div class="icons_container">
          <div class="icon_container">
            <div class="icon_logo_background">
              <img class="icon_logo" src="images/icons/cart.png">
            </div>

            <h3 class="underline_animation">Mon panier</h3>
          </div>

          <div class="icon_container">
            <div class="icon_logo_background">
              <img class="icon_logo" src="images/icons/favoris.png">
            </div>

            <h3 class="underline_animation">Mes favoris</h3>
          </div>

          <div class="icon_container">
            <div class="icon_logo_background">
              <img class="icon_logo" src="images/icons/account.png">
            </div>

            <h3 class="underline_animation">Mon compte</h3>
          </div>
        </div>
      </div>

      <nav>
        <div class="shop_dropdown">
          <a class="underline_animation" href="<?= url_to('Product::displayAll') ?>">SHOP ˅</a>

          <div class="dropdown_content">
            <div><h3>Sweats</h3></div>
            <div><h3>T-shirts</h3></div>
            <div><h3>Pantalons</h3></div>
            <div><h3>Accessoires</h3></div>
            <div><h3>Posters</h3></div>
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
        <img class="logo" src="images/logos/logo hg noir.png" alt="Logo">
      </div>

      <div class="navigation">
        <nav>
          <div class="shop_dropdown">
            <a class="underline_animation" href="">SHOP ˅</a>

            <div class="dropdown_content">
              <div><h3>Sweats</h3></div>
              <div><h3>T-shirts</h3></div>
              <div><h3>Pantalons</h3></div>
              <div><h3>Accessoires</h3></div>
              <div><h3>Posters</h3></div>
            </div>
          </div>


          <a class="underline_animation" href="">FEED</a>
          <a class="underline_animation" href="">LOOKBOOK</a>
          <a class="underline_animation" href="">QUI SOMMES NOUS?</a>
          <a class="underline_animation" href="">CONTACT</a>
        </nav>
      </div>

      <div class="icons_container">
        <div class="icon_logo_background">
          <img class="icon_logo" src="images/icons/cart.png">
        </div>

        <div class="icon_logo_background">
          <img class="icon_logo" src="images/icons/favoris.png">
        </div>

        <div class="icon_logo_background">
          <img class="icon_logo" src="images/icons/account.png">
        </div>
      </div>
    </div>
  </header>

  <div class="sticky_detection"></div>