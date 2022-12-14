<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href=<?= site_url() . "css/panier.css"?>>
	<title>Hot genre</title>
</head>


<?php
    $total = 0.0;
    $nombreProduits = 0;

    foreach ($panier as $exemplaire) {
        $quantite = $quantitesExemplaires[(string)$exemplaire["id_produit"] . $exemplaire["couleur"] . $exemplaire["taille"]];

        $total += (float)$produits[$exemplaire["id_produit"]]["prix"] * $quantite;
        $nombreProduits += $quantite;
    }
?>


<body>

    <div class="panier_header">
        <div>
            <h1 class="total">Total : <?= $total / 100 ?>€</h1>
            <h2 class="nombre_produits">Nombre de produits : <?= $nombreProduits ?></h2>
        </div>

        <a  href="<?= url_to('ClientController::afficherPanier') ?>">
            <div class="valider_panier">Valider et payer</div>
        </a>
    </div>

    <div class="products_container">
        <?php foreach($panier as $exemplaire) : ?>
            <div class="product">
                <a href="<?= url_to('Product::display', $exemplaire["id_produit"]) ?>">
                    <div class="image_container">
                        <?php 
                            $imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $exemplaire["id_produit"] . DIRECTORY_SEPARATOR . "couleurs" . DIRECTORY_SEPARATOR . $exemplaire["couleur"] . ".png";

                            $headers = @get_headers($imageURL);

                            // On vérifie si l'url existe
                            if(!$headers  || strpos($headers[0], '404')) {
                                $imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $exemplaire["id_produit"] . DIRECTORY_SEPARATOR . "couleurs" . DIRECTORY_SEPARATOR . $exemplaire["couleur"] . ".jpg";
                            }
                        ?>

                        <img src=<?= $imageURL ?>>
                    </div>
                </a>

                <div>
                    <div class="product_details_container">
                        <div class="product_details_left">
                            <h3><?= $produits[$exemplaire["id_produit"]]["nom"] ?></h3>
                            <h2><?= sprintf('%01.2f€', (float)$produits[$exemplaire["id_produit"]]["prix"] / 100); ?></h2>
                        </div>

                        <a href="<?= url_to('ClientController::supprimerDuPanier', $exemplaire["id_produit"], $exemplaire["couleur"], $exemplaire["taille"]) ?>">
                            <img class="logo" src="<?= site_url() . "images/icons/bin.png"?>" alt="Logo">
                        </a>

                        <div class="product_details_right">
                            <h3>Taille : <?= $exemplaire["taille"] ?></h3>
                            <h3>Quantité : <?= $quantitesExemplaires[(string)$exemplaire["id_produit"] . $exemplaire["couleur"] . $exemplaire["taille"]] ?></h3>
                        </div>
                    </div>

                    
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>