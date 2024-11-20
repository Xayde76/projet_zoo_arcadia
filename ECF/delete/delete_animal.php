<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];

    // Supprimer l'animal
    $stmt = $pdo->prepare("DELETE FROM animal WHERE animal_id = :animal_id");
    $stmt->execute([':animal_id' => $animal_id]);

    header('Location: ../habitats/gestion_habitats.php');
}
?>
