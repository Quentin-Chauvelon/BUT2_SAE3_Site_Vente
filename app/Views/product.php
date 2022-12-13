<!DOCTYPE html>
<html>


<script>
	function ProduitAjoutePanier() {
		setTimeout(() => {
			AfficherProduitAjoutePanier();
		}, 1000)
	}
</script>


<?php
	$tailleParCouleurs = [];
	$couleurs = [];
	$tailles = [];

	// on compte le nombre de tailles par couleurs
	foreach ($exemplaires as $exemplaire) {
		$couleur = $exemplaire->getCouleur();
		$taille = $exemplaire->getTaille();

		if (!array_key_exists($couleur, $tailleParCouleurs)) {
			$tailleParCouleurs[$couleur] = array();
			$couleurs[] = $couleur;
		}

		if (array_key_exists($taille, $tailleParCouleurs[$couleur])) {
			$tailleParCouleurs[$couleur][$taille] += 1;
		} else {
			$tailleParCouleurs[$couleur][$taille] = 1;
		}

		if (!in_array($taille, $tailles)) {
			$tailles[] = $taille;
		}
	}


	$productImages = [];

	foreach(new DirectoryIterator(dirname("images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "images/.")) as $file)
	{
		if(!$file->isDot()) {
			$productImages[] = site_url() . $file->getPath() . DIRECTORY_SEPARATOR . $file->getFileName();
		}
	}

	sort($productImages);


	$imageURLParCouleurs = [];

	foreach ($couleurs as $couleur) {
		$imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "couleurs/" . $couleur . ".png";
		
		$headers = @get_headers($imageURL);
		
		// On vérifie si l'url existe
		if(!$headers  || strpos($headers[0], '404')) {
			$imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "couleurs/" . $couleur . ".jpg";
		}

		$imageURLParCouleurs[$couleur] = $imageURL;
	}

	// $productColors = [];

	// foreach(new DirectoryIterator(dirname("images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "couleurs/.")) as $file)
	// {
	// 	if(!$file->isDot()) {
	// 		$productColors[] = site_url() . $file->getPath() . DIRECTORY_SEPARATOR . $file->getFileName();
	// 	}
	// }

	// sort($productColors);


	if ($ajouteAuPanier) {
		echo "<script>ProduitAjoutePanier();</script>";
	}
?>


<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src=<?= site_url() . "js_script/product.js"?>></script>
	<link rel="stylesheet" href=<?= site_url() . "css/product.css"?>>
	<title>Hot genre</title>
</head>

<body>

	<?php include 'header.php';?>

	<div class="product_container">

		<div id="product_images_container" class="product_images_container">
			
			<div class="arrow_background up_arrow">
			
				<img class="arrow_image" src="<?= site_url() . "images/icons/account.png"?>">
			</div>

			<?php foreach($productImages as $key=>$imageSrc) : ?>
				<div class="<?php echo ($key == 0) ? 'selected' : '' ?>">
					<img src= <?= $imageSrc ?>>
				</div>
			<?php endforeach; ?>

			<div class="arrow_background down_arrow">
				<img class="arrow_image" src="<?= site_url() . "images/icons/account.png"?>">
			</div>
		</div>

		<div class="product_image">
			<img id="product_image" src=<?= $productImages[0] ?>>
		</div>

		<div class="product_details">
			<h3 class="product_name"><?= $product->getNom() ?></h2>

			<h2 class="product_price"><?= sprintf('%01.2f€', (float)$product->getPrix() / 100); ?></h2>

			<p class="product_description"><?= $product->getDescription() ?></p>

			<div id="colours_container" class="colours_container">

				<?php foreach($imageURLParCouleurs as $couleur=>$imageSrc) : ?>
					<div class="colour_container" data-couleur="<?= $couleur ?>">
						<div class="colour_image_container <?php echo ($couleur == $couleurs[0]) ? 'selected' : '' ?>">
							<img class="arrow_image" src= <?= $imageSrc ?>>
						</div>

						<div class="colour_name_container">
							<!-- <div class="colour" style="background-color: red"></div> -->

							<h3 class="colour_name"><?= ucfirst($couleur) ?></h3>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div id="sizes_container" class="sizes_container">
				<?php foreach($tailles as $key=>$taille) : ?>
					<div class="<?php echo ($key == 0) ? 'selected' : '' ?>" data-taille="<?= $taille ?>"><?= $taille ?></div>
				<?php endforeach; ?>
			</div>

			<form action=<?= url_to('Client::ajouterAuPanier') ?> method="post">
				<div class="quantity_container">
				<h3>Quantité :</h3>
					<!-- <button class="increase_quantity"></button> -->
					<input id="quantity_input" class="quantity_input" name="quantite" type="number" value="1" min="0" max="20">
					<!-- <button class="decrease_quantity"></button> -->
					<h3 class="quantity_max">Maximum : 20</h3>
				</div>
				
				<input type="hidden" name="idProduit" value="<?= $product->getId_produit() ?>" readonly>
				<input id="couleur_input" type="hidden" name="couleur" value="<?= $couleurs[0] ?>" readonly>
				<input id="taille_input" type="hidden" name="taille" value="<?= $tailles[0] ?>" readonly>
				
				<div class="buttons_container">
					<button type="submit" class="add_to_cart">AJOUTER AU PANIER</button>

					<a href="<?= url_to('Client::ajouterFavori', $product->getId_produit(), 1) ?>">
						<div class="add_to_favorite">					
							<img src="<?= ($produitFavori) ? site_url() . "images/icons/compte/favoris_plein.png" : site_url() . "images/icons/favoris.png" ?>">
							<img class="hover_image" src="<?= ($produitFavori) ? site_url() . "images/icons/compte/favoris_blanc_plein.png" : site_url() . "images/icons/favoris_blanc.png" ?>">
						</div>
					</a>
				</div>
			</form>
		</div>
	</div>

	<div id="article_ajoute" class="article_ajoute article_ajoute_hidden">
		<h3>Votre article a bien été ajouté au panier !</h3>
		
		<a href="<?= url_to('Client::ajouterAuPanier') ?>">
			<div class="valider_panier">Valider et payer</div>
		</a>

		<div id="timer_animation" class="timer_animation"></div>
	</div>

	<?php include 'footer.php';?>
	
</body>
</html>