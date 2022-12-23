<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?= site_url() . "css/adminView.css"?>>
    <title>Hot genre</title>
</head>
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
                <!-- <img class="logo" src="<?= site_url() . "images/icons/compte/profil_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/profil_plein.png"?>" alt="Logo"> -->

                <h4>UTILISATEURS</h4>
            </div>
        </a>

        <a onclick="ProduitsClicked()">
            <div class="nav_element">
                <!-- <img class="logo" src="<?= site_url() . "images/icons/compte/historique_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/historique_plein.png"?>" alt="Logo"> -->

                <h4>PRODUITS</h4>
            </div>
        </a>
        
        <a onclick="ExemplairesClicked()">
            <div class="nav_element">
                <!-- <img class="logo" src="<?= site_url() . "images/icons/compte/deconnexion_blanc_plein.png"?>" alt="Logo">
                <img class="hover_logo" src="<?= site_url() . "images/icons/compte/deconnexion_plein.png"?>" alt="Logo"> -->

                <h4>EXEMPLAIRES</h4>
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
                    <th style="width: 280px;">Metter admin</th>
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
                            <a href="<?= url_to('AdminController::mettreAdmin', $utilisateur->id_client) ?>">
                                <div class="bouton">Mettre admin</div>
                            </a>
                        </td>

                        <td>
                            <a href="<?= url_to('AdminController::supprimerUtilisateur', $utilisateur->id_client) ?>">
                                <div class="bouton">Supprimer</div>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="produits" class="produits <?= ($notHidden == "produits") ? "" : "hidden" ?>">

            <a href="#creer_produit">
                <div class="bouton creer_produit_bouton">Créer produit</div>
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
                        <td><?= $produit->prix ?></td>
                        <td><?= $produit->reduction ?></td>
                        <td><?= $produit->description ?></td>
                        <td><?= $produit->categorie ?></td>
                        <td><?= $produit->parution ?></td>

                        <td>
                            <a href="<?= url_to('AdminController::modifierProduitVue', $produit->id_produit) ?>">
                                <div class="bouton">Modifier produit</div>
                            </a>
                        </td>

                        <td>
                            <a href="<?= url_to('AdminController::supprimerProduit', $produit->id_produit) ?>">
                                <div class="bouton">Supprimer produit</div>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>


            <form id="creer_produit" class="creer_produit_form" action=<?= url_to('AdminController::creerProduit') ?> method="post">
                <h1>Créer un produit :</h1>

                <div>
                    <label for="id_collection">ID collection</label>
                    <select id="id_collection" name="id_collection">
                        <option value="<?= NULL ?>">Aucune</option>
                        
                        <?php foreach($collections as $collection) : ?>
                            <option value="<?= $collection->id_collection ?>"><?= $collection->id_collection ?></option>
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

                <button type="submit" class="bouton">Créer produit</button>
            </form>
        </div>
        
        <div id="exemplaires" class="exemplaires <?= ($notHidden == "exemplaires") ? "" : "hidden" ?>">
            <a href="#creer_exemplaire">
                <div class="bouton creer_produit_bouton">Créer exemplaire</div>
            </a>

            <div class="exemplaires_produits_container">
                
                <?php foreach($exemplaires as $idProduit => $exemplaireTailles) : ?>
                    <?php 
                        $produit = $produits[$idProduit];
                    ?>

                    <h1><?= $produit->nom ?> (<?= $produit->prix ?> · <?= ucfirst($produit->categorie) ?>) :</h1>

                    <div class="exemplaires_tailles_container">

                        <?php
                            $quantiteTotalDisponible = 0;
                            $quantiteTotalTotal = 0;

                            foreach($exemplaireTailles as $exemplaireCouleurs) {
                                foreach($exemplaireCouleurs as $quantites) {
                                    $quantiteTotalDisponible += $quantites[0];
                                    $quantiteTotalTotal += $quantites[1];
                                }
                            }
                        ?>

                        <h3>Total : <span class="green"><?= $quantiteTotalDisponible ?> disponibles</span> / <span class="red"><?= $quantiteTotalTotal ?> total</span></h3>

                        <?php foreach($exemplaireTailles as $taille => $exemplaireCouleurs) : ?>

                            <?php
                                $quantiteDisponible = 0;
                                $quantiteTotal = 0;

                                foreach($exemplaireCouleurs as $quantites) {
                                    $quantiteDisponible += $quantites[0];
                                    $quantiteTotal += $quantites[1];
                                }
                            ?>

                            <h3><?= $taille ?> : <span class="green"><?= $quantiteDisponible ?> disponibles</span> / <span class="red"><?= $quantiteTotal ?> total</span></h3>

                            <div class="exemplaires_couleurs_container">

                                <?php foreach($exemplaireCouleurs as $couleur => $quantites) : ?>
                                    <h5><?= ucfirst($couleur) ?> : <span class="green"><?= $quantites[0] ?> disponibles</span> / <span class="red"><?= $quantites[1] ?> total</span></h5>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- TODO déplacer dans un fichier adminView.js -->
    <script>
        let utilisateurs = null;
        let produits = null;
        let exemplaires = null;

        window.addEventListener('DOMContentLoaded', (event) => {
            utilisateurs = document.getElementById('utilisateurs');
            produits = document.getElementById('produits');
            exemplaires = document.getElementById('exemplaires');
        });

        function UtilisateursClicked() {
            utilisateurs.classList.remove("hidden");
            produits.classList.add("hidden");
            exemplaires.classList.add("hidden");
        }

        function ProduitsClicked() {
            utilisateurs.classList.add("hidden");
            produits.classList.remove("hidden");
            exemplaires.classList.add("hidden");
        }

        function ExemplairesClicked() {
            utilisateurs.classList.add("hidden");
            produits.classList.add("hidden");
            exemplaires.classList.remove("hidden");
        }
    </script>
</body>
</html>