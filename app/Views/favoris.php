<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href=<?= site_url() . "css/favoris.css"?>>
	<title>Hot genre</title>
</head>

<body>

    <div class="products_container">
        <?php foreach($favoris as $favori) : ?>
            <div class="product">
                <a href="<?= url_to('Product::display', $favori->getId_produit()) ?>">
                    <div class="image_container">
                        <?php 
                            $imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $favori->getId_produit() . DIRECTORY_SEPARATOR . "images/image_1.png";

                            $headers = @get_headers($imageURL);

                            // On vérifie si l'url existe
                            if(!$headers  || strpos($headers[0], '404')) {
                                $imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $favori->getId_produit() . DIRECTORY_SEPARATOR . "images/image_1.jpg";
                            }
                        ?>

                        <img src=<?= $imageURL ?>>
                    </div>
                </a>

                <h3><?= $favori->getNom()?></h3>
                <h2><?= sprintf('%01.2f€', (float)$favori->getPrix() / 100); ?></h2>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>