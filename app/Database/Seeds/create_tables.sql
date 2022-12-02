CREATE
OR
REPLACE
TABLE
    Client(
        id_client INT PRIMARY KEY AUTO_INCREMENT,
        adresse_email VARCHAR(255) UNIQUE NOT NULL,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(64) NOT NULL,
        password VARCHAR(64) NOT NULL,
        est_admin BOOLEAN NOT NULL DEFAULT false
    );

CREATE OR REPLACE VIEW Admin AS
	SELECT * FROM Client WHERE est_admin =
TRUE;

CREATE
OR
REPLACE
TABLE
    Coupon(
        id_coupon VARCHAR(20) PRIMARY KEY,
        nom VARCHAR(50) NOT NULL,
        montant INT NOT NULL CHECK(montant > 0),
        est_pourcentage BOOLEAN NOT NULL,
        est_valable BOOLEAN NOT NULL,
        date_limite DATE,
        utilisations_max INT,
        CONSTRAINT pourcentage_valable CHECK(
            NOT est_pourcentage
            OR montant <= 100
        )
    );

CREATE OR REPLACE VIEW CouponValable AS
SELECT * FROM Coupon WHERE est_valable = true;

CREATE OR REPLACE TABLE Adresse(
    id_adresse INT PRIMARY KEY AUTO_INCREMENT,
    code_postal INT NOT NULL,
    rue VARCHAR(100) NOT NULL,
    CONSTRAINT adresse_unique UNIQUE(code_postal, rue)
);

CREATE
OR
REPLACE
TABLE
    Commande(
        id_commande INT PRIMARY KEY AUTO_INCREMENT,
        id_client INT,
        id_adresse INT,
        date_commande DATE NOT NULL,
        date_livraison_estimee DATE NOT NULL,
        date_livraison DATE,
        id_coupon VARCHAR(20),
        est_validee BOOLEAN DEFAULT false,
        montant INT default 0,
        CONSTRAINT fk_commande_coupon FOREIGN KEY(id_coupon) REFERENCES Coupon(id_coupon),
        CONSTRAINT fk_commande_adresse FOREIGN KEY(id_adresse) REFERENCES Adresse(id_adresse),
        CONSTRAINT fk_commande_client FOREIGN KEY(id_client) REFERENCES Client(id_client),
        CONSTRAINT livraison_estimee_apres_commande CHECK(
            date_livraison_estimee > date_commande
        ),
        CONSTRAINT livraison_apres_commande CHECK(
            date_livraison IS NULL
            OR date_livraison > date_commande
        )
    );

CREATE OR REPLACE VIEW Fidele AS
	SELECT * FROM Client c WHERE 3 <=
    (SELECT COUNT(*) FROM Commande co WHERE co.id_client = c.id_client);

CREATE
OR
REPLACE
TABLE
    Collection (
        id_collection INT PRIMARY KEY AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL UNIQUE,
        parution DATE NOT NULL,
        date_limite DATE,
        CONSTRAINT fin_apres_parution CHECK(
            date_limite IS NULL
            OR date_limite > parution
        )
    );

CREATE
OR
REPLACE
TABLE
    Produit(
        id_produit INT PRIMARY KEY AUTO_INCREMENT,
        id_collection INT,
        nom VARCHAR(100) UNIQUE NOT NULL,
        prix INT NOT NULL,
        -- prix en centimes
        reduction INT NOT NULL DEFAULT 0,
        -- une réduction en centimes, pourcentage calculé dans le php
        description VARCHAR(500) NOT NULL DEFAULT '',
        categorie VARCHAR(50),
        parution DATE NOT NULL,
        CONSTRAINT fk_produit_collection FOREIGN KEY(id_collection) REFERENCES Collection(id_collection),
        CONSTRAINT check_prix_positif CHECK(prix >= 0),
        CONSTRAINT check_reduction_positive CHECK(reduction >= 0),
        CONSTRAINT check_prix_superieur_reduction CHECK(reduction <= prix)
    );

CREATE OR REPLACE VIEW ProduitReduction AS
	SELECT * FROM Produit WHERE reduction >
0;

CREATE OR REPLACE VIEW Poster AS
	SELECT * FROM Produit WHERE categorie =
'poster';

CREATE OR REPLACE VIEW Accessoire AS
	SELECT * FROM Produit WHERE categorie =
'accessoire';

CREATE OR REPLACE VIEW Vetement AS
	SELECT *
	FROM Produit
	WHERE
	    categorie in ('pantalon', 'sweat', 'tshirt');

CREATE OR REPLACE VIEW Pantalon AS
	SELECT * FROM Produit WHERE categorie =
'pantalon';

CREATE OR REPLACE VIEW Sweat AS
	SELECT * FROM Produit WHERE categorie =
'sweat';

CREATE OR REPLACE VIEW Tshirt AS
	SELECT * FROM Produit WHERE categorie =
'tshirt';

CREATE OR REPLACE TABLE Taille(
    taille VARCHAR(3) PRIMARY KEY,
    categorie VARCHAR(10) NOT NULL
);
INSERT INTO Taille VALUES('A0', 'poster');
INSERT INTO Taille VALUES('A1', 'poster');
INSERT INTO Taille VALUES('A2', 'poster');
INSERT INTO Taille VALUES('A3', 'poster');

INSERT INTO Taille VALUES('XS', 'vetement');
INSERT INTO Taille VALUES('S', 'vetement');
INSERT INTO Taille VALUES('M', 'vetement');
INSERT INTO Taille VALUES('L', 'vetement');
INSERT INTO Taille VALUES('XL', 'vetement');
INSERT INTO Taille VALUES('XXL', 'vetement');

CREATE
OR
REPLACE
TABLE
    Exemplaire(
        id_exemplaire INT PRIMARY KEY AUTO_INCREMENT,
        id_produit INT NOT NULL,
        id_commande INT,
        date_obtention DATE NOT NULL,
        est_disponible BOOLEAN NOT NULL DEFAULT true,
        taille VARCHAR(3),
        couleur VARCHAR(20),
        CONSTRAINT fk_exemplaire_produit FOREIGN KEY(id_produit) REFERENCES Produit(id_produit),
        CONSTRAINT fk_exemplaire_commande FOREIGN KEY(id_commande) REFERENCES Commande(id_commande),
        CONSTRAINT dispo_pas_commande CHECK(
            id_commande IS NULL
            OR est_disponible = false
        ), -- empêche l'article d'être dispo alors qu'il est commandé
        CONSTRAINT fk_taille FOREIGN KEY (taille) REFERENCES Taille(taille)
    );

CREATE OR REPLACE VIEW ExemplaireDispo AS
	SELECT * FROM Exemplaire WHERE est_disponible =
TRUE;

CREATE
OR
REPLACE
TABLE
    Favori(
        id_client INT NOT NULL,
        id_produit INT NOT NULL,
        CONSTRAINT fk_favori_client FOREIGN KEY(id_client) REFERENCES Client(id_client),
        CONSTRAINT fk_favori_produit FOREIGN KEY(id_produit) REFERENCES Produit(id_produit),
        PRIMARY KEY(id_client, id_produit)
    );

