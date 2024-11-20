<?php
require '../config.php';
session_start();

// Récupérer les habitats avec leurs informations et les images associées
$stmt = $pdo->query("
    SELECT habitat.habitat_id, habitat.nom AS habitat_nom, habitat.description AS habitat_desc, 
           habitat.commentaire_habitat, image.image_data 
    FROM habitat
    LEFT JOIN image ON habitat.image_id = image.image_id
");
$habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les animaux associés à chaque habitat
$animalsByHabitat = [];
foreach ($habitats as $habitat) {
    $stmt = $pdo->prepare("
        SELECT animal.animal_id, animal.prenom, animal.etat, race.avel AS race, habitat.nom AS habitat_nom
        FROM animal
        JOIN race ON animal.race_id = race.race_id
        JOIN habitat ON animal.habitat_id = habitat.habitat_id
        WHERE animal.habitat_id = :habitat_id
    ");
    $stmt->execute([':habitat_id' => $habitat['habitat_id']]);
    $animalsByHabitat[$habitat['habitat_id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Arcadia</title>
    <link rel="stylesheet" href="../style/style.css">
    <script>
        function showDetails(id) {
            document.querySelectorAll('.details-container').forEach(container => {
                container.style.display = 'none';
            });
            document.getElementById('details-' + id).style.display = 'block';
        }
    </script>
</head>
<body>
    <header>
        <h1>Arcadia - Habitats</h1>
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
        <h2 id="habitats-title">Découvrez nos habitats</h2>
        <section class="habitats-container">
            <?php foreach ($habitats as $habitat): ?>
                <div class="habitat-card" onclick="showDetails('habitat-<?= $habitat['habitat_id'] ?>')">
                    <?php if (!empty($habitat['image_data'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($habitat['image_data']) ?>" alt="<?= htmlspecialchars($habitat['habitat_nom']) ?>">
                    <?php else: ?>
                        <img src="images/default.jpg" alt="Image par défaut">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($habitat['habitat_nom']) ?></h3>
                </div>
            <?php endforeach; ?>
        </section>

        <?php foreach ($habitats as $habitat): ?>
            <section id="details-habitat-<?= $habitat['habitat_id'] ?>" class="details-container">
                <h3><?= htmlspecialchars($habitat['habitat_nom']) ?></h3>
                <p><?= htmlspecialchars($habitat['habitat_desc']) ?></p>
                <div class="animals-list">
                    <?php foreach ($animalsByHabitat[$habitat['habitat_id']] as $animal): ?>
                        <div class="animal-card" onclick="showDetails('animal-<?= $animal['animal_id'] ?>')">
                            <img src="images/<?= strtolower($animal['prenom']) ?>.jpg" alt="<?= htmlspecialchars($animal['prenom']) ?>">
                            <h4><?= htmlspecialchars($animal['prenom']) ?></h4>
                            <p>Race : <?= htmlspecialchars($animal['race']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

    </main>
    <footer>
        <p>&copy; Arcadia Zoo</p>
    </footer>
</body>
</html>

