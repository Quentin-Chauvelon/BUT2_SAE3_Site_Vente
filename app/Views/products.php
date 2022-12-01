<!DOCTYPE html>
<html>

<!-- - GetAllPantalons()
- GetAllSweats()
- GetAllTshirts()
- GetAllVetements()
- GetAllPosters()
- GetAllAccessoires() -->

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href=<?= site_url() . "css/products.css"?>>
	<title>Hot genre</title>
</head>

<body>
<div class="products_container">
    <?php foreach($products as $product) : ?>
        <div class="product">
            <a href="<?= url_to('Product::display', $product->getId_produit()) ?>">
                <div class="image_container">
                    <img src=<?= "images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "images/image_1.png"?>>
                </div>
            </a>

            <h3><?= $product->getNom()?></h3>
            <h2><?= sprintf('%01.2f€', (float)$product->getPrix() / 100); ?></h2>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>