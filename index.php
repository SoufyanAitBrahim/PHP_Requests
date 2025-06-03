<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM seance 
                      WHERE utilisateur_id = ? 
                      ORDER BY date DESC");
$stmt->execute([$_SESSION['user_id']]);
$seance = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Mes révisions</title></head>
<body>
    <h1>Seances</h1>
    <button type="submit">
        <a href="add.php">Ajouter une nouvelle activité</a>
    </button><br> <br>
    <table border="1">
        <tr>
            <th>Seance</th>
            <th>Durée</th>
            <th>Calories</th>
            <th>Date</th>
            <th>Supprimer</th>
        </tr>
        <?php foreach ($seance as $seances): ?>
            <tr>
                <td><?php echo $seances['types']; ?></td>
                <td><?php echo $seances['duree']; ?> min</td>
                <td><?php echo $seances['calories']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($seances['date'])); ?></td>
                <td>
                    <form method="POST" action="supprimer_activites.php">
                        <input type="hidden" name="id" value="<?php echo $seances['id']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>