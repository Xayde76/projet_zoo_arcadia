<?php
require '../config.php';

if (isset($_POST["submit"])) {
    $req=$pdo->prepare("insert into image(image_data) values(?)");
    $req->execute(array(file_get_contents($_FILES['image_habitat']["tmp_name"])));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom_habitat'];
    $description = $_POST['description_habitat'];
    $commentaire = $_POST['commentaire_habitat'];
    $image = $_FILES['image_habitat']['tmp_name'];

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

    // Insérer l'habitat dans la table habitat
    $stmt = $pdo->prepare("
        INSERT INTO habitat (nom, description, commentaire_habitat, image_id) 
        VALUES (:nom, :description, :commentaire, :image_id)
    ");
    $stmt->execute([
        ':nom' => $nom,
        ':description' => $description,
        ':commentaire' => $commentaire,
        ':image_id' => $image_id
    ]);

    header('Location: ../habitats/gestion_habitats.php');
}
?>

