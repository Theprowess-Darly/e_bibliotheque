//   <!-- Script JavaScript pour gérer l'affichage du header -->
// Simulation d'un utilisateur connecté
let userLoggedIn = false; // Remplacez par une variable qui indique si l'utilisateur est connecté

if (userLoggedIn) {
    // Si l'utilisateur est connecté, afficher le header avec la recherche
    document.getElementById("header").innerHTML = `
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold">Bibliothèque</div>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="profile.html" class="hover:text-orange-600">Mon Profil</a></li>
                    <li><a href="logout.php" class="hover:text-orange-600">Se Déconnecter</a></li>
                </ul>
            </nav>
        </div>
    `;
    document.getElementById("search-section").classList.remove("hidden");
} else {
    // Si l'utilisateur n'est pas connecté, masquer la section recherche
    document.getElementById("search-section").classList.add("hidden");
}