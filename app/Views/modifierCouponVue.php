<?php
require_once (APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetController.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/modifierCoupon.css"?>>
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
        
        <form id="modifier_coupon" class="modifier_coupon_form" action=<?= url_to('AdminController::modifierCoupon') ?> method="post">
            <h1>Modifier coupon :</h1>
        
            <div>
                <label for="id_coupon">ID coupon (non modifiable)</label>
                <input type="text" name="id_coupon" id="id_coupon" placeholder=" " value="<?= $coupon->id_coupon ?>" readonly/>
            </div>
        
            <div>
                <label for="nom">Nom *</label>
                <input type="text" name="nom" id="nom" placeholder=" " maxlength="50" value="<?= $coupon->nom ?>" required/>
            </div>

            <div>
                <label for="montant">Montant *</label>
                <input type="number" name="montant" id="montant" value="<?= $coupon->montant ?>" min="0" required/>
            </div>

            <div class="checkbox">
                <input type="checkbox" name="est_pourcentage" id="est_pourcentage" value="est_pourcentage" <?= ($coupon->est_pourcentage) ? "checked" : "" ?> placeholder=" "/>
                <label>Est un pourcentage</label>
            </div>

            <div class="checkbox">
                <input type="checkbox" name="est_valable" id="est_valable" value="est_valable" <?= ($coupon->est_valable) ? "checked" : "" ?> placeholder=" "/>
                <label>Est valable</label>
            </div>
        
            <div>
                <label for="date_limite">Date limite *</label>
                <input type="date" name="date_limite" id="date_limite" value="<?= $coupon->date_limite ?>" required/>
            </div>

            <div>
                <label for="utilisations_max">Utilisations max *</label>
                <input type="number" name="utilisations_max" id="utilisations_max" value="<?= $coupon->utilisations_max ?>" min="0" required/>
            </div>
        
            <button type="submit" class="bouton">Modifier coupon</button>
        </form>
    </section>
</body>
</html>