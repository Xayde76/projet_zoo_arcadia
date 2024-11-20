<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom_animal'];
    $race = $_POST['race_animal'];
    $etat = $_POST['etat_animal'];
    $habitat_id = $_POST['habitat_id'];
    $image = $_FILES['image_animal']['tmp_name'];

    // Ajouter la race à la table race
    $stmt = $pdo->prepare("INSERT INTO race (avel) VALUES (:race)");
    $stmt->execute([':race' => $race]);
    $race_id = $pdo->lastInsertId();

    // Vérifier si un fichier a été envoyé
    if ($image) {
        $imageData = file_get_contents($image);

        // Insérer l'image dans la table image
        $stmt = $pdo->prepare("INSERT INTO image (image_data) VALUES (:image_data)");
        $stmt->bindParam(':image_data', $imageData, PDO::PARAM_LOB);
        $stmt->execute();

        $image_id = $pdo->lastInsertId();
    } else {
        $image_id = null; // Pas d'image associée
    }

    // Ajouter l'animal à la table animal
    $stmt = $pdo->prepare("
        INSERT INTO animal (prenom, etat, habitat_id, race_id) 
        VALUES (:prenom, :etat, :habitat_id, :race_id)
    ");
    $stmt->execute([
        ':prenom' => $prenom,
        ':etat' => $etat,
        ':habitat_id' => $habitat_id,
        ':race_id' => $race_id
    ]);

    header('Location: ../habitats/gestion_habitats.php');
}
?>
