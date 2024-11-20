<?php
require '../config.php';

try {
    // Vérifiez que la clé secrète est fournie et correcte
    if (!isset($_GET['key']) || $_GET['key'] !== 'jose777') {
        die('Accès non autorisé.');
    }

    // Paramètres de l'administrateur à créer
    $username = 'jose.garcia040376@gmail.com';
    $password = password_hash('ilovejosette', PASSWORD_DEFAULT);
    $nom = 'Admin';
    $prenom = 'Super';

    // Vérifiez si le rôle "Administrateur" existe
    $stmt = $pdo->prepare("SELECT role_id FROM role WHERE label = 'Administrateur'");
    $stmt->execute();
    $role = $stmt->fetch();

    if (!$role) {
        die("Le rôle Administrateur n'existe pas dans la base de données. Veuillez le créer avant de continuer.");
    }

    $role_id = $role['role_id'];

    // Vérifiez si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT username FROM utilisateur WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        die("Un utilisateur avec cet email existe déjà.");
    }

    // Insérez l'administrateur
    $stmt = $pdo->prepare("
        INSERT INTO utilisateur (username, password, nom, prenom, role_id) 
        VALUES (:username, :password, :nom, :prenom, :role_id)
    ");

    $stmt->execute([
        ':username' => $username,
        ':password' => $password,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':role_id' => $role_id
    ]);

    echo "Administrateur créé avec succès.";

} catch (PDOException $e) {
    // Affiche les erreurs pour le débogage
    echo "Erreur : " . $e->getMessage();
}
?>
