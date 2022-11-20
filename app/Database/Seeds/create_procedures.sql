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
*/


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