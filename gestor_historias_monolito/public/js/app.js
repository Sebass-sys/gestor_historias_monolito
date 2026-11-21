/* ScrumBoard UB · comportamiento del front end (JavaScript puro) */
document.addEventListener('DOMContentLoaded', function () {

    // 1. Confirmación antes de enviar formularios marcados con data-confirmar
    document.querySelectorAll('form[data-confirmar]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            if (!window.confirm(form.dataset.confirmar)) {
                ev.preventDefault();
            }
        });
    });

    // 2. Buscador en vivo para tablas (input con data-filtro="id-de-la-tabla")
    document.querySelectorAll('input[data-filtro]').forEach(function (campo) {
        var tabla = document.getElementById(campo.dataset.filtro);
        if (!tabla) { return; }
        campo.addEventListener('input', function () {
            var texto = campo.value.trim().toLowerCase();
            tabla.querySelectorAll('tbody tr').forEach(function (fila) {
                fila.style.display = fila.textContent.toLowerCase().indexOf(texto) === -1 ? 'none' : '';
            });
        });
        // Evita que Enter en el buscador envíe el formulario de filtros
        campo.addEventListener('keydown', function (ev) { if (ev.key === 'Enter') { ev.preventDefault(); } });
    });

    // 3. Los filtros con data-autosubmit envían el formulario al cambiar
    document.querySelectorAll('select[data-autosubmit]').forEach(function (sel) {
        sel.addEventListener('change', function () { sel.form.submit(); });
    });

    // 4. Las alertas desaparecen solas a los 5 segundos
    document.querySelectorAll('.alert').forEach(function (a) {
        setTimeout(function () { a.style.display = 'none'; }, 5000);
    });
});
