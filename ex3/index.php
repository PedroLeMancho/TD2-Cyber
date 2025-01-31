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

    // Vulnérabilité SQLi détectable par un DAST mais pas par Semgrep
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $message = "Connexion réussie !";
    } else {
        $message = "Échec de la connexion.";
    }
}
?>