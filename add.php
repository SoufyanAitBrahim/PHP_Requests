<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activité'])) {
    $activites = $_POST['activité'];
    $duree = intval($_POST['duree']);
    $calories = intval($_POST['calories']);
    $date = $_POST['date'];

    // Vérifications
    if (empty($activites) || empty($duree) || empty($calories) || empty($date)) {
        $erreur = "Tous les champs sont obligatoires";
    } elseif ($note < 0 || $note > 4000) {
        $erreur = "Les calories doit être entre 0 et 4000";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO seance (utilisateur_id, types, duree, calories, date) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $activites, $duree, $calories, $date]);
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $erreur = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Ajouter une nouvelle activité</title></head>
<body>
    <?php if (!empty($erreur)): ?>
        <p style="color:red;"><?php echo $erreur; ?></p>
    <?php endif; ?>
    <form method="POST">
    
        <label>Activites :</label>
            <select name="activité" onchange="this.form.submit()">
                <option value="">-- Choisir une activité --</option>
                <option value="vélo" <?= (isset($_POST['activité']) && $_POST['activité'] == 'vélo') ? 'selected' : '' ?>>vélo</option>
                <option value="marche" <?= (isset($_POST['activité']) && $_POST['activité'] == 'marche') ? 'selected' : '' ?>>marche</option>
                <option value="course" <?= (isset($_POST['activité']) && $_POST['activité'] == 'course') ? 'selected' : '' ?>>course</option>
                <option value="autres" <?= (isset($_POST['activité']) && $_POST['activité'] == 'autres') ? 'selected' : '' ?>>autres</option>
            </select>
        Durée (minutes) : <input type="number" name="duree" required><br>
        Calories : <input type="number" name="calories" required><br>
        Date : <input type="date" name="date" required><br>
        <button type="submit">Ajouter</button>
    </form>
    <br>
    <button type="submit">
        <a href="index.php">Mes activites</a>
    </button>
</body>
</html>