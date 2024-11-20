<?php
require '../config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcadia</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="icon" href="data:,">
</head>
<body>
    <header>
        <h1>Arcadia - Services</h1>
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
        <section class="services">
            <h2>Découvrez nos services</h2>
            <div class="service-list">
                <?php
                require '../config.php';

                // Récupérer les services depuis la table `service`
                $stmt = $pdo->query("SELECT nom, description FROM service ORDER BY nom ASC");
                $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($services) > 0): 
                    foreach ($services as $service): ?>
                        <div class="service-item">
                            <h3><?= htmlspecialchars($service['nom']); ?></h3>
                            <p><?= htmlspecialchars($service['description']); ?></p>
                        </div>
                    <?php endforeach; 
                else: ?>
                    <p>Aucun service disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; Arcadia Zoo</p>
    </footer>
</body>
</html>
