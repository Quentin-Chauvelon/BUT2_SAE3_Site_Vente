<div id="colours_container" class="colours_container">

    <?php foreach($productColors as $key=>$imageSrc) : ?>
        <div class="colour_container">
            <button class="colour_image_container <?php echo ($key == 0) ? 'selected' : '' ?>">
                <img class="arrow_image" src= <?= $imageSrc ?>>
            </button>

            <div class="colour_name_container">
                <div class="colour" style="background-color: red"></div>

                <h3 class="colour_name">Rouge</h3>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- product.php line 61 -->
<img id="product_image" src=<?= "images/produits/" . $product->getId_produit() . "/images/image_1.jpg" ?>>


<img src=<?= (file_exists(site_url() . "images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "images/image_1.png")) ? (site_url() . "images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "images/image_1.png") : (site_url() . "images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "images/image_1.jpg") ?>>





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

  <?php include 'header.php';?>

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
  <div class="sweats">
    <a href="<?= url_to('Product::trouverToutDeCategorie', 'sweat') ?>"> 
      <div class="sweats_image">
        <img src="<?= site_url() . "images/home/categories/sweat.png" ?>" alt="Sweats">
      </div>
    </a>
    
    <h2>Sweats</h2>
  </div>

   <div class="tshirts">
     <a href="<?= url_to('Product::trouverToutDeCategorie', 'tshirt') ?>"> 
    <div class="tshirts_image">
     <img src="<?= site_url() . "images/home/categories/tshirt.png" ?>" alt="T-shirts">
    </div>
    </a>
    <h2>T-shirts</h2>
   </div>

   <div class="pantalons">
     <a href="<?= url_to('Product::trouverToutDeCategorie', 'pantalon') ?>"> 
    <div class="pantalons_image">
     <img src="<?= site_url() . "images/home/categories/pantalon.png" ?>" alt="Pantalons">
    </div>
    </a>
    <h2>Pantalons</h2>
   </div>

   <div class="accessoires">
   <a href="<?= url_to('Product::trouverToutDeCategorie', 'accessoire') ?>"> 
    <div class="accessoires_image">
     <img src="<?= site_url() . "images/home/categories/accessoire.jpeg" ?>" alt="Accessoires">
    </div>
    </a>
    <h2>Accessoires</h2>
   </div>

   <div class="posters">
   <a href="<?= url_to('Product::trouverToutDeCategorie', 'poster') ?>"> 
    <div class="posters_image">
     <img src="<?= site_url() . "images/home/categories/poster.jpg" ?>" alt="Posters">
    </div>
    </a>
    <h2>Posters</h2>
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

    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 1; grid-row: 1 / 3"><div class="one"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 2 / 4; grid-row: 1 / 3"><div class="two"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 4 / 5; grid-row: 1"><div class="three"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 5 / 6; grid-row: 1 / 3"><div class="four"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 1 / 3; grid-row: 3"><div class="five"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 3 / 4; grid-row: 3"><div class="six"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 4 / 5; grid-row: 2 / 4"><div class="seven"></div></a>
    <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 5 / 6; grid-row: 3"><div class="eight"></div></a>
   </div>
 </div>

  <?php include 'footer.php';?>
  


</body>
</html>