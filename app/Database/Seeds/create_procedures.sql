/*
- GetAllClients()
- GetClientParID()
*/

CREATE OR REPLACE PROCEDURE GetAllClients()
BEGIN
   SELECT * FROM Client;
END;

CREATE OR REPLACE PROCEDURE GetClientParID(IN ID INT)
BEGIN
    SELECT * FROM Client WHERE id_client = ID;
END;