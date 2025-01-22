document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleButton');
    const menuIcon = document.getElementById('menuIcon');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');

        // Ajuster la rotation de l'icône
        if (sidebar.classList.contains('collapsed')) {
            menuIcon.style.transform = 'rotate(180deg)';
        } else {
            menuIcon.style.transform = 'rotate(0deg)';
        }
    });

    // Gestion responsive pour les appareils mobiles
    function handleResize() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
        }
    }

    // Écouter les changements de taille de fenêtre
    window.addEventListener('resize', handleResize);

    // Vérifier la taille initiale
    handleResize();
});

document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.querySelector(".usines-toggle");
    const dropdownMenus = document.querySelectorAll(".dropdown");

    if (toggleButton) {
        toggleButton.addEventListener("click", () => {
            dropdownMenus.forEach(menu => {
                menu.classList.toggle("visible");
            });
        });
    }
});