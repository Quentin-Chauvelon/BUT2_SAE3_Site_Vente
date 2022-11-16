-- COUPON TROP UTILISÉ

-- COUPON EXPIRÉ

CREATE OR REPLACE TRIGGER COUPON_TROP_UTILISE_INSERT 
BEFORE INSERT ON COMMANDE BEGIN 
	IF NEW.id_coupon IS NOT NULL
	AND MAX(
	    SELECT utilisations_max
	    FROM Coupon c
	    WHERE
	        c.id_coupon = NEW.id_COUPON
	) <= (
	    SELECT COUNT(*)
	    FROM Commande c
	    WHERE
	        c.id_COUPON = NEW.id_coupon
	) THEN SIGNAL SQLSTATE '45000'
	SET
	    message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
END; 

CREATE OR REPLACE TRIGGER COUPON_TROP_UTILISE_UPDATE 
BEFORE UPDATE ON COMMANDE FOR EACH ROW BEGIN 
	IF NEW.id_coupon IS NOT NULL
	AND MAX(
	    SELECT utilisations_max
	    FROM Coupon c
	    WHERE
	        c.id_coupon = NEW.id_COUPON
	) <= (
	    SELECT COUNT(*)
	    FROM Commande c
	    WHERE
	        c.id_COUPON = NEW.id_coupon
	) THEN SIGNAL SQLSTATE '45000'
	SET
	    message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
END; 

CREATE OR REPLACE TRIGGER COUPON_EXPIRE_INSERT BEFORE 
INSERT ON COMMANDE DECLARE DATE_COUPON DATE; BEGIN 
	IF NEW.id_coupon IS NOT NULL THEN
	SELECT
	    date_limite INTO date_coupon
	FROM Coupon c
	WHERE
	    c.id_coupon = NEW.id_COUPON;
	IF EXISTS(date_limite)
	AND date_limite IS NOT NULL
	AND date_limite < NEW.date_commande THEN SIGNAL SQLSTATE '45000'
	SET
	    message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
	END IF;
END; 

CREATE OR REPLACE TRIGGER COUPON_EXPIRE_UPDATE BEFORE 
UPDATE ON COMMANDE DECLARE DATE_COUPON DATE; BEGIN 
	IF NEW.id_coupon IS NOT NULL THEN
	SELECT
	    date_limite INTO date_coupon
	FROM Coupon c
	WHERE
	    c.id_coupon = NEW.id_COUPON;
	IF EXISTS(date_limite)
	AND date_limite IS NOT NULL
	AND date_limite < NEW.date_commande THEN SIGNAL SQLSTATE '45000'
	SET
	    message_text = 'Ce coupon de réduction a été trop souvent utilisé.';
	END IF;
	END IF;
END; 