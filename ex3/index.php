<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "testdb");

// 1. Command Injection via un paramètre GET (corrigée avec escapeshellarg)
if (isset($_GET['command'])) {
    $command = trim($_GET['command']); // Nettoyage de l'entrée utilisateur

    // Validation stricte : Limiter les commandes autorisées
    $allowed_commands = ['ls', 'whoami']; // Liste blanche
    if (in_array($command, $allowed_commands, true)) {
        // Protection contre l'injection de commande
        $safe_command = escapeshellcmd(escapeshellarg($command));
        $output = shell_exec($safe_command);
        echo "Sortie de la commande : " . htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Commande non autorisée.";
    }
}

// Exemple de formulaire
?>
<form method="GET">
    <input type="text" name="command" placeholder="Entrez une commande">
    <input type="submit" value="Exécuter">
</form>
