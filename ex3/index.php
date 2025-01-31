<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "testdb");

if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

$message = "";

// Vérification de l'identifiant et du mot de passe
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Utilisation de requêtes préparées pour éviter l'injection SQL détectable par Semgrep
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $message = "Connexion réussie !";
        } else {
            $message = "Échec de la connexion.";
        }
    } else {
        // Faille : Affichage des erreurs SQL (information leakage)
        $message = "Erreur SQL : " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat</title>
</head>
<body>
    <h2>Résultat de la connexion</h2>
    <p><?php echo htmlspecialchars($message); ?></p>
</body>
</html>
