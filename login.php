<?php
session_start();
require 'config.php';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email) || empty($mot_de_passe)) {
        $erreur = "Tous les champs sont obligatoires";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['nom'] = $utilisateur['nom'];
            header("Location: index.php");
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrects";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Connexion</title></head>
<body>
    <?php if (!empty($erreur)): ?>
        <p style="color:red;"><?php echo $erreur; ?></p>
    <?php endif; ?>
    <form method="POST">
        Email : <input type="email" name="email" required><br>
        Mot de passe : <input type="password" name="mot_de_passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>