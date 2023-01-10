<?php
require_once (APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetController.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/modifierCollection.css"?>>
    <title>Hot genre</title>
</head>

<body>
    <header>
        <div>
            <a href="<?= url_to(getRoute("index")) ?>">
                <img class="logo" src="<?= site_url() . "images/logos/logo hg noir.png"?>" alt="Logo">
            </a>
        </div>

        <h3 class="underline_animation">Admin</h3>
    </header>

    <section class="compte_action">
        
        <form id="modifier_collection" class="modifier_collection_form" action=<?= url_to('AdminController::modifierCollection') ?> method="post">
            <h1>Modifier une collection :</h1>
        
            <div>
                <label for="id_collection">ID collection (non modifiable)</label>
                <input type="number" name="id_collection" id="id_collection" placeholder=" " value="<?= $collection->id_collection ?>" readonly/>
            </div>
        
            <div>
                <label for="nom">Nom *</label>
                <input type="text" name="nom" id="nom" placeholder=" " maxlength="50" value="<?= $collection->nom ?>" required/>
            </div>
        
            <div>
                <label for="parution">Parution (non modifiable)</label>
                <input type="date" name="parution" id="parution" placeholder=" " value="<?= $collection->parution ?>" readonly/>
            </div>
        
            <div>
                <label for="date_limite">Date limite *</label>
                <input type="date" name="date_limite" id="date_limite" value="<?= $collection->date_limite ?>" required/>
            </div>
        
            <button type="submit" class="bouton">Modifier collection</button>
        </form>
    </section>
</body>
</html>