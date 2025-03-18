<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);

    // Validation simple
    if (empty($nom) || empty($prenom) || empty($email)) {
        die("Tous les champs sont obligatoires.");
    }

    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "e_bibliotheque"); // Changez les paramètres si nécessaire

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Préparer et exécuter la requête
    $stmt = $conn->prepare("INSERT INTO lecteurs (nom, prenom, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $prenom, $email);

    if ($stmt->execute()) {
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: login.php"); // Redirection vers la page de connexion
    } else {
        echo "Erreur : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
