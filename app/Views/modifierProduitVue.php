<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/modifierProduit.css"?>>
    <title>Hot genre</title>
</head>

<?php
    $productImages = [];

    foreach(new DirectoryIterator(dirname("images/produits" . DIRECTORY_SEPARATOR . $produit->id_produit . DIRECTORY_SEPARATOR . "images/.")) as $file)
    {
        if(!$file->isDot()) {
            $productImages[] = site_url() . $file->getPath() . DIRECTORY_SEPARATOR . $file->getFileName();
        }
    }

    sort($productImages);
?>

<body>
    <header>
        <div>
            <a href="<?= url_to('Home::index') ?>">
                <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png"?>" alt="Logo">
            </a>
        </div>

        <h3 class="underline_animation">Admin</h3>
    </header>

    <section class="compte_action">
        
        <form id="modifier_produit" class="modifier_produit_form" action=<?= url_to('AdminController::modifierProduit') ?> method="post">
            <h1>Modifier un produit :</h1>
        
            <div>
                <label for="id_produit">ID produit (non modifiable)</label>
                <input type="number" name="id_produit" id="id_produit" placeholder=" " value="<?= $produit->id_produit ?>" readonly/>
            </div>
        
            <div>
                <label for="id_collection">ID collection</label>
                <select id="id_collection" name="id_collection">
                    <option value="<?= NULL ?>">Aucune</option>
                    
                    <?php foreach($collections as $collection) : ?>
                        <option <?= ($produit->id_collection == $collection->id_collection) ? "selected" : "" ?> value="<?= $collection->id_collection ?>"><?= $collection->nom ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        
            <div>
                <label for="nom">Nom *</label>
                <input type="text" name="nom" id="nom" placeholder=" " maxlength="100" value="<?= $produit->nom ?>" required/>
            </div>
        
            <div>
                <label for="prix">Prix (en centimes) *</label>
                <input type="number" name="prix" id="prix" placeholder=" " value="<?= $produit->prix ?>" required/>
            </div>
        
            <div>
                <label for="reduction">Reduction (en centimes) *</label>
                <input type="number" name="reduction" id="reduction" value="0" value="<?= $produit->reduction ?>" required/>
            </div>
        
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" maxlength="500"><?= $produit->description ?></textarea>
            </div>
        
            <div>
                <label for="categorie">Categorie *</label>
                <select id="categorie" name="categorie">
                    <option <?= ($produit->categorie == "tshirt") ? "selected" : "" ?> value="tshirt">T-shirt</option>
                    <option <?= ($produit->categorie == "pantalon") ? "selected" : "" ?> value="pantalon">Pantalon</option>
                    <option <?= ($produit->categorie == "sweat") ? "selected" : "" ?> value="sweat">Sweat</option>
                    <option <?= ($produit->categorie == "poster") ? "selected" : "" ?> value="poster">Poster</option>
                    <option <?= ($produit->categorie == "accessoire") ? "selected" : "" ?> value="accessoire">Acessoire</option>
                </select>
            </div>
        
            <button type="submit" class="bouton">Modifier produit</button>
        </form>

        <div class="images_container">
            <?php foreach($productImages as $key=>$imageSrc) : ?>

                <div class="image_container">
                    <div class="image">
                        <img src= <?= $imageSrc ?>>

                    </div>

                    <img class="bin" src="<?= site_url() . "images/icons/bin.png"?>">
                </div>
			<?php endforeach; ?>

            <form action=<?= url_to('AdminController::ajouterImageProduit') ?> method="post" enctype='multipart/form-data'>
                <input type="hidden" name="id_produit" id="id_produit" value="<?= $produit->id_produit ?>" />
                <input class="add_image image" value="+" type="button" onclick="document.getElementById('image').click();" />
                <input type="file" style="display:none;" id="image" name="image" accept=".jpg, .png" onchange="this.form.submit()"/>
            </form>
        </div>
    </section>
</body>
</html>



