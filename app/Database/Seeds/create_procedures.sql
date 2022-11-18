/*
- GetAllClients()
- GetClientParID(ID)
- GetClientParEmail(email)
- GetAllAdmins()
- GetAllFideles()
- CreerClient(mail, nom, prenom, password) Pas admin par défaut
- ModifierClient(id, mail, nom, prenom, password, admin)
- CreerCollection(nom)
- ModifierCollection(id, nom, parution, date_limite)
- GetAllCollections()
- GetCollectionParId(id)
- GetCollectionParNom(nom)
- GetCollectionsActuelles()
- GetCollectionsEphemeresActuelles()
*/

CREATE OR REPLACE PROCEDURE GetAllClients()
BEGIN
   SELECT * FROM Client;
END;

CREATE OR REPLACE PROCEDURE GetClientParID(IN ID INT)
BEGIN
    SELECT * FROM Client WHERE id_client = ID;
END;

CREATE OR REPLACE PROCEDURE GetClientParEmail(IN mail VARCHAR(255))
BEGIN
    SELECT * FROM Client WHERE adresse_email=mail;
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
IN new_mail VARCHAR(255), IN new_nom VARCHAR(255), IN new_prenom VARCHAR(64), IN new_password VARCHAR(64))
BEGIN
   INSERT INTO Client(adresse_email, nom, prenom, password, est_admin) VALUES (new_mail, new_nom, new_prenom, new_password, false);
END;

CREATE OR REPLACE PROCEDURE ModifierClient(
IN ID INT, IN new_mail VARCHAR(255), IN new_nom VARCHAR(255), IN new_prenom VARCHAR(64), IN new_password VARCHAR(64), IN new_admin BOOLEAN)
BEGIN
    UPDATE Client SET adresse_email=new_mail, nom=new_nom, prenom=new_prenom, password=new_password, est_admin=new_admin WHERE id_client=ID;
END;


CREATE OR REPLACE PROCEDURE CreerCollection(IN nom_coll VARCHAR(50))
BEGIN
    INSERT INTO Collection(nom, parution, date_limite) VALUES (nom_coll, CURDATE(), NULL);
END;

CREATE OR REPLACE PROCEDURE ModifierCollection(IN ID INT, nom_coll VARCHAR(50), debut DATE, date_fin DATE)
BEGIN
    UPDATE Collection SET nom=nom_coll, date_limite=date_fin, parution=debut WHERE id_collection=ID;
END;

CREATE OR REPLACE PROCEDURE GetAllCollections()
BEGIN
    SELECT * FROM Collection;
END;


CREATE OR REPLACE PROCEDURE GetCollectionParId(IN id INT)
BEGIN
    SELECT * FROM Collection WHERE id_collection=id;
END;

CREATE OR REPLACE PROCEDURE GetCollectionParNom(IN nom_coll VARCHAR(50))
BEGIN
    SELECT * FROM Collection WHERE nom=nom_coll;
END;

CREATE OR REPLACE PROCEDURE GetCollectionsActuelles()
BEGIN
    SELECT * FROM Collection WHERE date_limite IS NULL OR date_limite >= CURDATE();
END;

CREATE OR REPLACE PROCEDURE GetCollectionsEphemeresActuelles()
BEGIN
   SELECT * FROM Collection WHERE date_limite IS NOT NULL AND date_limite >= CURDATE();
END;