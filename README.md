# Arcadia - ZOO

Arcadia est une application web destinée à la gestion des avis, des habitats et des animaux d'un zoo. Elle offre des fonctionnalités variées permettant aux administrateurs, vétérinaires et employés de gérer efficacement les données du zoo. Le site permet aux visiteurs de consulter les services proposés par le zoo, de laisser des avis, et aux utilisateurs disposant des rôles appropriés de gérer les animaux, les habitats et les avis.

## Fonctionnalités principales
- **Consultation des services du zoo** : Les utilisateurs peuvent voir les différents choses que propose le zoo.
- **Gestion des avis** : Les Employés et l'administrateur peuvent ajouter, modifier ou supprimer leurs avis.
- **Gestion des animaux et des habitats** : Les Employés et l'administrateur peut ajouter, modifier ou supprimer des animaux et des habitats.
- **Gestion des utilisateurs et des rôles** : L'administrateur peut créer des comptes utilisateurs (vétérinaire, employé) et gérer leurs rôles.
- **Formulaires dynamiques** : Permet l'ajout et la gestion des animaux, habitats, services et avis directement via des formulaires.

## Technologies utilisées
- **Figma** : Pour la conception visuelle du site.
- **PHP** : Langage côté serveur pour la gestion de la logique du site.
- **MySQL** : Système de gestion de base de données pour stocker les informations liées aux utilisateurs, avis, animaux, etc.
- **HTML/CSS** : Pour la structure et la mise en page du site.
- **XAMPP** : Environnement de développement local incluant Apache et MySQL.
- **GitHub** : Pour le versionnage du code et la collaboration.

## Installation et configuration

### 1. Clonez le repository
Commencez par cloner le repository GitHub en utilisant la commande suivante :
git clone https://github.com/Xayde76/projet_zoo_arcadia.git

# Configurez votre serveur local (XAMPP ou MAMP)
# Importez la base de données dans MySQL
# Assurez-vous que MySQL et Apache sont en cours d'exécution

# Créer la base de donnée
-- Créez une base de données
CREATE DATABASE arcadia;

-- Importez les tables via un fichier SQL
BDD/BDD.sql;

# Modifier le fichier config.php
Le fichier config.php permet de configurer les paramètres de connexion à votre base de données MySQL. Ouvrez ce fichier et renseignez les informations de connexion correspondant à votre environnement local 

# Lancer le site
Accédez à votre serveur local via votre navigateur en allant sur http://localhost/arcadia/. Le site s'ouvrira automatiquement et vous serez redirigé vers la page d'accueil (index.php).

Développé par : Romaric BOSSUT
