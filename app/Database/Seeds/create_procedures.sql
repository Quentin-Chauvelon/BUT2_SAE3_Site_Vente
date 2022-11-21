/*
### Table Client
- GetAllClients()
- GetClientParID(ID)
- GetClientParEmail(email)
- GetAllAdmins()
- GetAllFideles()
- CreerClient(mail, nom, prenom, password) Pas admin par défaut
- ModifierClient(id, mail, nom, prenom, password, admin)
- SupprimerClient(id)

### Table Collection
- CreerCollection(nom) Parution aujourd'hui et pas de date limite par défaut
- ModifierCollection(id, nom, parution, date_limite)
- GetAllCollections()
- GetCollectionParId(id)
- GetCollectionParNom(nom)
- GetCollectionsActuelles()
- GetCollectionsEphemeresActuelles()
- SupprimerCollection(id)

### Table Coupon
- CreerCoupon(code, nom, montant, est_pourcentage, est_valable) Pas de date limite ni de nombre d'utilisations max par défaut.
- ModifierCoupon(code, nom, montant, est_pourcentage, est_valable, date_limite, utilisations_max)
- GetAllCoupons()
- GetAllCouponsValables()
- GetCouponParCode(code)
- NombreUtilisationsCoupon(code)
- GetAllCouponsNonExpires()
- GetAllCouponsUtilisables()
- SupprimerCoupon(code)

### Table Produit
- CreerProduit(nom, prix, description, categorie)
- GetAllProduits()
- GetAllProduitsReduction()
- GetProduitParId(id_produit)
- ModifierProduit(id_produit, nom, prix, description, categorie, parution, reduction, id_collection)
- SupprimerProduit(id_produit)
- GetAllPantalons()
- GetAllSweats()
- GetAllTshirts()
- GetAllVetements()
- GetAllPosters()
- GetAllAccessoires()
- GetAllProduitsDispo()

### Table Favori
- CreerFavori(id_client, id_produit)
- SupprimerFavori(id_client, id_produit)
- GetAllFavoris()
- GetFavorisClient(id_client)
- ProduitsPlusFavoris()

### Table Exemplaire
- CreerExemplaire(id_produit, couleur, taille)
- SupprimerExemplaire(id_exemplaire)
- ModifierExemplaire(id_exemplaire, id_produit, couleur, taille, est_disponible, date_obtention, id_commande)
- GetAllExemplaires()
- GetAllExemplairesDispo()
- GetExemplaireParId(id_exemplaire)
- GetExemplairesParProduit(id_produit)
- GetExemplairesDispoParProduit(id_produit)
- GetExemplairesParProduitCouleurTaille(id_produit, couleur, taille)
- GetExemplairesDispoParProduitCouleurTaille(id_produit, couleur, taille)

### Table Commande
- CreerCommande(id_client)
- ModifierCommande(id_commande, id_client, date_commande, date_livraison_estimee, date_livraison, id_coupon)
- SupprimerCommande(id_commande)
- GetAllCommandes()
- GetCommandeParId(id_commande)
- GetContenuCommande(id_commande)
*/

-- TODO Procédures calculer prix commande et colonne est_valide
CREATE OR REPLACE PROCEDURE GetAllClients()
BEGIN
    SELECT * FROM Client;
END;

CREATE OR REPLACE PROCEDURE GetClientParID(IN _id_client INT)
BEGIN
    SELECT * FROM Client WHERE id_client = _id_client;
END;

CREATE OR REPLACE PROCEDURE GetClientParEmail(IN _adresse_email VARCHAR(255))
BEGIN
    SELECT * FROM Client WHERE adresse_email = _adresse_email;
END;

CREATE OR REPLACE PROCEDURE GetAllAdmins()
BEGIN
    SELECT * FROM Admin;
END;

CREATE OR REPLACE PROCEDURE GetAllFideles()
BEGIN
    SELECT * FROM Fidele;
END;

CREATE OR REPLACE PROCEDURE CreerClient(
    IN _adresse_email VARCHAR(255), IN _nom VARCHAR(255), IN _prenom VARCHAR(64), IN _password VARCHAR(64))
BEGIN
    INSERT INTO Client(adresse_email, nom, prenom, password, est_admin)
    VALUES (_adresse_email, _nom, _prenom, _password, false);
END;

CREATE OR REPLACE PROCEDURE ModifierClient(
    IN _id_client INT, IN _adresse_email VARCHAR(255), IN _nom VARCHAR(255), IN _prenom VARCHAR(64),
    IN _password VARCHAR(64), IN _est_admin BOOLEAN)
BEGIN
    UPDATE Client
    SET adresse_email=_adresse_email,
        nom=_nom,
        prenom=_prenom,
        password=_password,
        est_admin=_est_admin
    WHERE id_client = _id_client;
END;

CREATE OR REPLACE PROCEDURE SupprimerClient(IN _id_client INT)
BEGIN
   DELETE FROM Client WHERE id_client=_id_client;
END;

CREATE OR REPLACE PROCEDURE CreerCollection(IN _nom VARCHAR(50))
BEGIN
    INSERT INTO Collection(nom, parution, date_limite) VALUES (_nom, CURDATE(), NULL);
END;

CREATE OR REPLACE PROCEDURE ModifierCollection(IN _id_collection INT, _nom VARCHAR(50), _parution DATE,
                                               _date_limite DATE)
BEGIN
    UPDATE Collection SET nom=_nom, date_limite=_date_limite, parution=_parution WHERE id_collection = _id_collection;
END;

CREATE OR REPLACE PROCEDURE GetAllCollections()
BEGIN
    SELECT * FROM Collection;
END;

CREATE OR REPLACE PROCEDURE GetCollectionParId(IN _id_collection INT)
BEGIN
    SELECT * FROM Collection WHERE id_collection = _id_collection;
END;

CREATE OR REPLACE PROCEDURE GetCollectionParNom(IN _nom VARCHAR(50))
BEGIN
    SELECT * FROM Collection WHERE nom = _nom;
END;

CREATE OR REPLACE PROCEDURE GetCollectionsActuelles()
BEGIN
    SELECT * FROM Collection WHERE date_limite IS NULL OR date_limite >= CURDATE();
END;

CREATE OR REPLACE PROCEDURE GetCollectionsEphemeresActuelles()
BEGIN
    SELECT * FROM Collection WHERE date_limite IS NOT NULL AND date_limite >= CURDATE();
END;

CREATE OR REPLACE PROCEDURE SupprimerCollection(IN _id_collection INT)
BEGIN
    DELETE FROM Collection WHERE id_collection=_id_collection;
END;

CREATE OR REPLACE PROCEDURE CreerCoupon(
    IN _code VARCHAR(20),
    IN _nom VARCHAR(50),
    IN _montant INT,
    IN _est_pourcentage BOOLEAN,
    IN _est_valable BOOLEAN)
BEGIN
    INSERT INTO Coupon(code, nom, montant, est_pourcentage, est_valable, date_limite, utilisations_max) VALUES
    (_code, _nom, _montant, _est_pourcentage, _est_valable, NULL, NULL);
END;

CREATE OR REPLACE PROCEDURE ModifierCoupon(
    IN _code VARCHAR(20),
    IN _nom VARCHAR(50),
    IN _montant INT,
    IN _est_pourcentage BOOLEAN,
    IN _est_valable BOOLEAN,
    IN _date_limite DATE,
    IN _utilisations_max INT)
BEGIN
   UPDATE Coupon SET nom=_nom, montant=_montant, est_pourcentage=_est_pourcentage,
    est_valable=_est_valable, date_limite=_date_limite, utilisations_max=_utilisations_max WHERE code=_code;
END;

CREATE OR REPLACE PROCEDURE GetAllCoupons()
BEGIN
   SELECT * FROM Coupon;
END;

CREATE OR REPLACE PROCEDURE GetAllCouponsValables()
BEGIN
   SELECT * FROM CouponValable;
END;

CREATE OR REPLACE PROCEDURE GetCouponParCode(IN _code VARCHAR(20))
BEGIN
    SELECT * FROM Coupon WHERE code=_code;
END;

CREATE OR REPLACE PROCEDURE NombreUtilisationsCoupon(IN _code VARCHAR(20))
BEGIN
    SELECT COUNT(*) AS nombre_utilisations FROM Commande where id_coupon=_code;
END;

CREATE OR REPLACE PROCEDURE GetAllCouponsNonExpires()
BEGIN
   SELECT * FROM Coupon WHERE date_limite IS NULL OR date_limite >= CURDATE();
END;

CREATE OR REPLACE PROCEDURE GetAllCouponsUtilisables()
BEGIN
    SELECT * FROM Coupon WHERE est_valable AND (date_limite IS NULL OR date_limite >= CURDATE())
    AND (utilisations_max IS NULL OR utilisations_max > (SELECT COUNT(*) FROM Commande where id_coupon=code));
END;

CREATE OR REPLACE PROCEDURE SupprimerCoupon(IN _code VARCHAR(20))
BEGIN
   DELETE FROM Coupon WHERE code=_code;
END;


CREATE OR REPLACE PROCEDURE CreerProduit(IN _nom VARCHAR(100), IN _prix INT, IN _description VARCHAR(500),
IN _categorie VARCHAR(50))
BEGIN
    INSERT INTO Produit(nom, prix, reduction, description, categorie, parution, id_collection)
    VALUES (_nom, _prix, 0, _description, _categorie, CURDATE(), NULL);
END;

CREATE OR REPLACE PROCEDURE GetAllProduits()
BEGIN
    SELECT * FROM Produit;
END;

CREATE OR REPLACE PROCEDURE GetAllProduitsReduction()
BEGIN
    SELECT * FROM ProduitReduction;
END;

CREATE OR REPLACE PROCEDURE GetProduitParId(IN _id_produit INT)
BEGIN
    SELECT * FROM Produit WHERE id_produit=_id_produit;
END;

CREATE OR REPLACE PROCEDURE ModifierProduit(IN _id_produit INT, IN _nom VARCHAR(100), _prix INT, IN _description VARCHAR(500),
IN _categorie VARCHAR(50), IN _parution DATE, IN _reduction INT, IN _id_collection INT)
BEGIN
   UPDATE Produit SET nom=_nom, prix=_prix, description=_description,
                      categorie=_categorie, parution=_parution, reduction=_reduction, id_collection=_id_collection
                  WHERE id_produit=_id_produit;
END;

CREATE OR REPLACE PROCEDURE SupprimerProduit(IN _id_produit INT)
BEGIN
    DELETE FROM Produit WHERE id_produit=_id_produit;
END;

CREATE OR REPLACE PROCEDURE GetAllPantalons()
BEGIN
    SELECT * FROM Pantalon;
END;

CREATE OR REPLACE PROCEDURE GetAllSweats()
BEGIN
   SELECT * FROM Sweat;
END;

CREATE OR REPLACE PROCEDURE GetAllTshirts()
BEGIN
    SELECT * FROM Tshirt;
END;

CREATE OR REPLACE PROCEDURE GetAllVetements()
BEGIN
    SELECT * FROM Vetement;
END;

CREATE OR REPLACE PROCEDURE GetAllPosters()
BEGIN
    SELECT * FROM Poster;
END;

CREATE OR REPLACE PROCEDURE GetAllAccessoires()
BEGIN
    SELECT * FROM Accessoire;
END;

CREATE OR REPLACE PROCEDURE GetAllProduitsDispo()
BEGIN
   SELECT * FROM Produit WHERE id_produit IN (SELECT DISTINCT id_produit FROM ExemplaireDispo);
END;

CREATE OR REPLACE PROCEDURE CreerFavori(IN _id_client INT, IN _id_produit INT)
BEGIN
    INSERT INTO Favori(id_client, id_produit) VALUES (_id_client, _id_produit);
END;

CREATE OR REPLACE PROCEDURE SupprimerFavori(IN _id_client INT, IN _id_produit INT)
BEGIN
    DELETE FROM Favori WHERE id_client=_id_client AND id_produit=_id_produit;
END;

CREATE OR REPLACE PROCEDURE GetAllFavoris()
BEGIN
    SELECT * FROM Favori;
END;

CREATE OR REPLACE PROCEDURE GetFavorisClient(IN _id_client INT)
BEGIN
   SELECT * FROM Favori WHERE id_client=_id_client;
END;

CREATE OR REPLACE PROCEDURE ProduitsPlusFavoris()
BEGIN
   SELECT id_produit, COUNT(*) AS nombre FROM Favori GROUP BY id_produit ORDER BY nombre DESC;
END;

CREATE OR REPLACE PROCEDURE CreerExemplaire(IN _id_produit INT, IN _couleur VARCHAR(20), IN _taille VARCHAR(50))
BEGIN
    INSERT INTO Exemplaire(id_produit, couleur, taille, est_disponible, date_obtention, id_commande)
    VALUES (_id_produit, _couleur, _taille, true, CURDATE(), NULL) ;
END;

CREATE OR REPLACE PROCEDURE SupprimerExemplaire(IN _id_exemplaire INT)
BEGIN
   DELETE FROM Exemplaire WHERE id_exemplaire=_id_exemplaire;
END;

CREATE OR REPLACE PROCEDURE ModifierExemplaire(IN _id_exemplaire INT, IN _id_produit INT, IN _couleur VARCHAR(20),
IN _taille VARCHAR(50), IN _est_disponible BOOLEAN, IN _date_obtention DATE, IN _id_commande INT)
BEGIN
    UPDATE Exemplaire SET id_produit=_id_produit, couleur=_couleur, taille=_taille, est_disponible=_est_disponible,
                          date_obtention=_date_obtention, id_commande=_id_commande
    WHERE id_exemplaire=_id_exemplaire;
END;

CREATE OR REPLACE PROCEDURE GetAllExemplaires()
BEGIN
   SELECT * FROM Exemplaire;
END;

CREATE OR REPLACE PROCEDURE GetAllExemplairesDispo()
BEGIN
   SELECT * FROM ExemplaireDispo;
END;

CREATE OR REPLACE PROCEDURE GetExemplaireParId(IN _id_exemplaire INT)
BEGIN
    SELECT * FROM Exemplaire WHERE id_exemplaire=_id_exemplaire;
END;

CREATE OR REPLACE PROCEDURE GetExemplairesParProduit(IN _id_produit INT)
BEGIN
    SELECT * FROM Exemplaire WHERE id_produit=_id_produit;
END;

CREATE OR REPLACE PROCEDURE GetExemplairesDispoParProduit(IN _id_produit INT)
BEGIN
   SELECT * FROM ExemplaireDispo WHERE id_produit=_id_produit;
END;

CREATE OR REPLACE PROCEDURE GetExemplairesParProduitCouleurTaille(IN _id_produit INT, IN _couleur VARCHAR(20), IN _taille VARCHAR(50))
BEGIN
    SELECT * FROM Exemplaire WHERE id_produit=_id_produit AND couleur=_couleur AND taille=_taille;
END;

CREATE OR REPLACE PROCEDURE GetExemplairesDispoParProduitCouleurTaille(IN _id_produit INT, IN _couleur VARCHAR(20), IN _taille VARCHAR(50))
BEGIN
    SELECT * FROM ExemplaireDispo WHERE id_produit=_id_produit AND couleur=_couleur AND taille=_taille;
END;

CREATE OR REPLACE PROCEDURE CreerCommande(IN _id_client INT)
BEGIN
   INSERT INTO Commande(id_client, date_commande, date_livraison_estimee, date_livraison, id_coupon)
    VALUES(_id_client, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), NULL, NULL);
END;

CREATE OR REPLACE PROCEDURE ModifierCommande(IN _id_commande INT, IN _id_client INT, IN _date_commande DATE,
IN _date_livraison_estimee DATE, IN _date_livraison DATE, IN _id_coupon VARCHAR(20))
BEGIN
    UPDATE Commande SET id_coupon=_id_client, date_commande=_date_commande, date_livraison_estimee=_date_livraison_estimee,
                        date_livraison=_date_livraison, id_coupon=_id_coupon
    WHERE id_commande=_id_commande;
END;

CREATE OR REPLACE PROCEDURE SupprimerCommande(IN _id_commande INT)
BEGIN
    DELETE FROM Commande WHERE id_commande=_id_commande;
END;

CREATE OR REPLACE PROCEDURE GetAllCommandes()
BEGIN
    SELECT * FROM Commande;
END;

CREATE OR REPLACE PROCEDURE GetCommandeParId(IN _id_commande INT)
BEGIN
    SELECT * FROM Commande WHERE id_commande=_id_commande;
END;

CREATE OR REPLACE PROCEDURE GetContenuCommande(IN _id_commande INT)
BEGIN
    SELECT id_exemplaire FROM Exemplaire WHERE id_commande=_id_commande;
END;