<?php
// Connexion à la base de données
include './db_connection.php'; // ce fichier contient les informations de connexion MySQL

// Vérifier si une requête de recherche a été envoyée
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

$books = [];
if (!empty($searchQuery)) {
    // Requête SQL pour rechercher les livres par titre ou auteur
    $stmt = $conn->prepare("SELECT id, titre, auteur FROM livres WHERE titre LIKE ? OR auteur LIKE ?");
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Récupérer les résultats
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de Recherche - E_Bibliothèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

    <!-- En-tête -->
    <header class="bg-teal-500 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">E_Bibliothèque</h1>
            <nav>
                <a href="../../../index.php" class="hover:text-orange-600">Accueil</a>
            </nav>
        </div>
    </header>

    <!-- Résultats de recherche -->
    <main class="py-10 container mx-auto">
        <h2 class="text-2xl font-bold text-teal-600 text-center">Résultats de la Recherche</h2>
        <p class="text-center text-gray-600 mt-2">
            Critère de recherche : <span class="font-semibold"><?php echo htmlspecialchars($searchQuery); ?></span>
        </p>

        <div class="mt-8">
            <?php if (count($books) > 0): ?>
                <!-- Liste des livres -->
                <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php foreach ($books as $book): ?>
                        <li class="bg-teal-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-bold text-teal-700"><?php echo htmlspecialchars($book['titre']); ?></h3>
                            <p class="text-sm text-gray-700">Auteur : <?php echo htmlspecialchars($book['auteur']); ?></p>
                            <a href="../detailsLivre/details.php?id=<?php echo $book['id']; ?>" class="block mt-4 bg-orange-600 text-white text-center py-2 rounded-lg hover:bg-orange-700 transition">
                                Voir les Détails
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <!-- Message si aucun résultat -->
                <p class="text-center text-gray-600 mt-4">Aucun résultat trouvé pour votre recherche.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Pied de page -->
    <footer class="bg-teal-500 text-white py-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 DARLY TCHATCHOUANG pour E_Bibliothèque. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
