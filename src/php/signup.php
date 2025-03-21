<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - E_Bibliothèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

    <!-- Conteneur principal -->
    <div class="min-h-screen flex items-center justify-center bg-teal-50">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <!-- Bouton de retour à l'accueil -->
            <div class="mb-5">
                <a href="../../index.php" class="text-3xl text-orange-400 font-medium hover:underline">
                    &larr;
                </a>
            </div>
            <h2 class="text-2xl font-bold text-teal-600 text-center">Créer un compte</h2>
            <p class="text-center text-gray-600 text-sm mt-2">
                Rejoignez notre bibliothèque en ligne dès aujourd'hui !
            </p>
            
            <!-- Formulaire d'inscription -->
            <form action="process_signup.php" method="POST" class="mt-6 space-y-4">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="nom" id="nom" required 
                        class="mt-1 p-2 w-full border-2 border-teal-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600"
                        placeholder="Entrez votre nom">
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                    <input type="text" name="prenom" id="prenom" required 
                        class="mt-1 p-2 w-full border-2 border-teal-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600"
                        placeholder="Entrez votre prénom">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse Email</label>
                    <input type="email" name="email" id="email" required 
                        class="mt-1 p-2 w-full border-2 border-teal-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600"
                        placeholder="Entrez votre email">
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition">
                    Créer un compte
                </button>
            </form>
            
            <!-- Lien vers la page de connexion -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Vous avez déjà un compte ? 
                <a href="login.php" class="text-teal-600 font-medium hover:underline">Connectez-vous</a>
            </p>
        </div>
    </div>

</body>
</html>
