<?php
require '../config.php';
session_start();

// Vérification que l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connect-disconnect/login.php');
    exit;
}

// Récupération du rôle de l'utilisateur
$stmt = $pdo->prepare("
    SELECT r.label 
    FROM utilisateur u
    JOIN role r ON u.role_id = r.role_id
    WHERE u.username = ?
");
$stmt->execute([$_SESSION['user_id']]);
$userRole = $stmt->fetchColumn();

// Rediriger si l'utilisateur n'est pas administrateur
if ($userRole !== 'Administrateur') {
    header('Location: ../index.php');
    exit;
}

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage du mot de passe
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role_id = $_POST['role_id'];

    // Validation : Vérifier que le rôle est "Employé" ou "Vétérinaire"
    $allowed_roles = [2, 3]; // ID des rôles (Employé = 2, Vétérinaire = 3, à ajuster selon votre base)
    if (!in_array($role_id, $allowed_roles)) {
        die("Action non autorisée. Vous ne pouvez créer que des comptes Employé ou Vétérinaire.");
    }

    // Validation : Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetchColumn() > 0) {
        die("Un utilisateur avec cet e-mail existe déjà.");
    }

    // Insertion de l'utilisateur dans la base de données
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

    // Envoi de l'email à l'utilisateur avec la fonction mail()
    $to = $username;
    $subject = "Votre compte a été créé";
    $message = "Bonjour $prenom $nom,\n\nVotre compte a été créé avec succès.\nVotre identifiant (courriel) est : $username.\n\nVeuillez contacter l'administrateur pour obtenir votre mot de passe.";
    $headers = "From: jose.garcia040376@gmail.com";

    // Utilisation de la fonction mail() avec Postfix
    mail($to, $subject, $message, $headers);
} 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Arcadia</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="icon" href="data:,">
</head>
<body>
    <header>
        <h1>Arcadia - Créer un Utilisateur</h1>
    </header>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="../services/services.php">Services</a>
        <a href="../habitats/habitats.php">Habitats</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role_id'] == 2): ?>
                <a href="../habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="../connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == 3): ?>
                <a href="../avis/gestion_avis.php">Gestion des Avis</a>
                <a href="../habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="../connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == 1): ?>
                <a href="../avis/gestion_avis.php">Gestion des Avis</a>
                <a href="../services/gestion_services.php">Gestion des Services</a>
                <a href="../habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="../admin/create_user.php">Créer un Utilisateur</a>
                <a href="../connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php else: ?>
                <a href="../connect-disconnect/login.php">Se connecter</a>
        <?php endif; ?>
    </nav>
    <main>
        <h2>Créer un compte utilisateur</h2>
        <section class="form-section">
            <form action="create_user.php" method="POST">
                <label for="username">Courriel (Username) :</label>
                <input type="email" id="username" name="username" required>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
                
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>
                
                <label for="role">Rôle :</label>
                <select id="role" name="role_id" required>
                    <?php
                    // Limiter les rôles visibles à "Employé" et "Vétérinaire"
                    $stmt = $pdo->query("SELECT role_id, label FROM role WHERE label IN ('Employé', 'Vétérinaire')");
                    while ($row = $stmt->fetch()) {
                        echo "<option value=\"{$row['role_id']}\">{$row['label']}</option>";
                    }
                    ?>
                </select>
                
                <button type="submit">Créer l'utilisateur</button>
            </form>
        </section>
    </main>
</body>
</html>
