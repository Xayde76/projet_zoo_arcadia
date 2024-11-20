<?php
    session_start();
    include('../add/add_habitat.php');
    include('../add/add_animal.php');
    include('../delete/delete_habitat.php');
    include('../delete/delete_animal.php');
    include('../delete/delete_nourriture.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <title>Arcadia</title>
    <link rel="stylesheet" href="../style/style.css">  
</head>
<body>
    <header>
        <h1>Arcadia - Gestion des Habitats et des Animaux</h1>
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
        <!-- Formulaire pour ajouter un habitat -->
        <section class="form-section">
            <h2>Ajouter un habitat</h2>
            <form action="../add/add_habitat.php" method="POST" enctype="multipart/form-data" class="ajout">
                <label for="nom_habitat">Nom de l'habitat :</label>
                <input type="text" id="nom_habitat" name="nom_habitat" required>

                <label for="description_habitat">Description :</label>
                <textarea id="description_habitat" name="description_habitat" required></textarea>

                <label for="commentaire_habitat">Commentaire :</label>
                <textarea id="commentaire_habitat" name="commentaire_habitat"></textarea>

                <label for="image_habitat">Image de l'habitat :</label>
                <input type="file" id="image_habitat" name="image_habitat">

                <button type="submit">Ajouter l'habitat</button>
            </form>
        </section>

        <!-- Formulaire pour ajouter un animal -->
        <section class="form-section">
            <h2>Ajouter un animal</h2>
            <form action="../add/add_animal.php" method="POST" enctype="multipart/form-data" class="ajout">
                <label for="prenom_animal">Prénom de l'animal :</label>
                <input type="text" id="prenom_animal" name="prenom_animal" required>

                <label for="race_animal">Race :</label>
                <input type="text" id="race_animal" name="race_animal" required>

                <label for="etat_animal">État :</label>
                <input type="text" id="etat_animal" name="etat_animal" required>

                <label for="habitat_id">Habitat :</label>
                <select id="habitat_id" name="habitat_id" required>
                    <!-- Options générées dynamiquement via PHP -->
                    <?php
                    require '../config.php';
                    $stmt = $pdo->query("SELECT habitat_id, nom FROM habitat");
                    while ($row = $stmt->fetch()) {
                        echo "<option value=\"{$row['habitat_id']}\">{$row['nom']}</option>";
                    }
                    ?>
                </select>

                <button type="submit">Ajouter l'animal</button>
            </form>
        </section>

        <!-- Section pour ajouter la nourriture aux animaux (Employé) -->
        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 3): ?>
            <section class="form-section">
                <h2>Ajouter de la nourriture à un animal</h2>
                <form action="../add/add_nourriture.php" method="POST">
                    <label for="animal_id">Sélectionner un animal :</label>
                    <select id="animal_id" name="animal_id" required>
                        <?php
                        // Récupérer la liste des animaux
                        $stmt = $pdo->query("SELECT animal_id, prenom FROM animal");
                        while ($row = $stmt->fetch()) {
                            echo "<option value=\"{$row['animal_id']}\">{$row['prenom']}</option>";
                        }
                        ?>
                    </select>

                    <label for="quantite">Quantité de nourriture :</label>
                    <input type="number" id="quantite" name="quantite" required>

                    <label for="date_nourriture">Date de la nourriture :</label>
                    <input type="date" id="date_nourriture" name="date_nourriture" required>

                    <label for="heure_nourriture">Heure :</label>
                    <input type="time" id="heure_nourriture" name="heure_nourriture" required>

                    <button type="submit">Ajouter Nourriture</button>
                </form>
            </section>
        <?php endif; ?>

        <!-- Section pour supprimer des habitats -->
        <section class="form-section">
            <h2>Supprimer un habitat</h2>
            <form action="../delete/delete_habitat.php" method="POST" class="delete-section">
                <label for="habitat_id_delete">Sélectionner un habitat :</label>
                <select id="habitat_id_delete" name="habitat_id_delete" required>
                    <!-- Options générées dynamiquement via PHP -->
                    <?php
                    $stmt = $pdo->query("SELECT habitat_id, nom FROM habitat");
                    while ($row = $stmt->fetch()) {
                        echo "<option value=\"{$row['habitat_id']}\">{$row['nom']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Supprimer</button>
            </form>
        </section>

        <!-- Section pour supprimer des animaux -->
        <section class="form-section">
            <h2>Supprimer un animal</h2>
            <form action="../delete/delete_animal.php" method="POST" class="delete-section">
                <label for="animal_id">Sélectionner un animal :</label>
                <select id="animal_id" name="animal_id" required>
                    <!-- Options générées dynamiquement via PHP -->
                    <?php
                    $stmt = $pdo->query("SELECT animal_id, prenom FROM animal");
                    while ($row = $stmt->fetch()) {
                        echo "<option value=\"{$row['animal_id']}\">{$row['prenom']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Supprimer</button>
            </form>
        </section>

        <!-- Section pour supprimer une nourriture -->
        <section class="form-section">
            <h2>Supprimer de la nourriture</h2>
            <form action="../delete/delete_nourriture.php" method="POST" class="delete-section">
                <label for="nourriture_id_delete">Sélectionner une nourriture :</label>
                <select id="nourriture_id_delete" name="nourriture_id_delete" required>
                    <?php
                    // Récupérer la liste des nourritures stockées dans la base de données
                    $stmt = $pdo->query("SELECT n.animal_id, a.prenom, n.date, n.heure, n.animal_id, n.quantite
                                        FROM nourriture n
                                        JOIN animal a ON n.animal_id = a.animal_id");

                    while ($row = $stmt->fetch()) {
                        // Afficher chaque nourriture avec l'animal, la date et l'heure
                        echo "<option value=\"{$row['animal_id']}_{$row['date']}_{$row['heure']}\">";
                        echo "Animal: {$row['prenom']} - Date: {$row['date']} - Heure: {$row['heure']} - Quantité: {$row['quantite']}";
                        echo "</option>";
                    }
                    ?>
                </select>

                <button type="submit">Supprimer</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; Arcadia Zoo</p>
    </footer>
</body>
</html>
