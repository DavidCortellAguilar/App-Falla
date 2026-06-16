function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    sidebar.classList.toggle('is-open');
    backdrop.classList.toggle('is-open');
}

document.addEventListener('DOMContentLoaded', function () {
    const backdrop = document.getElementById('sidebarBackdrop');

    if (backdrop) {
        backdrop.addEventListener('click', function () {
            document.getElementById('sidebar').classList.remove('is-open');
            backdrop.classList.remove('is-open');
        });
    }
});

// La lógica de bloques/opciones está dentro de actos.php.
// Se deja fuera de app.js para evitar que los botones ejecuten la acción dos veces.
