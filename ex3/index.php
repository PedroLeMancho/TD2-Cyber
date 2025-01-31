<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "testdb");

// 1. Command Injection via un paramètre GET (corrigée avec proc_open)
if (isset($_GET['command'])) {
    $command = $_GET['command']; // Entrée utilisateur

    // Validation stricte avec une liste blanche
    $allowed_commands = ['ls', 'whoami'];
    if (in_array($command, $allowed_commands)) {
        // Création sécurisée du processus
        $descriptor_spec = [
            0 => ["pipe", "r"], // STDIN
            1 => ["pipe", "w"], // STDOUT
            2 => ["pipe", "w"]  // STDERR
        ];

        $process = proc_open($command, $descriptor_spec, $pipes);
        if (is_resource($process)) {
            $output = stream_get_contents($pipes[1]); // Lire la sortie
            fclose($pipes[1]);
            proc_close($process);
            echo "Sortie de la commande : " . htmlspecialchars($output);
        }
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
