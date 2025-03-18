<?php
// Paramètres de connexion à la base de données
$servername = "localhost"; // Nom du serveur (généralement 'localhost' sur un serveur local)
$username = "root"; // Votre nom d'utilisateur MySQL
$password = ""; // Votre mot de passe MySQL
$dbname = "e_bibliotheque"; // Le nom de votre base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}

// echo "Connexion réussie"; // Optionnel, à utiliser pour tester la connexion
?>
