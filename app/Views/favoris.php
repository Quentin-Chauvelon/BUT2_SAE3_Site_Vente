<?php
require_once (APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetController.php');
require_once APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetExtensionImage.php';
?>

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

    <h1 class="pas_de_favoris <?= (count($favoris) == 0) ? "" : "hidden" ?>">Vous n'avez aucun favori pour le moment.</h1>

    <div class="products_container">

        <?php foreach($favoris as $favori) : ?>
            <div class="product">
                <a href="<?= url_to(getRoute("display"), $favori->id_produit) ?>">
                    <div class="image_container">
                        <?php 
                            $extension = getExtensionImage("images/produits/" . $favori->id_produit . "/images/image_1");
                        ?>

                        <img src=<?= site_url() . "images/produits" . DIRECTORY_SEPARATOR . $favori->id_produit . DIRECTORY_SEPARATOR . "images/image_1" . $extension ?>>
                    </div>
                </a>

                <div>
                    <div class="product_details_container">
                        <h3><?= $favori->nom?></h3>
                        <h2><?= sprintf('%01.2f€', (float)$favori->prix / 100); ?></h2>
                    </div>

                    <a href="<?= url_to(getRoute("ajouterFavori"), $favori->id_produit, 0) ?>">
                        <img class="logo" src="<?= site_url() . "images/icons/bin.png"?>" alt="Logo">
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>