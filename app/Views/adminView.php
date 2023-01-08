<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src=<?= site_url() . "js_script/adminView.js"?>></script>
    <link rel="stylesheet" href=<?= site_url() . "css/adminView.css"?>>
    <title>Hot genre</title>
</head>

<?php
    $exemplairesParCouleurTailleProduits = array();

    foreach ($exemplaires as $exemplaire) {
        $idProduit = $exemplaire->id_produit;
        $taille = $exemplaire->taille;
        $couleur = $exemplaire->couleur;
        $estDisponible = $exemplaire->est_disponible;
        $quantite = $exemplaire->quantite;

        if (!array_key_exists($exemplaire->id_produit, $exemplairesParCouleurTailleProduits)) {
            $exemplairesParCouleurTailleProduits[$idProduit] = array();
        }

        if (!array_key_exists($taille, $exemplairesParCouleurTailleProduits[$idProduit])) {
            $exemplairesParCouleurTailleProduits[$idProduit][$taille] = array();
        }

        if (array_key_exists($couleur, $exemplairesParCouleurTailleProduits[$idProduit][$taille])) {
            // if ($estDisponible) {
            //     $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur][0] += 1;
            // }

            // $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur][1] += 1;
            $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] += $quantite;
        }

        else {
            // if ($estDisponible) {
            //     $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = array(1, 1);
            // } else {
            //     $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = array(0, 1);
            // }

            $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = $quantite;
        }
    }
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

    <div class="nav_container">
        <a onclick="UtilisateursClicked()">
            <div class="nav_element">
                <h4>UTILISATEURS</h4>
            </div>
        </a>

        <a onclick="ProduitsClicked()">
            <div class="nav_element">
                <h4>PRODUITS</h4>
            </div>
        </a>
        
        <a onclick="ExemplairesClicked()">
            <div class="nav_element">
                <h4>EXEMPLAIRES</h4>
            </div>
        </a>

        <a onclick="CollectionsClicked()">
            <div class="nav_element">
                <h4>COLLECTIONS</h4>
            </div>
        </a>

        <a onclick="CommandesClicked()">
            <div class="nav_element">
                <h4>COMMANDES</h4>
            </div>
        </a>
    </div>

    <section class="compte_action">

        <div id="utilisateurs" class="utilisateurs <?= ($notHidden == "utilisateurs") ? "" : "hidden" ?>">
            <table>
                <tr>
                    <th>ID client</th>
                    <th>Prenom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Est admin</th>
                    <th style="width: 280px;">Mettre admin</th>
                    <th style="width: 280px;">Supprimer</th>
                </tr>

                <?php foreach($utilisateurs as $utilisateur) : ?>
                    <tr>
                        <td><?= $utilisateur->id_client ?></td>
                        <td><?= $utilisateur->prenom ?></td>
                        <td><?= $utilisateur->nom ?></td>
                        <td><?= $utilisateur->adresse_email ?></td>
                        <td><?= $utilisateur->est_admin ?></td>

                        <td>
                            <a href="<?= ($utilisateur->est_admin) ? url_to('AdminController::enleverAdmin', $utilisateur->id_client) : url_to('AdminController::mettreAdmin', $utilisateur->id_client) ?>">
                                <div class="button"><?= ($utilisateur->est_admin) ? "Enlever admin" : "Mettre admin" ?></div>
                            </a>
                        </td>

                        <td>
                            <a href="<?= url_to('AdminController::supprimerUtilisateur', $utilisateur->id_client) ?>">
                                <div class="button">Supprimer</div>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="produits" class="produits <?= ($notHidden == "produits") ? "" : "hidden" ?>">

            <a href="#creer_produit">
                <div class="button creer_bouton">Créer produit</div>
            </a>

            <table>
                <tr>
                    <th>ID produit</th>
                    <th>ID collection</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Reduction</th>
                    <th>Description</th>
                    <th>Categorie</th>
                    <th>Parution</th>
                    <th style="width: 280px;">Modifier produit</th>
                    <th style="width: 280px;">Supprimer produit</th>
                </tr>

                <?php foreach($produits as $produit) : ?>
                    <tr>
                        <td><?= $produit->id_produit ?></td>
                        <td><?= $produit->id_collection ?></td>
                        <td><?= $produit->nom ?></td>
                        <td><?= $produit->prix / 100 ?>€</td>
                        <td><?= $produit->reduction ?></td>
                        <td><?= $produit->description ?></td>
                        <td><?= $produit->categorie ?></td>
                        <td><?= $produit->parution ?></td>

                        <td>
                            <a href="<?= url_to('AdminController::modifierProduitVue', $produit->id_produit) ?>">
                                <div class="button">Modifier produit</div>
                            </a>
                        </td>

                        <td>
                            <a href="<?= url_to('AdminController::supprimerProduit', $produit->id_produit) ?>">
                                <div class="button">Supprimer produit</div>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>


            <form id="creer_produit" class="creer_produit_form" action=<?= url_to('AdminController::creerProduit') ?> method="post" enctype='multipart/form-data'>
                <h1>Créer un produit :</h1>

                <div>
                    <label for="id_collection">ID collection</label>
                    <select id="id_collection" name="id_collection">
                        <option value="<?= NULL ?>">Aucune</option>
                        
                        <?php foreach($collections as $collection) : ?>
                            <option value="<?= $collection->id_collection ?>"><?= $collection->nom ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="nom">Nom *</label>
                    <input type="text" name="nom" id="nom" placeholder=" " maxlength="100" required/>
                </div>

                <div>
                    <label for="prix">Prix (en centimes) *</label>
                    <input type="number" name="prix" id="prix" placeholder=" " required/>
                </div>

                <div>
                    <label for="reduction">Reduction (en centimes) *</label>
                    <input type="number" name="reduction" id="reduction" value="0" required/>
                </div>

                <div>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" maxlength="500"></textarea>
                </div>

                <div>
                    <label for="categorie">Categorie *</label>
                    <select id="categorie" name="categorie">
                        <option value="tshirt">T-shirt</option>
                        <option value="pantalon">Pantalon</option>
                        <option value="sweat">Sweat</option>
                        <option value="poster">Poster</option>
                        <option value="accessoire">Acessoire</option>
                    </select>
                </div>

                <div>
                    <label for="description">Images * (.jpg ou .png)</label>
                    <input type="file" name="images[]" id="images" multiple accept=".jpg, .png" required>
                </div>

                <button type="submit" class="button">Créer produit</button>
            </form>
        </div>
        
        <div id="exemplaires" class="exemplaires <?= ($notHidden == "exemplaires") ? "" : "hidden" ?>">
            <a href="#creer_exemplaire">
                <div class="button creer_bouton">Créer exemplaire</div>
            </a>

            <div class="exemplaires_produits_container">
                
                <?php foreach($exemplairesParCouleurTailleProduits as $idProduit => $exemplaireTailles) : ?>
                    <?php 
                        // $produit = $produits[$idProduit - 1];
                        $produit = NULL;
                        
                        foreach ($produits as $produitBoucle) {
                        	if ($produitBoucle->id_produit == $idProduit) {
                        		$produit = $produitBoucle;
                        	}
                        }
                    ?>

                    <div class="produit_exemplaire">
                        <h1><?= $produit->nom ?> (<?= ($produit->prix / 100) ?>€ · <?= ucfirst($produit->categorie) ?>) :</h1>

                        <a href="<?= url_to('AdminController::modifierExemplaireImagesVue', $idProduit) ?>">
                            <div class="button exemplaire_images">Modifier images</div>
                        </a>
                    </div>

                    <div class="exemplaires_tailles_container">

                        <?php
                            // $quantiteTotalDisponible = 0;
                            // $quantiteTotalTotal = 0;
                            $quantiteTotal = 0;

                            foreach($exemplaireTailles as $exemplaireCouleurs) {
                                foreach($exemplaireCouleurs as $quantite) {
                                    // $quantiteTotalDisponible += $quantites[0];
                                    // $quantiteTotalTotal += $quantites[1];
                                    $quantiteTotal += $quantite;
                                }
                            }
                        ?>

                        <h3>Total : <span class="green"><?= $quantiteTotal ?> disponibles</span></h3>

                        <?php foreach($exemplaireTailles as $taille => $exemplaireCouleurs) : ?>

                            <?php
                                // $quantiteDisponible = 0;
                                $quantiteTotal = 0;

                                foreach($exemplaireCouleurs as $quantite) {
                                    // $quantiteDisponible += $quantites[0];
                                    $quantiteTotal += $quantite;
                                }
                            ?>

                            <h3><?= $taille ?> : <span class="green"><?= $quantiteTotal ?> disponibles</span></h3>

                            <div class="exemplaires_couleurs_container">

                                <?php foreach($exemplaireCouleurs as $couleur => $quantite) : ?>
                                    <div class="exemplaire_couleurs">
                                        <h5><?= ucfirst($couleur) ?> : <span class="green"><?= $quantite ?> disponibles</span></h5>

                                        <a href="<?= url_to('AdminController::supprimer1Exemplaire', $idProduit, $taille, $couleur) ?>">
                                            <div class="button small">Supprimer 1</div>
                                        </a>

                                        <a href="<?= url_to('AdminController::supprimerTousLesExemplaires', $idProduit, $taille, $couleur) ?>">
                                            <div class="button small">Supprimer tout</div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <form id="creer_exemplaire" class="creer_exemplaire_form" action=<?= url_to('AdminController::creerExemplaire') ?> method="post" enctype='multipart/form-data'>
                <h1>Créer un exemplaire :</h1>

                <div>
                    <label for="id_produit">Produit *</label>

                    <select id="id_produit" name="id_produit">
                        <?php foreach($produits as $produit) : ?>
                            
                            <option value="<?= $produit->id_produit ?>"><?= $produit->nom ?> (<?= ($produit->prix / 100) ?>€ · <?= ucfirst($produit->categorie) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="couleur">Couleur *</label>
                    <input type="text" name="couleur" id="couleur" placeholder=" " maxlength="20" required/>
                </div>

                <div>
                    <label for="taille">Taille *</label>
                    
                    <select id="taille" name="taille">
                        <optgroup label="Tailles vêtements">
                            <?php foreach($taillesVetements as $taille) : ?>
                                <option value="<?= $taille ?>"><?= $taille ?></option>                            
                            <?php endforeach; ?>
                        </optgroup>

                        <optgroup label="Tailles posters">
                            <?php foreach($taillesPosters as $taille) : ?>
                                <option value="<?= $taille ?>"><?= $taille ?></option>                            
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
                
                <div>
                    <label for="quantite">Quantite *</label>
                    <input type="number" name="quantite" id="quantite" value="1" required/>
                </div>
                
        	    <div>
                    <label for="description">Image (.jpg ou .png)</label>
                    <input type="file" name="image" id="image" accept=".jpg, .png">
                </div>

                <button type="submit" class="button">Créer exemplaire</button>
            </form>

        </div>


        <div id="collections" class="collections <?= ($notHidden == "collections") ? "" : "hidden" ?>">
            
            <a href="#creer_collection">
                <div class="button creer_bouton">Créer collection</div>
            </a>

            <table>
                <tr>
                    <th>ID collection</th>
                    <th>Nom</th>
                    <th>Parution</th>
                    <th>Date limite</th>
                    <th style="width: 280px;">Modifier collection</th>
                    <th style="width: 280px;">Supprimer collection</th>
                </tr>

                <?php foreach($collections as $collection) : ?>
                    <tr>
                        <td><?= $collection->id_collection ?></td>
                        <td><?= $collection->nom ?></td>
                        <td><?= $collection->parution ?></td>
                        <td><?= $collection->date_limite ?></td>

                        <td>
                            <a href="<?= url_to('AdminController::modifierCollection', $collection->id_collection) ?>">
                                <div class="button">Modifier collection</div>
                            </a>
                        </td>

                        <td>
                            <a href="<?= url_to('AdminController::supprimerCollection', $collection->id_collection) ?>">
                                <div class="button">Supprimer collection</div>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>


            <form id="creer_collection" class="creer_collection_form" action=<?= url_to('AdminController::creerCollection') ?> method="post">
                <h1>Créer une collection :</h1>

                <div>
                    <label for="nom">Nom *</label>
                    <input type="text" name="nom" id="nom" placeholder=" " maxlength="50" required/>
                </div>

                <div>
                    <label for="date_limite">Date limite *</label>
                    <input type="date" name="date_limite" id="date_limite" required/>
                </div>

                <button type="submit" class="button">Créer collection</button>
            </form>
        </div>


        <div id="commandes" class="commandes <?= ($notHidden == "commandes") ? "" : "hidden" ?>">

            <?php foreach($utilisateurs as $utilisateur) : ?>
                <h1><?= $utilisateur->prenom . " " . $utilisateur->nom ?></h1>

                <table>
                    <tr>
                        <th>ID commande</th>
                        <th>ID adresse</th>
                        <th>Date</th>
                        <th>Date livraison estimée</th>
                        <th>Date livraison</th>
                        <th>ID coupon</th>
                        <th>Est validee</th>
                        <th>montant</th>
                    </tr>

                    <?php foreach($commandes as $commande) : ?>

                        <?php if ($commande->id_client == $utilisateur->id_client) : ?>
                            <tr>
                                <td><?= $commande->id_commande ?></td>
                                <td><?= $commande->id_adresse ?></td>
                                <td><?= $commande->date_commande ?></td>
                                <td><?= $commande->date_livraison_estimee ?></td>
                                <td><?= $commande->date_livraison ?></td>
                                <td><?= $commande->id_coupon ?></td>
                                <td><?= $commande->est_validee ?></td>
                                <td><?= $commande->montant / 100 ?>€</td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>

    </section>
</body>
</html>
