-- COUPON TROP UTILISÉ

-- COUPON EXPIRÉ

CREATE OR REPLACE TRIGGER coupon_trop_utilise_insert
BEFORE INSERT ON Commande FOR EACH ROW BEGIN
	IF NEW.id_coupon IS NOT NULL AND
		SELECT(MAX(c.utilisations_max) FROM Coupon c WHERE c.id_coupon = NEW.id_coupon) <=
		(SELECT COUNT(*) FROM Commande c WHERE c.id_coupon = NEW.id_coupon)
		THEN
			SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
END;

CREATE OR REPLACE TRIGGER coupon_trop_utilise_update
BEFORE UPDATE ON Commande FOR EACH ROW BEGIN
	IF NEW.id_coupon IS NOT NULL AND
		SELECT(MAX(c.utilisations_max) FROM Coupon c WHERE c.id_coupon = NEW.id_coupon) <=
		(SELECT COUNT(*) FROM Commande c WHERE c.id_coupon = NEW.id_coupon)
		THEN
			SIGNAL SQLSTATE '45000' SET message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
END;

/* CREATE OR REPLACE TRIGGER coupon_expire_insert
BEFORE INSERT ON Commande FOR EACH ROW
DECLARE DATE_COUPON DATE;
BEGIN
	IF NEW.id_coupon IS NOT NULL THEN
	SELECT
	    date_limite INTO date_coupon
	FROM Coupon c
	WHERE
	    c.id_coupon = NEW.id_coupon;
	IF EXISTS(date_limite)
	AND date_limite IS NOT NULL
	AND date_limite < NEW.date_Commande THEN SIGNAL SQLSTATE '45000'
	SET
	    message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
	END IF;
END;

CREATE OR REPLACE TRIGGER COUPON_EXPIRE_UPDATE BEFORE
UPDATE ON Commande DECLARE DATE_COUPON DATE; BEGIN
	IF NEW.id_coupon IS NOT NULL THEN
	SELECT
	    date_limite INTO date_coupon
	FROM Coupon c
	WHERE
	    c.id_coupon = NEW.id_coupon;
	IF EXISTS(date_limite)
	AND date_limite IS NOT NULL
	AND date_limite < NEW.date_Commande THEN SIGNAL SQLSTATE '45000'
	SET
	    message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
	END IF;
END; */