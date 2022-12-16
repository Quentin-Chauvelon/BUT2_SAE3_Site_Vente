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
            <h1 class="total">Total : <?= $montant / 100 ?>€</h1>
            <h2 class="nombre_produits">Nombre de produits : <?= $nombreArticles ?></h2>
        </div>

        <a  href="<?= url_to('ClientController::afficherPanier') ?>">
            <div class="annuler_commande">Annuler ma commande</div>
        </a>
    </div>

    <form action=<?= url_to('ClientController::adresseCommande') ?> method="post">
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
</body>
</html>