<?php
session_start();
require '../config.php';

// Vérification du rôle de l'utilisateur (1 = Administrateur, 3 = Employé)
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 3) {
    echo "Vous n'avez pas les autorisations nécessaires pour ajouter de la nourriture.";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $animal_id = $_POST['animal_id'];
    $quantite = $_POST['quantite'];
    $date_nourriture = $_POST['date_nourriture'];
    $heure_nourriture = $_POST['heure_nourriture'];

    // Insertion des données dans la base de données
    $stmt = $pdo->prepare("INSERT INTO nourriture (animal_id, quantite, date, heure) VALUES (?, ?, ?, ?)");
    $stmt->execute([$animal_id, $quantite, $date_nourriture, $heure_nourriture]);

    // Vérification de l'insertion
    if ($stmt) {
        echo "Nourriture ajoutée avec succès!";
    } else {
        echo "Erreur lors de l'ajout de la nourriture.";
    }
    header('Location: ../habitats/gestion_habitats.php');
}
?>