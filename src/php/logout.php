<?php
session_start();
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session
header('Location: /php/e_bibliotheque/index.php'); // Redirige vers la page d'accueil après la déconnexion
exit;
?>