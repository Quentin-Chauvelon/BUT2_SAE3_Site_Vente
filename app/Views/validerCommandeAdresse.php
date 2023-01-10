<?php
require_once (APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetController.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/validerCommandeAdresse.css"?>>
    <title>Hot genre</title>
</head>

<body>

    <div class="panier_header">
        <div>
            <h1 class="total">Total : <?= substr($montant / 100, 0, 5) ?>€</h1>
            <h2 class="nombre_produits">Nombre de produits : <?= $nombreArticles ?></h2>
        </div>
        
        <a href="<?= url_to(getRoute("annulerCommande"), $idCommande) ?>">
            <div class="annuler_commande">Annuler ma commande</div>
        </a>
    </div>

    <div class="adresse_container">

        <form action=<?= url_to("ClientController::adresseCommande") ?> method="post">
            <div>
                <label for="rue">Rue *</label>
                <input type="text" name="rue" id="rue" placeholder=" " maxlength="100" required/>
            </div>

            <div>
                <label for="codePostal">Code postal *</label>
                <input type="text" name="codePostal" id="codePostal" placeholder=" " pattern="[0-9]{5}" required/>
            </div>

            <div>
                <label for="ville">Ville *</label>
                <input type="text" name="ville" id="ville" placeholder=" " maxlength="100" required/>
            </div>

            <input name="idCommande" type="hidden" value="<?= $idCommande ?>" readonly>

            <button type="submit">UTILISER CETTE ADRESSE</button>
        </form>

        <div class="adresses_precedentes_container">
            <h1>Adresses précédemments utilisées :</h1>

            <div class="adresses_precedentes">
                <?php foreach($adressesPrecendentes as $adresse) : ?>
    
                    <form action=<?= url_to("ClientController::adresseCommande") ?> method="post">
                        <div class="adresse_precedente">
                            <div>
                                <h3>Rue :</h3>
                                <input type="text" name="rue" id="rue" value="<?= $adresse->rue ?>" maxlength="100" readonly required/>
                            </div>

                            <div>
                                <h3>Code postal :</h3>
                                <input type="text" name="codePostal" id="codePostal" value="<?= $adresse->code_postal ?>" pattern="[0-9]{5}" readonly required/>
                            </div>

                            <div>
                                <h3>Ville :</h3>
                                <input type="text" name="ville" id="ville" value="<?= $adresse->ville ?>" maxlength="100" readonly required/>
                            </div>

                            <input name="idCommande" type="hidden" value="<?= $idCommande ?>" readonly>

                            <button type="submit" id="adresse_precedente_button" class="adresse_precedente_button">UTILISER</button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>