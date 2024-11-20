<?php
require '../config.php';

session_start();

// Vérification que l'utilisateur est connecté et est administrateur ou employé
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connect-disconnect/login.php');
    exit;
}

// Traitement de la validation ou suppression d'avis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $avis_id = (int)$_POST['avis_id'];

        if ($_POST['action'] == 'valider') {
            // Valider l'avis
            $stmt = $pdo->prepare("UPDATE avis SET isVisible = 1 WHERE avis_id = ?");
            $stmt->execute([$avis_id]);
        } elseif ($_POST['action'] == 'supprimer') {
            // Supprimer l'avis
            $stmt = $pdo->prepare("DELETE FROM avis WHERE avis_id = ?");
            $stmt->execute([$avis_id]);
        }
    }
}

// Récupérer les avis non validés
$avisNonValidésQuery = $pdo->query("SELECT * FROM avis WHERE isVisible = 0 ORDER BY avis_id DESC");
$avisNonValidésList = $avisNonValidésQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les avis validés
$avisValidésQuery = $pdo->query("SELECT * FROM avis WHERE isVisible = 1 ORDER BY avis_id DESC");
$avisValidésList = $avisValidésQuery->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour limiter le texte à 500 caractères et ajouter un lien "Lire plus"
function limitCommentLength($commentaire) {
    $maxLength = 500;
    if (strlen($commentaire) > $maxLength) {
        return substr($commentaire, 0, $maxLength) . '... <a href="#">Lire plus</a>';
    }
    return $commentaire;
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
        <h1>Arcadia - Gestion des Avis</h1>
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
        <!-- Section des avis en attente de validation -->
         <section class="avis">
            <h2>Avis en attente de validation</h2>
            <?php if (count($avisNonValidésList) > 0): ?>
                <ul>
                    <?php foreach ($avisNonValidésList as $avis): ?>
                        <li class="avis-bloc">
                            <strong><?= htmlspecialchars($avis['pseudo']) ?>:</strong>
                            <p><?= nl2br(htmlspecialchars(limitCommentLength($avis['commentaire']))) ?></p>
                            <form method="POST" action="">
                                <input type="hidden" name="avis_id" value="<?= $avis['avis_id'] ?>">
                                <button type="submit" name="action" value="valider">Valider</button>
                                <button type="submit" name="action" value="supprimer">Supprimer</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun avis en attente de validation.</p>
            <?php endif; ?>
        </section>
        <!-- Section des avis validés -->
         <section class="avis">
            <h2>Avis validés</h2>
            <?php if (count($avisValidésList) > 0): ?>
                <ul>
                    <?php foreach ($avisValidésList as $avis): ?>
                        <li class="avis-bloc">
                            <strong><?= htmlspecialchars($avis['pseudo']) ?>:</strong>
                            <p><?= nl2br(htmlspecialchars(limitCommentLength($avis['commentaire']))) ?></p>
                            <form method="POST" action="">
                                <input type="hidden" name="avis_id" value="<?= $avis['avis_id'] ?>">
                                <button type="submit" name="action" value="supprimer">Supprimer</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun avis validé pour le moment.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; Arcadia Zoo</p>
    </footer>
</body>
</html>
