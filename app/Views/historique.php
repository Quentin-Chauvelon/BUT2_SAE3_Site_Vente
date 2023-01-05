<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/historique.css"?>>
    <title>Hot genre</title>
</head>

<?php
    // on trie les commandes par ordre décroissant de l'id
    usort($commandes, fn($a, $b) => $a->id_commande < $b->id_commande);
?>

<body>

    <h1 class="pas_de_commandes <?= (count($commandes) == 0) ? "" : "hidden" ?>">Vous n'avez passé aucune commande pour l'instant.</h1>

    <div class="commandes_container">

        <?php foreach($commandes as $commande) : ?>
            <?php
                $idCommande = $commande->id_commande;
                $nombreArticles = 0;

                foreach ($exemplaires as $exemplaire) {
                    if ($exemplaire->id_commande == $idCommande) {
                        $nombreArticles += 1;
                    }
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
            ?>

            <div class="commande">
            <div class="left_container">
                <div class="commande_details_container">
                    <h1 class="numero_commande">Commande N°<?= $idCommande ?> du <?= $commande->date_commande ?></h1>

                    <h2 class="commande_details"> Total : <?= $commande->montant / 100 ?>€ <strong>·</strong> Nombre d'articles : <?= $nombreArticles ?></h2>
                </div>

                <p class="etat_commande <?= $etatCommandeCouleur ?>"><?= $etatCommande ?></p>
            </div>

            <a class="voir_detail" href="<?= url_to('ClientController::detailCommande', $commande->id_commande) ?>">
                <div class="voir_detail">Voir le détail</div>
            </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>