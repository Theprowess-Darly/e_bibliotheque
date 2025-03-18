<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = trim($_POST['email']);

    // Validation simple
    if (empty($email)) {
        die("L'adresse email est obligatoire.");
    }

    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "e_bibliotheque"); // Changez les paramètres si nécessaire

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Requête pour vérifier si l'utilisateur existe
    $stmt = $conn->prepare("SELECT id, nom, prenom FROM lecteurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // L'utilisateur existe, démarrer une session
        $user = $result->fetch_assoc();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['prenom'] . " " . $user['nom'];

        // Redirection vers la page d'accueil
        header('Location: /php/e_bibliotheque/index.php');
        exit();
    } else {
        echo "Aucun compte trouvé avec cet email. <a href='signup.php'>Inscrivez-vous</a>.";
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
