<div class="page-head">
    <h1>Sprints</h1>
    <a class="btn btn--primary" href="index.php?c=sprint&a=nuevo">+ Nuevo sprint</a>
</div>

<div class="card">
    <input type="search" class="input input--search" placeholder="Buscar sprint…"
           data-filtro="tabla-sprints" aria-label="Buscar sprints">

    <div class="table-wrap">
    <table class="table" id="tabla-sprints">
        <thead>
            <tr><th>Sprint</th><th>Periodo</th><th>Historias</th><th>Puntos</th><th style="width:20%">Avance</th><th class="right">Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach ($sprints as $s): $p = porcentaje($s['puntos_finalizados'], $s['puntos']); ?>
            <tr>
                <td><strong><?= e($s['nombre']) ?></strong><br><span class="muted"><?= e($s['objetivo']) ?></span></td>
                <td><?= e($s['fecha_inicio']) ?> → <?= e($s['fecha_fin']) ?></td>
                <td><a href="index.php?c=historia&sprint=<?= (int)$s['id'] ?>"><?= (int)$s['historias'] ?></a></td>
                <td><?= (int)$s['puntos_finalizados'] ?> / <?= (int)$s['puntos'] ?></td>
                <td><div class="bar"><span style="width:<?= $p ?>%"></span></div> <small><?= $p ?>%</small></td>
                <td class="right actions">
                    <a class="btn btn--small" href="index.php?c=informe&sprint=<?= (int)$s['id'] ?>">Informe</a>
                    <a class="btn btn--small btn--ghost" href="index.php?c=sprint&a=editar&id=<?= (int)$s['id'] ?>">Editar</a>
                    <form method="post" action="index.php?c=sprint&a=eliminar"
                          data-confirmar="¿Eliminar <?= e($s['nombre']) ?> y sus <?= (int)$s['historias'] ?> historias?">
                        <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                        <button class="btn btn--small btn--danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$sprints): ?>
            <tr><td colspan="6" class="muted">No hay sprints registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
