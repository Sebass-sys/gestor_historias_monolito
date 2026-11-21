<div class="page-head"><h1><?= htmlspecialchars($titulo) ?></h1></div>

<form class="card form" method="post" action="index.php?c=historia&a=<?= htmlspecialchars($accion) ?>" novalidate>
    <?php if (!empty($historia['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$historia['id'] ?>">
    <?php endif; ?>

    <div class="grid">
        <div class="field field--wide">
            <label for="titulo">Título</label>
            <input class="input <?= isset($errores['titulo']) ? 'is-invalid' : '' ?>" id="titulo" name="titulo"
                   maxlength="150" value="<?= htmlspecialchars($historia['titulo']) ?>" required>
            <?php if (isset($errores['titulo'])): ?><small class="error"><?=htmlspecialchars($errores['titulo']) ?></small><?php endif; ?>
        </div>
        <div class="field field--wide">
            <label for="descripcion">Descripción</label>
            <textarea class="input <?= isset($errores['descripcion']) ? 'is-invalid' : '' ?>" id="descripcion" name="descripcion" rows="3"
                      placeholder="Como [rol] quiero [acción] para [beneficio]" required><?= htmlspecialchars($historia['descripcion']) ?></textarea>
            <?php if (isset($errores['descripcion'])): ?><small class="error"><?= htmlspecialchars($errores['descripcion']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="responsable">Responsable</label>
            <input class="input <?= isset($errores['responsable']) ? 'is-invalid' : '' ?>" id="responsable" name="responsable"
                   value="<?= htmlspecialchars($historia['responsable']) ?>" required>
            <?php if (isset($errores['responsable'])): ?><small class="error"><?= htmlspecialchars($errores['responsable']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="sprint_id">Sprint</label>
            <select class="input <?= isset($errores['sprint_id']) ? 'is-invalid' : '' ?>" id="sprint_id" name="sprint_id" required>
                <option value="">— Selecciona —</option>
                <?php foreach ($sprints as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= (string)$historia['sprint_id'] === (string)$s['id'] ? 'selected' : '' ?>><?= e($s['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['sprint_id'])): ?><small class="error"><?= htmlspecialchars($errores['sprint_id']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="prioridad">Prioridad</label>
            <select class="input" id="prioridad" name="prioridad">
                <?php foreach (Historia::PRIORIDADES as $p): ?>
                    <option value="<?= htmlspecialchars($p) ?>" <?= $historia['prioridad'] === $p ? 'selected' : '' ?>><?= e(ucfirst($p)) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['prioridad'])): ?><small class="error"><?= htmlspecialchars($errores['prioridad']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="estado">Estado</label>
            <select class="input" id="estado" name="estado">
                <?php foreach (Historia::ESTADOS as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= $historia['estado'] === $s ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($s)) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['estado'])): ?><small class="error"><?= htmlspecialchars($errores['estado']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="puntos">Puntos de historia</label>
            <select class="input <?= isset($errores['puntos']) ? 'is-invalid' : '' ?>" id="puntos" name="puntos">
                <?php foreach (Historia::PUNTOS as $p): ?>
                    <option value="<?= $p ?>" <?= (int)$historia['puntos'] === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['puntos'])): ?><small class="error"><?= htmlspecialchars($errores['puntos']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="fecha_creacion">Fecha de creación</label>
            <input class="input <?= isset($errores['fecha_creacion']) ? 'is-invalid' : '' ?>" id="fecha_creacion" name="fecha_creacion"
                   type="date" value="<?= htmlspecialchars($historia['fecha_creacion']) ?>" required>
            <?php if (isset($errores['fecha_creacion'])): ?><small class="error"><?= htmlspecialchars($errores['fecha_creacion']) ?></small><?php endif; ?>
        </div>
    </div>
    <p class="muted">La fecha de finalización se registra sola cuando la historia pasa a «finalizada».</p>

    <div class="form__actions">
        <button class="btn btn--primary" type="submit">Guardar</button>
        <a class="btn btn--ghost" href="index.php?c=historia">Cancelar</a>
    </div>
</form>
