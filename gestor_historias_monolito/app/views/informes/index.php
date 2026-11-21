<div class="page-head">
    <h1>Informes</h1>
    <form method="get" action="index.php" class="filtros">
        <input type="hidden" name="c" value="informe">
        <select class="input" name="sprint" aria-label="Sprint del informe" data-autosubmit>
            <option value="0">Todos los sprints</option>
            <?php foreach ($sprints as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= $sprintId === (int)$s['id'] ? 'selected' : '' ?>><?= e($s['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<section class="stats">
    <article class="stat"><span class="stat__n"><?= $resumen['historias'] ?></span><span class="stat__l">Historias</span></article>
    <article class="stat"><span class="stat__n"><?= $resumen['puntos'] ?></span><span class="stat__l">Puntos totales</span></article>
    <article class="stat"><span class="stat__n"><?= $resumen['puntos_finalizados'] ?></span><span class="stat__l">Puntos finalizados</span></article>
    <article class="stat"><span class="stat__n"><?= $resumen['avance'] ?>%</span><span class="stat__l">Avance</span></article>
</section>

<section class="card">
    <h2>Historias por estado</h2>
    <div class="table-wrap">
    <table class="table">
        <thead><tr><th>Estado</th><th>Historias</th><th>Puntos</th><th style="width:40%">Proporción</th></tr></thead>
        <tbody>
        <?php foreach ($porEstado as $est => $v): $p = porcentaje($v['historias'], $resumen['historias']); ?>
            <tr>
                <td><span class="badge badge--<?= e($est) ?>"><?= e($est) ?></span></td>
                <td><?= $v['historias'] ?></td>
                <td><?= $v['puntos'] ?></td>
                <td><div class="bar bar--<?= e($est) ?>"><span style="width:<?= $p ?>%"></span></div> <small><?= $p ?>%</small></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</section>

<section class="card">
    <h2>Carga de trabajo por responsable</h2>
    <?php if (!$porResponsable): ?>
        <p class="muted">No hay historias para mostrar.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Responsable</th><th>Total</th><th>Nuevas</th><th>Activas</th><th>Finalizadas</th><th>Impedimentos</th><th>Puntos</th></tr>
            </thead>
            <tbody>
            <?php foreach ($porResponsable as $r): ?>
                <tr>
                    <td><strong><?= e($r['responsable']) ?></strong></td>
                    <td><?= (int)$r['total'] ?></td>
                    <td><?= (int)$r['nuevas'] ?></td>
                    <td><?= (int)$r['activas'] ?></td>
                    <td><?= (int)$r['finalizadas'] ?></td>
                    <td><?= (int)$r['impedimentos'] ?></td>
                    <td><?= (int)$r['puntos_finalizados'] ?> / <?= (int)$r['puntos'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</section>
