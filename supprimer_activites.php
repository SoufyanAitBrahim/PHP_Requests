<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // Vérifier si la révision appartient à l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM seance WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        $pdo->prepare("DELETE FROM seance WHERE id = ?")->execute([$id]);
    }
    
    header("Location: index.php");
    exit();
}