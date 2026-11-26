<div class="page-head">
    <h1>Panel principal</h1>
    <div class="actions">
        <a class="btn btn--ghost" href="index.php?c=sprint&a=nuevo">+ Nuevo sprint</a>
        <a class="btn btn--primary" href="index.php?c=historia&a=nuevo">+ Nueva historia</a>
    </div>
</div>

<section class="stats">
    <article class="stat"><span class="stat__n"><?= (int)(new Sprint())->total() ?></span><span class="stat__l">Sprints</span></article>
    <article class="stat"><span class="stat__n"><?= $totales['historias'] ?></span><span class="stat__l">Historias</span></article>
    <article class="stat"><span class="stat__n"><?= $totales['puntos'] ?></span><span class="stat__l">Puntos totales</span></article>
    <article class="stat"><span class="stat__n"><?= $avance ?>%</span><span class="stat__l">Avance (puntos finalizados)</span></article>
    <article class="stat"><span class="stat__n"><?= $totales['impedimentos'] ?></span><span class="stat__l">Con impedimento</span></article>
</section>

<section class="card">
    <h2>Avance por sprint</h2>
    <?php if (!$sprints): ?>
        <p class="muted">Aún no hay sprints. Crea el primero para empezar.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Sprint</th><th>Periodo</th><th>Historias</th><th>Puntos</th><th style="width:28%">Avance</th></tr></thead>
            <tbody>
            <?php foreach ($sprints as $s): $p = porcentaje($s['puntos_finalizados'], $s['puntos']); ?>
                <tr>
                    <td><strong><?= e($s['nombre']) ?></strong><br><span class="muted"><?= e($s['objetivo']) ?></span></td>
                    <td><?= e($s['fecha_inicio']) ?> → <?= e($s['fecha_fin']) ?></td>
                    <td><?= (int)$s['historias'] ?></td>
                    <td><?= (int)$s['puntos_finalizados'] ?> / <?= (int)$s['puntos'] ?></td>
                    <td><div class="bar" title="<?= $p ?>%"><span style="width:<?= $p ?>%"></span></div> <small><?= $p ?>%</small></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</section>

<section class="card">
    <h2>Historias con impedimento</h2>
    <?php if (!$impedimentos): ?>
        <p class="muted">No hay historias con impedimento. 🎉</p>
    <?php else: ?>
        <ul class="lista">
        <?php foreach ($impedimentos as $h): ?>
            <li>
                <strong><?= e($h['titulo']) ?></strong>
                <span class="muted">· <?= e($h['sprint']) ?> · <?= e($h['responsable']) ?></span>
                <a class="btn btn--small btn--ghost" href="index.php?c=historia&a=editar&id=<?= (int)$h['id'] ?>">Ver</a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
