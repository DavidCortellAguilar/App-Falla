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
function addOptionRow() {
    const wrapper = document.getElementById('optionsRows');
    if (!wrapper) return;

    const row = document.createElement('div');
    row.className = 'option-row';
    row.innerHTML = `
        <input type="hidden" name="opcion_id[]" value="">
        <input class="form-control" name="opcion_nombre[]" placeholder="Opción. Ej. Paella">
        <input class="form-control" name="opcion_plazas[]" type="number" placeholder="Plazas">
        <input class="form-control" name="opcion_descripcion[]" placeholder="Descripción opcional">
        <button class="btn btn-outline-danger" type="button" onclick="removeOptionRow(this)">×</button>
    `;
    wrapper.appendChild(row);
}

function removeOptionRow(button) {
    const row = button.closest('.option-row');
    if (!row) return;
    row.remove();
}
