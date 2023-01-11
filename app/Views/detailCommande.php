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
	<link rel="stylesheet" href=<?= site_url() . "css/detailCommande.css"?>>
	<title>Hot genre</title>
</head>

<?php
    $nombreProduits = 0;

    foreach ($exemplaires as $exemplaire) {
        $quantite = $quantitesExemplaires[(string)$exemplaire["id_produit"] . $exemplaire["couleur"] . $exemplaire["taille"]];
        $nombreProduits += $quantite;
    }

    $etatCommande = "";
    $etatCommandeCouleur = "vert";

    // livré -> date livraison != null, en cours de livraison -> date livraison == null, non validée -> id_adrese == null
    // si la commande n'a pas été validée (pas payer ou pas d'adresse)
    if ($commande->id_adresse == NULL) {
        $etatCommande = "Cette commande n'a pas encore été validée.";
        $etatCommandeCouleur = "rouge";
    }
    else {

        // si la commande n'a pas encore de date de livraison fixée
        if ($commande->date_livraison == NULL) {
            $etatCommande = "Cette commande n'est pas encore prête, elle devrait arriver le " . $commande->date_livraison_estimee . ".";
            $etatCommandeCouleur = "orange";
        }
        else {

            // si la commande a une date de livraison fixée mais n'est pas encore arrivée
            if ($commande->date_livraison > date("Y-m-d")) {
                $etatCommande = "Cette commande est prête, elle devrait arriver le " . $commande->date_livraison . ".";
                $etatCommandeCouleur = "orange";
            }

            // si la commande est arrivée
            else {
                $etatCommande = "Vous avez reçu cette commande le " . $commande->date_livraison . ".";
                $etatCommandeCouleur = "vert";
            }
        }
    }

    $montantCoupon = "";
    $symboleCoupon = "";

    if ($coupon != NULL) {
        if ($coupon->est_pourcentage) {
            $montantCoupon = $coupon->montant;
            $symboleCoupon = "%";
        } else {
            $montantCoupon = $coupon->montant / 100;
            $symboleCoupon = "€";
        }
    }
?>

<body>

    <div class="panier_header">
        <div>
            <h1 class="total">Total : <?= $commande->montant / 100 ?>€ <span class="green <?= ($coupon == NULL) ? "hidden" : "" ?>">(-<?= $montantCoupon ?><?= $symboleCoupon ?>)</span></h1>
            <h2 class="nombre_produits">Nombre de produits : <?= $nombreProduits ?></h2>
        </div>

        <h1 class="date_commande">Commande N°<?= $commande->id_commande ?> du <?= $commande->date_commande ?></h1>

        <div>
            <a class="telecharger_facture" href="<?= url_to(getRoute("facture"), $commande->id_commande) ?>">
                <div>Télécharger ma facture</div>
            </a>
        </div>
    </div>

    <p class="etat_commande <?= $etatCommandeCouleur ?>"><?= $etatCommande ?></p>

    <?php if ($adresse != NULL) : ?>
        <p class="adresse"> Livré au : <?= $adresse->rue ?>, <?= $adresse->code_postal ?> <?= $adresse->ville ?></p>
    <?php endif; ?>

    <div class="products_container">
        <?php foreach($exemplaires as $exemplaire) : ?>
            <div class="product">
                <a href="<?= url_to(getRoute("display"), $exemplaire["id_produit"]) ?>">
                    <div class="image_container">
                        <?php 
                            $imageURL = "";
                            $extension = getExtensionImage("images/produits" . DIRECTORY_SEPARATOR . $exemplaire["id_produit"] . DIRECTORY_SEPARATOR . "couleurs/" . $exemplaire["couleur"]);

                            if ($extension != "") {
                                $imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $exemplaire["id_produit"] . DIRECTORY_SEPARATOR . "couleurs/" . $exemplaire["couleur"] . $extension;
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