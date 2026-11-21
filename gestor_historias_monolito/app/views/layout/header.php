<?php
$cfg    = require __DIR__ . '/../../../config/config.php';
$activo = $_GET['c'] ?? 'home';
$flash  = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$menu = [
    'home'     => 'Inicio',
    'sprint'   => 'Sprints',
    'historia' => 'Historias',
    'informe'  => 'Informes',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Inicio') ?> · <?= e($cfg['app']['nombre']) ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar__inner">
        <a class="brand" href="index.php"><span class="brand__dot"></span><?= e($cfg['app']['nombre']) ?></a>
        <nav class="nav">
            <?php foreach ($menu as $clave => $texto): ?>
                <a href="index.php?c=<?= $clave ?>" class="<?= $activo === $clave ? 'is-active' : '' ?>"><?= $texto ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
<main class="container">
    <?php if ($flash): ?>
        <div class="alert alert--<?= e($flash['tipo']) ?>" role="status"><?= e($flash['mensaje']) ?></div>
    <?php endif; ?>
