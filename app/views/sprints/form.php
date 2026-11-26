<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="card form" method="post" action="index.php?c=sprint&a=<?= e($accion) ?>" novalidate>
    <?php if (!empty($sprint['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$sprint['id'] ?>">
    <?php endif; ?>

    <div class="grid">
        <div class="field">
            <label for="nombre">Nombre</label>
            <input class="input <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" id="nombre" name="nombre"
                   placeholder="Sprint 3" value="<?= e($sprint['nombre']) ?>" required>
            <?php if (isset($errores['nombre'])): ?><small class="error"><?= e($errores['nombre']) ?></small><?php endif; ?>
        </div>
        <div class="field field--wide">
            <label for="objetivo">Objetivo del sprint</label>
            <input class="input <?= isset($errores['objetivo']) ? 'is-invalid' : '' ?>" id="objetivo" name="objetivo"
                   maxlength="255" placeholder="¿Qué se quiere lograr en este sprint?" value="<?= e($sprint['objetivo']) ?>">
            <?php if (isset($errores['objetivo'])): ?><small class="error"><?= e($errores['objetivo']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="fecha_inicio">Fecha de inicio</label>
            <input class="input <?= isset($errores['fecha_inicio']) ? 'is-invalid' : '' ?>" id="fecha_inicio" name="fecha_inicio"
                   type="date" value="<?= e($sprint['fecha_inicio']) ?>" required>
            <?php if (isset($errores['fecha_inicio'])): ?><small class="error"><?= e($errores['fecha_inicio']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="fecha_fin">Fecha de fin</label>
            <input class="input <?= isset($errores['fecha_fin']) ? 'is-invalid' : '' ?>" id="fecha_fin" name="fecha_fin"
                   type="date" value="<?= e($sprint['fecha_fin']) ?>" required>
            <?php if (isset($errores['fecha_fin'])): ?><small class="error"><?= e($errores['fecha_fin']) ?></small><?php endif; ?>
        </div>
    </div>

    <div class="form__actions">
        <button class="btn btn--primary" type="submit">Guardar</button>
        <a class="btn btn--ghost" href="index.php?c=sprint">Cancelar</a>
    </div>
</form>
