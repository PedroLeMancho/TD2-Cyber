<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "testdb");

// 1. Command Injection via un paramètre GET
if (isset($_GET['command'])) {
    $command = $_GET['command']; // Vulnérable à l'injection de commande
    // Exécution de la commande système (par exemple, supprimer des fichiers ou manipuler le serveur)
    $output = shell_exec($command);
    echo "Sortie de la commande : " . $output;
}

// Exemple de formulaire
?>
<form method="GET">
    <input type="text" name="command" placeholder="Entrez une commande">
    <input type="submit" value="Exécuter">
</form>
