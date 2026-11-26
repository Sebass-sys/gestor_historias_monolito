<?php /* Pantalla de confirmación del lado del servidor (reemplaza al cuadro confirm() de JavaScript). */ ?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<section class="card confirmar">
    <p><?= e($mensaje) ?></p>
    <p class="muted">Esta acción no se puede deshacer.</p>
    <form method="post" action="<?= e($accion) ?>" class="form__actions">
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <button class="btn btn--danger" type="submit">Sí, eliminar</button>
        <a class="btn btn--ghost" href="<?= e($volver) ?>">Cancelar</a>
    </form>
</section>
