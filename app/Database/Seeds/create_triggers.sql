-- COUPON TROP UTILISÉ
-- COUPON NON VALABLE
-- COUPON EXPIRÉ
DROP TRIGGER IF EXISTS coupon_trop_utilise_insert;
DROP TRIGGER IF EXISTS coupon_trop_utilise_update;
DROP TRIGGER IF EXISTS coupon_expire_insert;
DROP TRIGGER IF EXISTS coupon_expire_update;
DROP TRIGGER IF EXISTS coupon_non_valable_insert;
DROP TRIGGER IF EXISTS coupon_non_valable_update;

CREATE TRIGGER coupon_trop_utilise_insert
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