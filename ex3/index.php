<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "testdb");

// 1. Command Injection via un paramètre GET (corrigée)
if (isset($_GET['command'])) {
    $command = $_GET['command']; // Entrée utilisateur

    // Validation de l'entrée utilisateur pour éviter l'injection de commandes
    $allowed_commands = ['ls', 'whoami']; // Liste des commandes autorisées
    if (in_array($command, $allowed_commands)) {
        // Exécution uniquement des commandes autorisées
        $output = shell_exec($command);
        echo "Sortie de la commande : " . htmlspecialchars($output); // Sécurisation de la sortie pour éviter les XSS
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
