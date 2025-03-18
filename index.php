<?php
session_start(); // Démarre la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E_Bibliothèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/src/js/index.js"></script>
</head>
<body class="bg-white text-gray-800">

    <!-- En-tête - Affichage dynamique selon connexion -->
    <header class="bg-teal-500 text-white p-4" id="header">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold">E_Bibliothèque</div>
            <nav id="nav-links">
                <ul class="flex space-x-4">
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                        <!-- Si l'utilisateur est connecté -->
                        <li><a href="profile.php" class="hover:text-orange-600">Mon Profil</a></li>
                        <li><a href="src/php/logout.php" class="hover:text-orange-600">Se Déconnecter</a></li>
                    <?php else: ?>
                        <!-- Si l'utilisateur n'est pas connecté -->
                        <li><a href="src/php/login.php" class="hover:text-orange-600">Connexion</a></li>
                        <li><a href="src/php/signup.php" class="hover:text-orange-600">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Message d'accroche et Description -->
    <section class="py-20 bg-teal-50">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl text-teal-600 font-bold">Bienvenue sur votre E_BIBLIOTHEQUE</h1>
            <h1 class="text-4xl text-teal-600 font-bold">La Bibliothèque en Ligne comme vous l'aimez</h1>
            <p class="text-lg mt-4 text-gray-700">
                Découvrez notre collection de livres numériques. <br> Vous pouvez rechercher, emprunter,<br> et gérer vos livres préférés !
                <br>
                <span class="font-semibold">
                    <?php if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true): ?>
                        Veuillez vous connecter ou vous inscrire pour commencer à utiliser la bibliothèque.
                    <?php else: ?>
                        Accédez directement à notre collection et commencez à explorer !
                    <?php endif; ?>
                </span>
            </p>
        </div>
    </section>

    <!-- Formulaire de recherche - visible après connexion -->
    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
        <section id="search-section" class="py-8">
            <div class="container mx-auto text-center">
                <h2 class="text-2xl font-semibold text-teal-500">Rechercher un Livre</h2>
                <form action="./src/php/results/results.php" method="GET" class="mt-4">
                    <input type="text" name="query" placeholder="Rechercher par titre ou auteur..." class="p-2 w-1/2 border-2 border-teal-500 rounded-lg" required>
                    <button type="submit" class="p-2 bg-orange-600 text-white rounded-lg ml-4 hover:bg-orange-700">Rechercher</button>
                </form>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-teal-500 text-white py-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 DARLY TCHATCHOUANG pour E_Bibliothèque. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
