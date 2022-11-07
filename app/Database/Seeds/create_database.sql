CREATE TABLE Client(
    id_client INT PRIMARY KEY,
    adresse_email VARCHAR(255) UNIQUE NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(64) NOT NULL,
    password VARCHAR(64) NOT NULL,
    est_admin BOOLEAN NOT NULL DEFAULT false,
    est_fidele BOOLEAN NOT NULL DEFAULT false -- TODO ajouter avec un trigger
);

CREATE VIEW Admin AS SELECT * FROM Client WHERE est_admin = true;
CREATE VIEW Fidele AS SELECT * FROM Client WHERE est_fidele = true;

CREATE TABLE Coupon(
    code VARCHAR(20) PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    montant INT NOT NULL CHECK(montant > 0),
    est_pourcentage BOOLEAN,
    est_valable BOOLEAN,
    CONSTRAINT pourcentage_valable CHECK(NOT est_pourcentage OR montant <= 100)
);

CREATE TABLE Commande(
    id_commande INT PRIMARY KEY,
    id_client INT NOT NULL FOREIGN KEY REFERENCES Client(id_client),
    date_commande DATE NOT NULL,
    date_livraison_estimee DATE NOT NULL,
    date_livraison DATE,
    id_coupon VARCHAR(20) FOREIGN KEY REFERENCES Coupon(code)
    CONSTRAINT livraison_estimee_apres_commande CHECK(date_livraison_estimee > date_commande),
    CONSTRAINT livraison_apres_commande CHECK(date_livraison IS NULL OR date_livraison > date_commande)
);

CREATE TABLE Collection (
    id_collection INT PRIMARY KEY,
    parution DATE NOT NULL,
    date_limite DATE,
    CONSTRAINT fin_apres_parution CHECK(date_limite IS NULL OR date_limite > parution)
);

CREATE TABLE Produit(
    id_produit INT PRIMARY KEY,
    id_collection INT FOREIGN KEY REFERENCES Collection(id_collection),
    nom VARCHAR(100) UNIQUE NOT NULL,
    prix INT NOT NULL, -- prix en centimes
    reduction INT NOT NULL DEFAULT 0, -- une réduction en centimes, pourcentage calculé dans le php
    description VARCHAR(500) NOT NULL DEFAULT '',
    CONSTRAINT check_prix_positif CHECK(prix >= 0)
    CONSTRAINT check_reduction_positive CHECK(reduction >= 0),
    CONSTRAINT check_prix_superieur_reduction CHECK(reduction <= prix),
);

CREATE VIEW ProduitReduction AS SELECT * FROM Produit WHERE reduction > 0;

CREATE TABLE Exemplaire(
    id_exemplaire INT PRIMARY KEY,
    id_produit INT NOT NULL FOREIGN KEY REFERENCES Produit(id_produit),
    id_commande INT FOREIGN KEY REFERENCES Commande(id_commande), -- TODO trigger pour vérifier si la date d'obtention correspond à la date de commande
    date_obtention DATE NOT NULL,
    est_disponible BOOLEAN NOT NULL DEFAULT true
    CONSTRAINT dispo_pas_commande CHECK(id_commande IS NULL OR est_disponible = false) -- empêche l'article d'être dispo alors qu'il est commandé
);

CREATE VIEW ExemplaireDispo AS SELECT * FROM Exemplaire WHERE est_disponible = true;

CREATE TABLE Favori(
    id_client INT NOT NULL FOREIGN KEY REFERENCES Client(id_client),
    id_produit INT NOT NULL FOREIGN KEY REFERENCES Produit(id_produit),
    PRIMARY KEY(id_client, id_produit)
);
