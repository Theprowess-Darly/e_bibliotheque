<?php
include '../results/db_connection.php'; // Connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: ../../../index.php"); // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté
    exit();
}



// Vérifier si l'identifiant du livre est passé en paramètre GET
if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Récupérer les détails du livre
    $sql = "SELECT * FROM livres WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        $error = "Livre introuvable.";
    }
} else {
    $error = "Aucun livre sélectionné.";
}



// Gestion des emprunts
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['borrow_book'])) {
        // Vérifier si l'utilisateur a déjà emprunté ce livre
        $check_sql = "SELECT * FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $book_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
    
        if ($check_result->num_rows > 0) {
            $error = "Vous avez déjà emprunté ce livre.";
        } else {
            if ($book['nombre_exemplaire'] > 0) {
                // Diminuer le nombre d'exemplaires
                $update_sql = "UPDATE livres SET nombre_exemplaire = nombre_exemplaire - 1 WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $book_id);
                $update_stmt->execute();
    
                // Ajouter à la liste de lecture
                $wishlist_sql = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (?, ?, NOW())";
                $wishlist_stmt = $conn->prepare($wishlist_sql);
                $wishlist_stmt->bind_param("ii", $book_id, $user_id);
    
                if ($wishlist_stmt->execute()) {
                    $success = "Livre emprunté avec succès.";
                } else {
                    $error = "Erreur lors de l'emprunt.";
                }
            } else {
                $error = "Ce livre n'est pas disponible pour l'instant.";
            }
        }
    }

    if (isset($_POST['return_book'])) {
        // Vérifier si le livre est dans la liste de lecture
        $check_sql = "SELECT * FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $book_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Supprimer le livre de la liste de lecture
            $delete_sql = "DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $book_id, $user_id);
            $delete_stmt->execute();

            // Augmenter le nombre d'exemplaires
            $update_sql = "UPDATE livres SET nombre_exemplaire = nombre_exemplaire + 1 WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $book_id);
            $update_stmt->execute();

            $success = "Livre rendu avec succès.";
        } else {
            $error = "Vous n'avez pas emprunté ce livre.";
        }
        if (isset($_POST['remove_book'])) {
            // Supprimer un livre de la liste de lecture
            $remove_book_id = intval($_POST['book_id']);
            $delete_sql = "DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $remove_book_id, $user_id);
        
            if ($delete_stmt->execute()) {
                $success = "Livre retiré de votre liste de lecture.";
            } else {
                $error = "Erreur lors de la suppression du livre.";
            }
        }
        
    }

    if (isset($_POST['remove_book'])) {
        $remove_book_id = intval($_POST['book_id']);
        $delete_sql = "DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $remove_book_id, $user_id);
        if ($delete_stmt->execute()) {
            $success = "Livre retiré de votre liste de lecture.";
        } else {
            $error = "Erreur lors de la suppression du livre.";
        }
    }
}

// Récupérer la liste de lecture de l'utilisateur
$list_sql = "SELECT livres.id AS id_livre, livres.titre, livres.auteur FROM liste_lecture 
             INNER JOIN livres ON liste_lecture.id_livre = livres.id 
             WHERE liste_lecture.id_lecteur = ?";

$list_stmt = $conn->prepare($list_sql);
$list_stmt->bind_param("i", $user_id);
$list_stmt->execute();
$list_result = $list_stmt->get_result();

// Vérifier si l'utilisateur a déjà emprunté ce livre- Validation côté frontend
$already_borrowed = false;
$check_sql = "SELECT * FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $book_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $already_borrowed = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">
    <header class="bg-teal-500 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">E_Bibliothèque</h1>
            <a href="../../../index.php" class="hover:text-orange-600">Accueil</a>
        </div>
    </header>
    <main class="container mx-auto py-10 flex space-x-6">
        <section class="w-1/2 p-4 border rounded-lg shadow">
            <!-- Détails du livre -->
            <?php if (isset($book)): ?>
                <h2 class="text-3xl font-bold text-teal-600 mb-4"><?= htmlspecialchars($book['titre']); ?></h2>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($book['auteur']); ?></p>
                <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($book['description'])); ?></p>
                <p><strong>Exemplaires disponibles :</strong> <?= htmlspecialchars($book['nombre_exemplaire']); ?></p>

                <form action="" method="POST" class="mt-4">
                    <?php if (!$already_borrowed): ?>
                        <button type="submit" name="borrow_book" class="p-2 bg-orange-600 text-white rounded hover:bg-orange-700">
                            Emprunter
                        </button>
                    <?php else: ?>
                        <p class="text-green-500">Vous avez emprunté ce livre.</p>
                        <button type="submit" name="return_book" class="p-2 bg-teal-600 text-white rounded hover:bg-teal-700">
                            Rendre
                        </button>
                    <?php endif; ?>
                </form>

                <?php if (isset($success)): ?>
                    <p class="text-green-500 mt-4"><?= $success; ?></p>
                <?php elseif (isset($error)): ?>
                    <p class="text-red-500 mt-4"><?= $error; ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-red-500 font-semibold"><?= $error; ?></p>
                <a href="../../../index.php" class="inline-block mt-4 px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600">Retour à l'accueil</a>
            <?php endif; ?>
        </section>

        <!-- Colonne de droite : Liste de lecture -->
        <section class="w-1/2 p-4 border rounded-lg shadow">
            <h2 class="text-2xl font-semibold text-teal-500 mb-4">Ma Liste de Lecture</h2>

            <?php
            // Récupérer la liste de lecture de l'utilisateur
            // $wishlist_sql = "SELECT liste_lecture.id AS id_liste, 
            // livres.titre AS titre, livres.auteur AS auteur, livres.id AS id_livre FROM liste_lecture JOIN livres ON liste_lecture.id_livre = livres.id WHERE liste_lecture.id_lecteur = ?";
            // $wishlist_stmt = $conn->prepare($wishlist_sql);
            // $wishlist_stmt->bind_param("ii", $book_id, $user_id);
            // $wishlist_stmt->execute();
            // $list_result = $wishlist_stmt->get_result();

            // Gestion de la suppression d'un livre de la liste de lecture
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_book'])) {
                $book_id_to_remove = intval($_POST['book_id']);
                $delete_sql = "DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("ii", $book_id_to_remove, $user_id);
                if ($delete_stmt->execute()) {
                    echo "<p class='text-green-500'>Livre retiré de la liste de lecture avec succès.</p>";
                    // Actualiser la page pour refléter les changements
                    header("Location: details.php?id=" . $book_id);
                    exit();
                } else {
                    echo "<p class='text-red-500'>Erreur lors de la suppression du livre.</p>";
                }
            }
            ?>

            <?php if ($list_result->num_rows > 0): ?>
                <ul class="space-y-4">
                    <?php while ($row = $list_result->fetch_assoc()): ?>
                        <li class="p-4 border rounded-lg shadow">
                            <h3 class="text-xl font-bold text-teal-600"><?= htmlspecialchars($row['titre']); ?></h3>
                            <p><strong>Auteur :</strong> <?= htmlspecialchars($row['auteur']); ?></p>
                            <form action="details.php?id=<?= htmlspecialchars($book_id); ?>" method="POST" class="mt-4">
                                <input type="hidden" name="book_id" value="<?= $row['id_livre']; ?>">
                                <button type="submit" name="remove_book" class="p-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Retirer
                                </button>
                            </form>
                        </li>
                    <?php endwhile; ?>

                </ul>
            <?php else: ?>
                <p class="text-gray-700">Votre liste de lecture est vide.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
