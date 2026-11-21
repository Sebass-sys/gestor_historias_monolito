<?php
$etiquetaAvanzar = ['nueva' => 'Iniciar', 'activa' => 'Finalizar', 'impedimento' => 'Reanudar'];
?>
<div class="page-head">
    <h1>Historias de usuario</h1>
    <a class="btn btn--primary" href="index.php?c=historia&a=nuevo<?= $sprintId ? '&sprint=' . $sprintId : '' ?>">+ Nueva historia</a>
</div>

<div class="card">
    <form class="filtros" method="get" action="index.php">
        <input type="hidden" name="c" value="historia">
        <select class="input" name="sprint" aria-label="Filtrar por sprint" data-autosubmit>
            <option value="0">Todos los sprints</option>
            <?php foreach ($sprints as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= $sprintId === (int)$s['id'] ? 'selected' : '' ?>><?= e($s['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <select class="input" name="estado" aria-label="Filtrar por estado" data-autosubmit>
            <option value="">Todos los estados</option>
            <?php foreach (Historia::ESTADOS as $est): ?>
                <option value="<?= e($est) ?>" <?= $estado === $est ? 'selected' : '' ?>><?= e(ucfirst($est)) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="search" class="input input--search" placeholder="Buscar por título, responsable…"
               data-filtro="tabla-historias" aria-label="Buscar historias">
    </form>

    <div class="table-wrap">
    <table class="table" id="tabla-historias">
        <thead>
            <tr><th>Historia</th><th>Sprint</th><th>Responsable</th><th>Prioridad</th><th>Pts</th><th>Estado</th><th class="right">Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach ($historias as $h): ?>
            <tr>
                <td><strong><?= e($h['titulo']) ?></strong><br><span class="muted"><?= e(mb_strimwidth($h['descripcion'], 0, 70, '…')) ?></span></td>
                <td><?= e($h['sprint']) ?></td>
                <td><?= e($h['responsable']) ?></td>
                <td><span class="badge badge--<?= e($h['prioridad']) ?>"><?= e($h['prioridad']) ?></span></td>
                <td><?= (int)$h['puntos'] ?></td>
                <td><span class="badge badge--<?= e($h['estado']) ?>"><?= e($h['estado']) ?></span></td>
                <td class="right actions">
                    <?php if (isset($etiquetaAvanzar[$h['estado']])): ?>
                        <form method="post" action="index.php?c=historia&a=avanzar">
                            <input type="hidden" name="id" value="<?= (int)$h['id'] ?>">
                            <button class="btn btn--small" type="submit"><?= $etiquetaAvanzar[$h['estado']] ?></button>
                        </form>
                    <?php endif; ?>
                    <a class="btn btn--small btn--ghost" href="index.php?c=historia&a=editar&id=<?= (int)$h['id'] ?>">Editar</a>
                    <form method="post" action="index.php?c=historia&a=eliminar" data-confirmar="¿Eliminar la historia «<?= e($h['titulo']) ?>»?">
                        <input type="hidden" name="id" value="<?= (int)$h['id'] ?>">
                        <button class="btn btn--small btn--danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$historias): ?>
            <tr><td colspan="7" class="muted">No hay historias con ese filtro.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
