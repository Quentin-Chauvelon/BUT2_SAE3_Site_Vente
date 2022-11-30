<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src=<?= site_url() . "js_script/home.js"?>></script>
  <link rel="stylesheet" href=<?= site_url() . "css/home.css"?>>
  <title>Hot genre DEV</title>
</head>

<body>
  <header>
    <div id="header_not_sticky" class="header_not_sticky">
      <div class="header_top">
        <div></div>

        <img class="logo" src="images/logos/logo hg noir.png" alt="Logo">

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

  <!-- <script>
    const el = document.querySelector(".sticky_detection")

    const header = document.querySelector(".header")
    const logo = document.querySelector(".logo")

    const observer = new IntersectionObserver( 
      ([e]) => e.target.classList.toggle("isSticky", e.intersectionRatio < 1),
      { threshold: [1] }
    );

    const logoObserver = new IntersectionObserver( 
      ([e]) => e.target.style.display = 'none'
    );

    observer.observe(el);
    logoObserver.observe(logo);
  </script>
 -->
  <!-- <script>
    const el = document.querySelector(".sticky_detection")

    const observer = new IntersectionObserver( 
      ([e]) => e.target.classList.toggle("isSticky", e.intersectionRatio < 1),
      { threshold: [1] }
    );

    observer.observe(el);
  </script> -->

    <!--
    <div id="carrousel">
        <div class="hideLeft">
         <img src="https://i1.sndcdn.com/artworks-000165384395-rhrjdn-t500x500.jpg">
       </div>
 
       <div class="prevLeftSecond">
         <img src="https://i1.sndcdn.com/artworks-000185743981-tuesoj-t500x500.jpg">
       </div>
 
       <div class="prev">
         <img src="https://i1.sndcdn.com/artworks-000158708482-k160g1-t500x500.jpg">
       </div>
 
       <div class="selected">
         <img src="https://i1.sndcdn.com/artworks-000062423439-lf7ll2-t500x500.jpg">
       </div>
 
       <div class="next">
         <img src="https://i1.sndcdn.com/artworks-000028787381-1vad7y-t500x500.jpg">
       </div>
 
       <div class="nextRightSecond">
         <img src="https://i1.sndcdn.com/artworks-000108468163-dp0b6y-t500x500.jpg">
       </div>
 
       <div class="hideRight">
         <img src="https://i1.sndcdn.com/artworks-000064920701-xrez5z-t500x500.jpg">
       </div>
 
     </div>
   -->


   <div class="carrousel">
    <button class="carrousel_prev carrousel_button"><</button>

    <div class="carrousel_images">
     <img id="carrousel_previous_button" class="prev" src="https://i1.sndcdn.com/artworks-000165384395-rhrjdn-t500x500.jpg">
     <img class="selected" src="https://i1.sndcdn.com/artworks-000185743981-tuesoj-t500x500.jpg">
     <img id="carrousel_next_button" class="next" src="https://i1.sndcdn.com/artworks-000158708482-k160g1-t500x500.jpg">
   </div>

   <button class="carrousel_next carrousel_button">></button>
 </div>


 <div class="categories">
   <div class="posters">
    <div class="posters_image">
     <img src="images/categories/poster.jpeg" alt="Posters">
    </div>
    <h2>Posters</h2>
   </div>

   <div class="accessoires">
    <div class="accessoires_image">
     <img src="images/categories/accessoire.jpeg" alt="Accessoires">
    </div>
    <h2>Accessoires</h2>
   </div>

   <div class="posters">
    <div class="posters_image">
     <img src="images/categories/poster.jpeg" alt="Posters">
    </div>
    <h2>T-shirts</h2>
   </div>

   <div class="accessoires">
    <div class="accessoires_image">
     <img src="images/categories/accessoire.jpeg" alt="Accessoires">
    </div>
    <h2>Pantalons</h2>
   </div>

   <div class="posters">
    <div class="posters_image">
     <img src="images/categories/poster.jpeg" alt="Posters">
    </div>
    <h2>Sweats</h2>
   </div>

   <div class="accessoires">
    <div class="accessoires_image">
     <img src="images/categories/accessoire.jpeg" alt="Accessoires">
    </div>
    <h2>Sweats</h2>
   </div>
 </div>


 <div class="populaires_container">
   <h2>Les plus populaires</h2>

   <div class="populaires">
     <div style="background-color: #09b3fc;">
       <img src="https://cdn.shopify.com/s/files/1/1297/1509/files/model-min_550x.png?v=1640328855">

       <div class="populaires_details">
         <h3>NOM PRODUIT</h3>
         <button>ACHETER</button>
       </div>
     </div>

     <div style="background-color: #a40c12">
       <img src="https://cdn.shopify.com/s/files/1/1297/1509/files/model-min_550x.png?v=1640328855">

       <div class="populaires_details">
        <h3>NOM PRODUIT</h3>
        <button>ACHETER</button>
      </div>
     </div>

     <div style="background-color: #622d85">
       <img src="https://cdn.shopify.com/s/files/1/1297/1509/files/model-min_550x.png?v=1640328855">

       <div class="populaires_details">
        <h3>NOM PRODUIT</h3>
        <button>ACHETER</button>
      </div>
     </div>
   </div>
 </div>


 <div class="collection_container">
   <h2>Notre dernière collection</h2>

   <div class="collection">
    
    <!-- <script>
      var element = document.getElementById('collection');
      console.log(element.clientWidth)
      element.clientHeight = element.clientWidth;
      // element.style.height = element.clientWidth;
      console.log(element.style.height)
      console.log(element.clientHeight)
    </script> -->

    <a href="<?= url_to('Product::display', '1') ?>"><div class="one" style="grid-column: 1; grid-row: 1 / 3"></div></a>
    <div class="two" style="grid-column: 2 / 4; grid-row: 1 / 3"></div>
    <div class="three" style="grid-column: 4 / 5; grid-row: 1"></div>
    <div class="four" style="grid-column: 5 / 6; grid-row: 1 / 3"></div>
    <div class="five" style="grid-column: 1 / 3; grid-row: 3"></div>
    <div class="six" style="grid-column: 3 / 4; grid-row: 3"></div>
    <div class="seven" style="grid-column: 4 / 5; grid-row: 2 / 4"></div>
    <div class="eight" style="grid-column: 5 / 6; grid-row: 3"></div>
   </div>
 </div>


 <div class="footer">
  <div class="cgu">
    <ul>
      <li>
        <a href="">Conditions générales d'utilisation</a>
      </li>

      <li>
        <a href="">Conditions générales d'utilisation</a>
      </li>

      <li>
        <a href="">Conditions générales d'utilisation</a>
      </li>

      <li>
        <a href="">Conditions générales d'utilisation</a>
      </li>
    </ul>
  </div>

  <div class="contact">
    <h3>DONNEZ-NOUS VOTRE AVIS</h3>

    <div class="contact_container">
      <input class="contact_field" type="text">
      <button class="contact_button">ENVOYER</button>
    </div>
  </div>
  
  <div class="reseaux">
    <ul>
      <li>
        <div class="reseau_container">
          <h4 class="underline_animation">@jfkdqljfqjlj</h4>

          <a href="">
            <img src="images/reseaux/instagram.png" alt="Instagram">
          </a>
        </div>
      </li>

      <li>
        <div class="reseau_container">
          <h4 class="underline_animation">@jfkdqljfqjlj</h4>

          <a href="">
            <img src="images/reseaux/instagram.png" alt="Instagram">
          </a>
        </div>
      </li>

      <li>
        <div class="reseau_container">
          <a class="reseau_container" href="">
            <h4 class="underline_animation">@jfkdqljfqjlj</h4>
          </a>

          <img src="images/reseaux/instagram.png" alt="Instagram">
        </div>
      </li>
    </ul>
  </div>
 </div>

</body>
</html>