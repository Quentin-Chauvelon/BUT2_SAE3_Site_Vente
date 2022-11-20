-- COUPON TROP UTILISÉ
-- COUPON NON VALABLE
-- COUPON EXPIRÉ
-- Suppression de client -> Mise de l'id de la commande à NULL, suppression des favoris
DROP TRIGGER IF EXISTS coupon_trop_utilise_insert;
DROP TRIGGER IF EXISTS coupon_trop_utilise_update;
DROP TRIGGER IF EXISTS coupon_expire_insert;
DROP TRIGGER IF EXISTS coupon_expire_update;
DROP TRIGGER IF EXISTS coupon_non_valable_insert;
DROP TRIGGER IF EXISTS coupon_non_valable_update;
DROP TRIGGER IF EXISTS supprimer_client_cascade;
DROP TRIGGER IF EXISTS exemplaire_pas_dispo_commande; -- Met est_dispo à false quand on ajoute à une commande
DROP TRIGGER IF EXISTS suppression_commande_liberer_exemplaire; -- met dispo à true quand on supprime une commande
DROP TRIGGER IF EXISTS suppression_collection;

CREATE OR REPLACE TRIGGER coupon_trop_utilise_insert
BEFORE INSERT ON Commande FOR EACH ROW BEGIN
DECLARE use_max INT;
DECLARE comm INT;
IF new.id_coupon IS NOT NULL THEN
	SELECT MAX(utilisations_max) INTO use_max FROM Coupon WHERE code = NEW.id_coupon;
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
	SELECT MAX(utilisations_max) INTO use_max FROM Coupon WHERE code = NEW.id_coupon;
	SELECT COUNT(*) INTO comm FROM Commande WHERE id_coupon = NEW.id_coupon;
	IF use_max <= comm THEN
	SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
END IF;
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_expire_insert
BEFORE INSERT ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT code FROM Coupon WHERE date_limite IS NOT NULL AND date_limite < NEW.date_commande) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a expiré.';
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_expire_update BEFORE
UPDATE ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT code FROM Coupon WHERE date_limite IS NOT NULL AND date_limite < NEW.date_commande) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a expiré.';
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_non_valable_insert BEFORE
INSERT ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT code FROM Coupon WHERE est_valable = false) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction n\'est pas valable.';
END IF;
END;

CREATE OR REPLACE TRIGGER coupon_non_valable_update BEFORE
UPDATE ON Commande FOR EACH ROW BEGIN
IF NEW.id_coupon IN (SELECT code FROM Coupon WHERE est_valable = false) THEN
    SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction n\'est pas valable.';
END IF;
END;

CREATE OR REPLACE TRIGGER supprimer_client_cascade BEFORE
DELETE ON Client FOR EACH ROW BEGIN
    DELETE FROM Favori WHERE id_client=OLD.id_client;
    UPDATE Commande SET id_client=NULL WHERE id_client=OLD.id_client;
END;

CREATE OR REPLACE TRIGGER exemplaire_pas_dispo_commande BEFORE
UPDATE ON Exemplaire FOR EACH ROW BEGIN
   IF NEW.id_commande IS NOT NULL THEN
      UPDATE Exemplaire SET est_disponible=false WHERE id_exemplaire=NEW.id_exemplaire;
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