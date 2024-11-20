<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nourriture_id_delete'])) {
    // Récupérer la valeur envoyée par le formulaire
    $selected_value = $_POST['nourriture_id_delete'];

    // Séparer les informations envoyées (animal_id, date, heure)
    list($animal_id, $date, $heure) = explode('_', $selected_value);

    try {
        // Préparer et exécuter la requête pour supprimer la nourriture
        $stmt = $pdo->prepare("DELETE FROM nourriture 
                               WHERE animal_id = :animal_id AND date = :date AND heure = :heure");
        $stmt->execute([
            ':animal_id' => $animal_id,
            ':date' => $date,
            ':heure' => $heure,
        ]);

        // Rediriger ou afficher un message de succès
        header('Location: ../habitats/gestion_habitats.php');
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
