/*
* `coupon_trop_utilise_insert`
* `coupon_trop_utilise_update`
* `coupon_expire_insert`
* `coupon_expire_update`
* `coupon_non_valable_insert`
* `coupon_non_valable_update`
* `supprimer_client_cascade`
* `exemplaire_pas_dispo_commande` Met est_dispo à false quand on ajoute à une commande
* `suppression_commande_liberer_exemplaire` Met dispo à true quand on supprime une commande
* `suppression_collection`
* `categorie_produit_invalide_insert`
* `categorie_produit_invalide_update`
* `taille_valide_insert`
* `taille_valide_update`
* `update_commande_validee`
* `commande_validee_mauvaise`
*/
CREATE OR REPLACE TRIGGER coupon_trop_utilise_insert
BEFORE INSERT ON Commande FOR EACH ROW BEGIN
DECLARE use_max INT;
DECLARE comm INT;
IF new.id_coupon IS NOT NULL THEN
	SELECT MAX(utilisations_max) INTO use_max FROM Coupon WHERE id_coupon = NEW.id_coupon;
	SELECT COUNT(*) INTO comm FROM Commande WHERE id_coupon = NEW.id_coupon;
	IF use_max <= comm THEN
	SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
END IF;
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_trop_utilise_update
BEFORE UPDATE ON Commande FOR EACH ROW BEGIN
DECLARE use_max INT;
DECLARE comm INT;
IF new.id_coupon IS NOT NULL THEN
	SELECT MAX(utilisations_max) INTO use_max FROM Coupon WHERE id_coupon = NEW.id_coupon;
	SELECT COUNT(*) INTO comm FROM Commande WHERE id_coupon = NEW.id_coupon;
	IF use_max <= comm THEN
	SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
END IF;
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_expire_insert
BEFORE INSERT ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT id_coupon FROM Coupon WHERE date_limite IS NOT NULL AND date_limite < NEW.date_commande) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a expiré.';
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_expire_update BEFORE
UPDATE ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT id_coupon FROM Coupon WHERE date_limite IS NOT NULL AND date_limite < NEW.date_commande) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a expiré.';
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_non_valable_insert BEFORE
INSERT ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT id_coupon FROM Coupon WHERE est_valable = false) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction n\'est pas valable.';
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_non_valable_update BEFORE
UPDATE ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT id_coupon FROM Coupon WHERE est_valable = false) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction n\'est pas valable.';
END IF;
END;

CREATE OR REPLACE TRIGGER supprimer_client_cascade BEFORE
DELETE ON Client FOR EACH ROW BEGIN
    IF (SELECT COUNT(*) FROM Commande WHERE id_client = OLD.id_client AND date_livraison <= CURDATE()) > 0 THEN
        SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de supprimer un client qui a une commande en cours.';
    ELSE
        DELETE FROM Favori WHERE id_client=OLD.id_client;
        UPDATE Commande SET id_client=NULL WHERE id_client=OLD.id_client;
    END IF;
END;

CREATE OR REPLACE TRIGGER exemplaire_pas_dispo_commande BEFORE
UPDATE ON Exemplaire FOR EACH ROW BEGIN
   IF NEW.id_commande IS NOT NULL THEN
      SET NEW.est_disponible=false;
END IF;
END;

CREATE OR REPLACE TRIGGER suppression_commande_liberer_exemplaire BEFORE
DELETE ON Commande FOR EACH ROW BEGIN
    UPDATE Exemplaire SET id_commande=NULL, est_disponible=true WHERE id_commande=OLD.id_commande;
END;

CREATE OR REPLACE TRIGGER suppression_collection BEFORE
DELETE ON Collection FOR EACH ROW BEGIN
   UPDATE Produit SET id_collection=NULL WHERE id_collection=OLD.id_collection;
END;

CREATE OR REPLACE TRIGGER categorie_produit_invalide_insert BEFORE
INSERT ON Produit FOR EACH ROW BEGIN
    IF NEW.categorie NOT IN ('tshirt', 'sweat', 'pantalon', 'accessoire', 'poster') THEN
        SIGNAL SQLSTATE '45000' SET message_text = 'Cette catégorie de vêtement n\'est pas valide.';
    END IF;
END;

CREATE OR REPLACE TRIGGER categorie_produit_invalide_update BEFORE
UPDATE ON Produit FOR EACH ROW BEGIN
    IF NEW.categorie NOT IN ('tshirt', 'sweat', 'pantalon', 'accessoire', 'poster') THEN
        SIGNAL SQLSTATE '45000' SET message_text = 'Cette catégorie de vêtement n\'est pas valide.';
    END IF;
END;

CREATE OR REPLACE TRIGGER taille_valide_insert BEFORE
INSERT ON Exemplaire FOR EACH ROW BEGIN
   IF NEW.taille IS NOT NULL THEN
       IF NEW.id_produit IN (SELECT categorie FROM Produit WHERE categorie = 'accessoire') THEN
           SIGNAL SQLSTATE '45000' SET message_text = 'Les accessoires n\'ont pas de taille.';
       END IF;
       IF NEW.id_produit IN (SELECT categorie FROM Produit WHERE categorie IN ('tshirt', 'sweat', 'pantalon'))
           AND NEW.taille IN (SELECT taille FROM Taille WHERE categorie = 'poster') THEN
                SIGNAL SQLSTATE '45000' SET message_text = 'Impossible d\'associer une taille de poster à un vêtement.';
       END IF;
       IF NEW.id_produit IN (SELECT categorie FROM Produit WHERE categorie = 'poster') AND
          NEW.taille NOT IN (SELECT taille FROM Taille WHERE categorie = 'poster') THEN
           SIGNAL SQLSTATE '45000' SET message_text = 'Impossible d\'associer une taille de vêtement à un poster.';
       END IF;
END IF;
END;

CREATE OR REPLACE TRIGGER taille_valide_update BEFORE
INSERT ON Exemplaire FOR EACH ROW BEGIN
   IF NEW.taille IS NOT NULL THEN
       IF NEW.id_produit IN (SELECT categorie FROM Produit WHERE categorie = 'accessoire') THEN
           SIGNAL SQLSTATE '45000' SET message_text = 'Les accessoires n\'ont pas de taille.';
       END IF;
       IF NEW.id_produit IN (SELECT categorie FROM Produit WHERE categorie IN ('tshirt', 'sweat', 'pantalon'))
           AND NEW.taille IN (SELECT taille FROM Taille WHERE categorie = 'poster') THEN
                SIGNAL SQLSTATE '45000' SET message_text = 'Impossible d\'associer une taille de poster à un vêtement.';
       END IF;
       IF NEW.id_produit IN (SELECT categorie FROM Produit WHERE categorie = 'poster') AND
          NEW.taille NOT IN (SELECT taille FROM Taille WHERE categorie = 'poster') THEN
           SIGNAL SQLSTATE '45000' SET message_text = 'Impossible d\'associer une taille de vêtement à un poster.';
       END IF;
END IF;
END;

CREATE OR REPLACE TRIGGER update_commande_validee BEFORE
UPDATE ON Commande FOR EACH ROW BEGIN
   IF OLD.est_validee = true THEN
       IF OLD.id_client != NEW.id_client THEN
           SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de modifier le client d\'une commande validée.';
       END IF;
       IF OLD.id_coupon != NEW.id_coupon THEN
            SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de modifier le coupon d\'une commande validée.';
       END IF;
       IF OLD.montant != NEW.montant THEN
            SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de modifier le montant d\'une commande validée.';
       END IF;
   END IF;
END;

CREATE OR REPLACE TRIGGER commande_validee_mauvaise BEFORE
UPDATE ON Commande FOR EACH ROW BEGIN
    IF OLD.est_validee = false AND NEW.est_validee = true THEN
        IF NEW.id_client IS NULL THEN
            SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de valider une commande sans client.';
        END IF;
        IF NEW.id_adresse IS NULL THEN
            SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de valider une commande sans adresse.';
        END IF;
        IF NOT EXISTS(SELECT * FROM Exemplaire WHERE Exemplaire.id_commande = NEW.id_commande) THEN
            SIGNAL SQLSTATE '45000' SET message_text = 'Impossible de valider une commande sans articles.';
        END IF;
    END IF;
END;