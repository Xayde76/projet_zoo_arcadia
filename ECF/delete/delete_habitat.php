<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $habitat_id = $_POST['habitat_id_delete'];

    // Supprimer l'habitat
    $stmt = $pdo->prepare("DELETE FROM habitat WHERE habitat_id = :habitat_id");
    $stmt->execute([':habitat_id' => $habitat_id]);

    header('Location: ../habitats/gestion_habitats.php');
}
?>
