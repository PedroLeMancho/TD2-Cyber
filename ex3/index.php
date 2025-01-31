<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "testdb");

if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Vérification de l'ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupération des infos de l'utilisateur (FAILLE IDOR)
    $sql = "SELECT * FROM users WHERE id = $id";  // Pas de contrôle d'accès !
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<h2>Profil de " . htmlspecialchars($user['username']) . "</h2>";
        echo "<p>Email : " . htmlspecialchars($user['email']) . "</p>";
        echo "<p>Informations privées : " . htmlspecialchars($user['private_data']) . "</p>";
    } else {
        echo "<p>Utilisateur non trouvé.</p>";
    }
} else {
    echo "<p>Aucun ID fourni.</p>";
}
?>
