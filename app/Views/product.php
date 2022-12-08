<!DOCTYPE html>
<html>


<?php
	$productImages = [];

	foreach(new DirectoryIterator(dirname("images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "images/.")) as $file)
	{
		if(!$file->isDot()) {
			$productImages[] = site_url() . $file->getPath() . DIRECTORY_SEPARATOR . $file->getFileName();
		}
	}

	sort($productImages);


	// $productColors = [];

	// foreach(new DirectoryIterator(dirname("images/produits" . DIRECTORY_SEPARATOR . $product->getId_produit() . DIRECTORY_SEPARATOR . "couleurs/.")) as $file)
	// {
	// 	if(!$file->isDot()) {
	// 		$productColors[] = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFileName();
	// 	}
	// }

	// sort($productColors);
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

			<!-- COLOR HERE + UNCOMMENT LINES 18-27 -->

			<div id="sizes_container" class="sizes_container">
				<button class="selected">S</button>
				<button>M</button>
				<button>L</button>
				<button>XL</button>
				<button>XXL</button>
			</div>

			<div class="buttons_container">
				<button class="add_to_cart">AJOUTER AU PANIER</button>

				<a href="<?= url_to('Client::ajouterFavori', $product->getId_produit()) ?>">
					<button class="add_to_favorite">
						<img src="<?= site_url() . "images/icons/favoris.png"?>">
						<img class="hover_image" src="<?= site_url() . "images/icons/favoris_blanc.png"?>">
					</button>
				</a>
			</div>
		</div>

	</div>

	<?php include 'footer.php';?>
	
</body>
</html>