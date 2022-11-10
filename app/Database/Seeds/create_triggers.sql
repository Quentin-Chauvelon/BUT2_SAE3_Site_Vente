-- COUPON TROP UTILISÉ
-- COUPON EXPIRÉ

CREATE OR REPLACE TRIGGER coupon_trop_utilise_insert
BEFORE INSERT ON Commande
WHEN (NEW.id_coupon IS NOT NULL)
BEGIN
    IF MAX(SELECT utilisations_max FROM Coupon c WHERE c.id_coupon = NEW.id_COUPON)
    <= (SELECT COUNT(*) FROM Commande c WHERE c.id_COUPON = NEW.id_coupon)
    THEN
        SIGNAL SQLSTATE '45000'SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
    END IF;
END;

CREATE OR REPLACE TRIGGER coupon_trop_utilise_update
BEFORE UPDATE ON commande FOR EACH ROW
WHEN (NEW.id_coupon IS NOT NULL)
BEGIN
    IF MAX(SELECT utilisations_max FROM Coupon c WHERE c.id_coupon = NEW.id_COUPON)
    <= (SELECT COUNT(*) FROM Commande c WHERE c.id_COUPON = NEW.id_coupon)
    THEN
        SIGNAL SQLSTATE '45000'SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
    END IF;
END;

CREATE OR REPLACE TRIGGER coupon_expire_insert
BEFORE INSERT ON Commande
WHEN (NEW.id_coupon IS NOT NULL)
DECLARE date_coupon DATE;
BEGIN
    SELECT date_limite INTO date_coupon FROM Coupon c WHERE c.id_coupon = NEW.id_COUPON;
    IF EXISTS(date_limite) AND date_limite IS NOT NULL AND date_limite < NEW.date_commande
    THEN
        SIGNAL SQLSTATE '45000'SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
    END IF;
END;

CREATE OR REPLACE TRIGGER coupon_expire_update
BEFORE UPDATE ON Commande
WHEN (NEW.id_coupon IS NOT NULL)
DECLARE date_coupon DATE;
BEGIN
    SELECT date_limite INTO date_coupon FROM Coupon c WHERE c.id_coupon = NEW.id_COUPON;
    IF EXISTS(date_limite) AND date_limite IS NOT NULL AND date_limite < NEW.date_commande
    THEN
        SIGNAL SQLSTATE '45000'SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
    END IF;
END;
