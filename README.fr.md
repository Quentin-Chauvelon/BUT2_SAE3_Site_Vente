[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md) 

# Hot Genre
![Static Badge](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=777BB4&labelColor=grey)
![Static Badge](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=E34F26&labelColor=grey)
![Static Badge](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=1572B6&labelColor=grey)
[![JavaScript](https://github.com/aleen42/badges/raw/docs/src/javascript.svg)](https://www.javascript.com/)
![Static Badge](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=4479A1&labelColor=grey&cacheSeconds=https%3A%2F%2Fwww.mysql.com%2F)
![Static Badge](https://img.shields.io/badge/CodeIgniter-EF4223?style=flat&logo=codeigniter&logoColor=EF4223&labelColor=grey&cacheSeconds=https%3A%2F%2Fwww.codeigniter.com%2F)
![Static Badge](https://img.shields.io/badge/Trello-0052CC?style=flat&logo=trello&logoColor=0052CC&labelColor=grey)
![Static Badge](https://img.shields.io/badge/GitHub-181717?style=flat&logo=github&logoColor=181717&labelColor=grey)

Hot Genre est un projet de fin de semestre réalisé par groupe de 5 pendant 2 mois en 2ème année de BUT. Pour ce projet, nous devions développer un site de vente en ligne qui devait gérer tout le processus de commande par un client, de la connexion à la validation de la commande (le paiement n'étant pas à gérer).

Le projet a été réalisé en PHP, HTML5, CSS3 et JavaScript avec le framework [CodeIgniter 4](https://www.codeigniter.com) et avec une base de données [MySQL](https://www.mysql.com/).

Afin de nous organiser au mieux, nous nous sommes répartis les tâches à l'aide de l'outil de gestion Trello.

Avant de commencer, nous avons mené une analyse des besoins et avons réalisé différents schémas nous permettant de bien comprendre le besoin et de détrerminer ce qu'il nous fallait faire.

![Diagramme de cas d'utilisation](Images/Use_Case_Diagram.png)  
*Diagramme de cas d'utilisation*

![Exemple de diagramme de séquence réalisé (commander)](Images/Sequence_Diagram.png)  
*Exemple de diagramme de séquence réalisé (commander)*


```mermaid
classDiagram
direction BT
class Adresse {
   int(11) code_postal
   varchar(100) ville
   varchar(100) rue
   int(11) id_adresse
}
class Client {
   varchar(255) adresse_email
   varchar(255) nom
   varchar(64) prenom
   varchar(64) password
   tinyint(1) est_admin
   int(11) id_client
}
class Collection {
   varchar(50) nom
   date parution
   date date_limite
   int(11) id_collection
}
class Commande {
   int(11) id_client
   int(11) id_adresse
   date date_commande
   date date_livraison_estimee
   date date_livraison
   varchar(20) id_coupon
   tinyint(1) est_validee
   int(11) montant
   int(11) id_commande
}
class Coupon {
   varchar(50) nom
   int(11) montant
   tinyint(1) est_pourcentage
   tinyint(1) est_valable
   date date_limite
   int(11) utilisations_max
   varchar(20) id_coupon
}
class Exemplaire {
   int(11) id_produit
   int(11) id_commande
   date date_obtention
   tinyint(1) est_disponible
   varchar(3) taille
   varchar(20) couleur
   int(11) id_exemplaire
}
class Favori {
   int(11) id_client
   int(11) id_produit
}
class Produit {
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
   int(11) id_produit
}
class Taille {
   varchar(10) categorie
   varchar(3) taille
}
class accessoire {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}
class admin {
   int(11) id_client
   varchar(255) adresse_email
   varchar(255) nom
   varchar(64) prenom
   varchar(64) password
   tinyint(1) est_admin
}
class couponvalable {
   varchar(20) id_coupon
   varchar(50) nom
   int(11) montant
   tinyint(1) est_pourcentage
   tinyint(1) est_valable
   date date_limite
   int(11) utilisations_max
}
class exemplairedispo {
   int(11) id_exemplaire
   int(11) id_produit
   int(11) id_commande
   date date_obtention
   tinyint(1) est_disponible
   varchar(3) taille
   varchar(20) couleur
}
class fidele {
   int(11) id_client
   varchar(255) adresse_email
   varchar(255) nom
   varchar(64) prenom
   varchar(64) password
   tinyint(1) est_admin
}
class pantalon {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}
class poster {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}
class produitreduction {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}
class sweat {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}
class tshirt {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}
class vetement {
   int(11) id_produit
   int(11) id_collection
   varchar(100) nom
   int(11) prix
   int(11) reduction
   varchar(500) description
   varchar(50) categorie
   date parution
}

Commande  -->  Adresse : id_adresse
Commande  -->  Client : id_client
Commande  -->  Coupon : id_coupon
Exemplaire  -->  Commande : id_commande
Exemplaire  -->  Produit : id_produit
Exemplaire  -->  Taille : taille
Favori  -->  Client : id_client
Favori  -->  Produit : id_produit
Produit  -->  Collection : id_collection
admin --> Client : id_client
couponvalable --> Coupon : id_coupon
accessoire --> Produit : id_produit
accessoire --> Collection : id_collection
exemplairedispo --> Exemplaire : id_exemplaire
exemplairedispo --> Produit : id_produit
exemplairedispo --> Commande : id_commande
fidele --> Client : id_client
pantalon --> Produit : id_produit
pantalon --> Collection : id_collection
poster --> Produit : id_produit
poster --> Collection : id_collection
produitreduction --> Produit : id_produit
produitreduction --> Collection : id_collection
sweat --> Produit : id_produit
sweat --> Collection : id_collection
tshirt --> Produit : id_produit
tshirt --> Collection : id_collection
vetement --> Produit : id_produit
vetement --> Collection : id_collection
```
*Diagramme base de données*

Nous avons décidé d'utiliser CodeIgniter 4 car ce dernier est basé sur un modèle MVC (Model View Controller). Cela permet de bien séparer les différents aspects de l'application avec les contrôleurs pour le back-end, les modèles pour les accès à la base de données et les vues pour le front-end.

Les principales fonctinnalités que nous avons implémentées sont :
- Authentification
- Consultation du catalogue de produits
- Choix de la taille et de la couleur du produit
- Mise en favori des produits
- Panier
- Coupons de réduction
- Commandes
- Historique de commandes
- Tableau de bord pour administrateurs (pour gérer les comptes, produits, stocks...)
- ...

Sur ce projet, j'ai principalement travaillé sur les contrôleurs et les vues mais aussi sur l'analyse des besoins et la base de données.

Nous avons aussi réalisé des tests de performance sur différentes pages du site afin d'estimer la charge que pouvais supporter le serveur (voir le rapport [ici](Rapport_Tests_Performance.pdf))


# Installation et configuration

## Prérequis

> PHP version 8.1+
> PHP extensions : mbstring, curl, intl, xml, mysql
> MySQL

## Déploiement

Il faut tout d'abord cloner ce dépôt.

Configurer ensuite la variable `baseUrl` dans `app/Config/App.php` pour indiquer l'hôte et le port voulu

Puis, créer une base de données avec MySQL :
```sql
CREATE DATABASE database_name;
USE database_name;
```
Et exécuter les trois scripts SQL dans `app/Database/Seeds` :
```sql
source PATH/TO/create_tables.sql
source PATH/TO/create_procedures.sql
source PATH/TO/create_triggers.sql
```

Après cela, configurer la variable `default` du fichier `app/Config/Database.php` en y indiquant le `username` et le `password` de l'utilisateur pour se connecter à la bd et le nom de la base de données dans `database`.


Pour démarrer le serveur, exécuter :
```
php spark serve
```

Pour avoir accès aux fonctionnalités administrateurs du site, il faut créer un utilisateur (grâce au bouton Inscription) puis manuellement le mettre adminstrateur depuis la base de données avec la commande :
```sql
UPDATE Client SET set_admin = 1 WHERE id_client = 1;
```
Cela n'est à faire que la première fois et peut ensuite être fait depuis la page administrateur de ce compte.


# Screenshots

![Page d'accueil](Images/Home.gif)  
*Page d'accueil*

![Création de compte](Images/Account_Creation.gif)  
*Création de compte*

![Liste des produits](Images/Products.png)  
*Liste des produits*

![Ajout de produit au panier](Images/Add_Product_To_Cart.gif)  
*Ajout de produit au panier*

![Confirmation de commande](Images/Order.gif)  
*Confirmation de commande*

![Récapitulatif de commande](Images/Order_Summary.png)  
*Récapitulatif de commande*

![Profil](Images/Profile.gif)  
*Profil*

![Page administrateur - Gestion des comptes](Images/Admin_Accounts_Management.png)  
*Page administrateur - Gestion des comptes*

![Page administrateur - Gestion des produits](Images/Admin_Products_Management.gif)  
*Page administrateur - Gestion des produits*

![Page administrateur - Gestion des stocks](Images/Admin_Stock_Management.png)  
*Page administrateur - Gestion des stocks*

![Page administrateur - Gestion des coupons de rédution](Images/Admin_Vouchers_Management.png)  
*Page administrateur - Gestion des coupons de rédution*

![Page administrateur - Historique des commandes](Images/Admin_Orders_Summary.png)  
*Page administrateur - Historique des commandes*


# Contact

Email: [quentin.chauvelon@gmail.com](mailto:quentin.chauvelon@gmail.com) 

LinkedIn: [Quentin Chauvelon](https://www.linkedin.com/in/quentin-chauvelon/) 