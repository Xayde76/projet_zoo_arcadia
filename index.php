<?php
require 'config.php';

session_start();

// Traitement du formulaire d'avis
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $commentaire = trim($_POST['commentaire']);

    if (!empty($pseudo) && !empty($commentaire)) {
        $stmt = $pdo->prepare("INSERT INTO avis (pseudo, commentaire, isVisible) VALUES (:pseudo, :commentaire, 0)");
        $stmt->execute([':pseudo' => $pseudo, ':commentaire' => $commentaire]);
        $message = "Votre avis a été soumis avec succès. Il sera visible après validation par un employé.";
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

// Récupérer les avis validés
$avisQuery = $pdo->query("SELECT pseudo, commentaire FROM avis WHERE isVisible = 1 ORDER BY avis_id DESC");
$avisList = $avisQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les services disponibles
$servicesQuery = $pdo->query("SELECT nom, description FROM service");
$servicesList = $servicesQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Arcadia</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" href="data:,">
</head>
<body>
    <header>
        <h1>Arcadia</h1>
    </header>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="services/services.php">Services</a>
        <a href="habitats/habitats.php">Habitats</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role_id'] == 2): ?>
                <a href="habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == 3): ?>
                <a href="avis/gestion_avis.php">Gestion des Avis</a>
                <a href="habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == 1): ?>
                <a href="avis/gestion_avis.php">Gestion des Avis</a>
                <a href="services/gestion_services.php">Gestion des Services</a>
                <a href="habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="admin/create_user.php">Créer un Utilisateur</a>
                <a href="connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php else: ?>
                <a href="connect-disconnect/login.php">Se connecter</a>
        <?php endif; ?>
    </nav>
    <main>
        <p>
            Arcadia est un zoo situé en France près de la forêt de Brocéliande, en Bretagne, depuis 1960.</br>
            Ils possèdent tout un panel d'animaux, répartis par habitat (savane, jungle, marais) et font</br> 
            extrêmement attention à leur santé. Chaque jour, plusieurs vétérinaires viennent afin</br>
            d'effectuer les contrôles sur chaque animal avant l'ouverture du zoo pour s'assurer que</br> 
            tout se passe bien. De même, toute la nourriture donnée est calculée afin d'avoir le bon</br> 
            grammage (le bon grammage est précisé dans le rapport du vétérinaire).</br>
        </p>
    </main>
    
   <div id="carousel">
    <div id="container">
    </div>
        <button class="btn" id="prev">&#10096;</button>
        <button class="btn" id="next">&#10097;</button>
   </div>
   <script src="carousel.js"></script>

   <section class="service">
        <h2>Nos Services</h2>
        <?php if (count($servicesList) > 0): ?>
            <ul>
                <?php foreach ($servicesList as $service): ?>
                    <li>
                        <h3><?= htmlspecialchars($service['nom']) ?></h3>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun service disponible pour le moment.</p>
        <?php endif; ?>
   </section>

   <footer>
        <div class="form-container">
            <h2>Soumettre un avis</h2>
            <?php if (!empty($message)): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="text" name="pseudo" placeholder="Votre pseudo" required>
                <textarea name="commentaire" placeholder="Votre avis" rows="5" required maxlength="500" oninput="updateCharacterCount()"></textarea>
                <p id="charCount">500 caractères restants</p>
                <button type="submit">Soumettre</button>
            </form>
            <script>
                function updateCharacterCount() {
                    var textarea = document.querySelector('textarea');
                    var charCount = document.getElementById('charCount');
                    var remaining = 500 - textarea.value.length;
                    charCount.textContent = remaining + " caractères restants";
                }
            </script>
        </div>

        <section class="avis">
            <h2>Avis des visiteurs</h2>
            <?php if (count($avisList) > 0): ?>
                <ul>
                    <?php foreach ($avisList as $avis): ?>
                        <li>
                            <strong><?= htmlspecialchars($avis['pseudo']) ?>:</strong>
                            <p><?= nl2br(htmlspecialchars($avis['commentaire'])) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun avis visible pour le moment.</p>
            <?php endif; ?>
        </section>
        <p>&copy; Arcadia Zoo</p>
    </footer>
</body>
</html>
