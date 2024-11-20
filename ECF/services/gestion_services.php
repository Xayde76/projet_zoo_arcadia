<?php
require '../config.php';
session_start();

$message = '';

// Vérification que l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
    header('Location: index.php');
    exit;
}

// Traitement de l'ajout de service
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nom'], $_POST['description'])) {
        $nom = trim($_POST['nom']);
        $description = trim($_POST['description']);

        // Vérification de la longueur de la description (max 1000 caractères)
        if (strlen($description) > 1000) {
            $message = "La description ne doit pas dépasser 1000 caractères.";
        } elseif (!empty($nom) && !empty($description)) {
            $stmt = $pdo->prepare("INSERT INTO service (nom, description) VALUES (:nom, :description)");
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
            ]);
            $message = "Le service a été ajouté avec succès.";
        } else {
            $message = "Tous les champs sont obligatoires.";
        }
    }

    // Traitement de la suppression d'un service
    if (isset($_POST['delete_service_id'])) {
        $serviceId = (int) $_POST['delete_service_id'];

        // Suppression du service
        $stmt = $pdo->prepare("DELETE FROM service WHERE service_id = :service_id");
        $stmt->execute([':service_id' => $serviceId]);
    }
}

// Récupérer la liste des services existants
$stmt = $pdo->query("SELECT * FROM service ORDER BY nom ASC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arcadia</title>
        <link rel="stylesheet" href="../style/style.css">
    </head>
    <body>
        <header>
            <h1>Arcadia - Gestion des Services</h1>
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
            <div class="services">
                <h2>Ajouter un nouveau service</h2>
                <?php if ($message): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <label for="nom">Nom du service :</label>
                    <input type="text" id="nom" name="nom" required maxlength="50">
                    
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" rows="4" required maxlength="1000"></textarea>
                    
                    <button type="submit">Ajouter le service</button>
                </form>
            </div>
            <div class="services">
                <h2>Services existants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= htmlspecialchars($service['nom']) ?></td>
                                <td><?= htmlspecialchars($service['description']) ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="delete_service_id" value="<?= $service['service_id'] ?>">
                                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
            <p>&copy; Arcadia Zoo</p>
        </footer>
    </body>
</html>
