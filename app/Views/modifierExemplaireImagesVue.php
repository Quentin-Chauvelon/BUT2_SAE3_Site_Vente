<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/modifierExemplaireImages.css"?>>
    <title>Hot genre</title>
</head>

<?php
    $exemplaireImages = [];

    foreach(new DirectoryIterator(dirname("images/produits" . DIRECTORY_SEPARATOR . $idProduit . DIRECTORY_SEPARATOR . "couleurs/.")) as $file)
    {
        if(!$file->isDot()) {
            $key = str_replace(array(".jpg", ".jpeg", ".png"), "", $file->getFileName());
            $exemplaireImages[$key] = site_url() . $file->getPath() . DIRECTORY_SEPARATOR . $file->getFileName();
        }
    }
    
    asort($exemplaireImages);

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

        <h1>Modifier les images des couleurs des exemplaires :</h1>

        <h3>Cliquez sur l'image que vous souhaitez modifier puis choisissez celle par laquelle vous voulez la remplacer.</h3>
        
        <div class="images_container">
            
            <?php foreach($exemplaireImages as $key=>$imageSrc) : ?>
                <form action=<?= url_to('AdminController::modifierImageExemplaire') ?> method="post" enctype='multipart/form-data'>
                    <input type="hidden" name="id_produit" id="id_produit" value="<?= $idProduit ?>" />
                    <input type="hidden" name="couleur" id="couleur" value="<?= $key ?>" />
                    <input class="add_image image" style="background-image: url(<?= $imageSrc . "?" . time() ?>);" type="button" onclick="document.getElementById('image').click();" />
                    <input type="file" style="display:none;" id="image" name="image" accept=".jpg, .png" onchange="this.form.submit()"/>
                    <h3><?= ucfirst($key) ?></h3>
                </form>
            <?php endforeach; ?>
            </form>
        </div>

        <a href="<?= url_to('Home::index') ?>">
            <div class="bouton">Valider</div>
        </a>
    </section>
</body>
</html>



