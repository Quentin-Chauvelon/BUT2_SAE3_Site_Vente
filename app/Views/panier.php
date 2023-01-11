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

<?php
    $etatCouponTexte = "";
    $etatCouponCouleur = "";
    $montant = "";
    $symbole = "";

    if ($etatCoupon == "invalide") {
        $etatCouponTexte = "Ce code promo est invalide.";
        $etatCouponCouleur = "red";
    }
    else if ($etatCoupon == "perime") {
        $etatCouponTexte = "Ce coupon est périmé.";
        $etatCouponCouleur = "red";
    }
    else if ($etatCoupon == "valide") {
        $etatCouponTexte = "Coupon appliqué avec succès !";
        $etatCouponCouleur = "green";

        // on calcule le nouveau montant de la commande en fonction du coupon appliqué
        if ($coupon->est_pourcentage) {
            $montant = $coupon->montant;
            $total = max(0, $total * (1 - ($montant / 100)));
            $symbole = "%";
        }
        else {
            $montant = $coupon->montant / 100;
            $total = max(0, $total - $coupon->montant);
            $symbole = "€";
        }
    }
?>

<body>

    <div class="panier_header">
        <div>
            <h1 class="total">Total : <?= substr($total / 100, 0, 5) ?>€ <span class='green <?= ($etatCoupon == "valide" ? "" : "hidden") ?>'>(-<?= $montant ?><?= $symbole ?>)</span></h1>
            <h2 class="nombre_produits">Nombre de produits : <?= $nombreProduits ?></h2>

            <div class="coupon_container">
                <h3>Coupon :</h3>

                <form action=<?= url_to(getRoute("appliquerCoupon")) ?> method="post">
                    <input id="code_promo" class="coupon_input" name="code_promo" type="text" placeholder=" ">
                    <button type="submit" class="appliquer_coupon">APPLIQUER</button>
                </form>
            </div>

            <h4 class="coupon_etat <?= $etatCouponCouleur ?>"><?= $etatCouponTexte ?></h4>
        </div>

        <div class="panier_header_boutons">
            <a href="<?= url_to(getRoute("validerPanier"), ($coupon != NULL) ? $coupon->id_coupon : "")?>">
                <div class="valider_panier">Valider et payer</div>
            </a>

            <a href="<?= url_to(getRoute("viderPanier")) ?>">
                <div class="vider_panier">Vider panier</div>
            </a>
        </div>
    </div>

    <div class="products_container">
        <?php foreach($panier as $exemplaire) : ?>
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

                        <a href="<?= url_to(getRoute("supprimerDuPanier"), $exemplaire["id_produit"], $exemplaire["couleur"], $exemplaire["taille"]) ?>">
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