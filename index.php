<?php
/**
 * Front Controller: único punto de entrada de la aplicación monolítica.
 * Uso: index.php?c=<controlador>&a=<accion>
 */
session_start();

// Autocarga de clases desde las carpetas del monolito
spl_autoload_register(function (string $clase) {
    foreach (['core', 'app/models', 'app/controllers'] as $carpeta) {
        $archivo = __DIR__ . "/{$carpeta}/{$clase}.php";
        if (is_file($archivo)) {
            require $archivo;
            return;
        }
    }
});

// Escapa la salida en las vistas (previene XSS)
function e($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

// Porcentaje entero (0-100) seguro ante división por cero
function porcentaje($parte, $total): int
{
    return $total > 0 ? (int)round($parte * 100 / $total) : 0;
}

$controlador = $_GET['c'] ?? 'home';
$accion      = $_GET['a'] ?? 'index';

$permitidos = ['home', 'sprint', 'historia', 'informe'];
if (!in_array($controlador, $permitidos, true)) {
    $controlador = 'home';
}

$clase = ucfirst($controlador) . 'Controller';
$obj   = new $clase();

if (!is_callable([$obj, $accion]) || str_starts_with($accion, '_')) {
    http_response_code(404);
    exit('Acción no encontrada');
}

try {
    $obj->$accion();
} catch (PDOException $ex) {
    http_response_code(500);
    echo '<h3>Error de base de datos</h3><p>Verifica que importaste database.sql y la configuración en config/config.php.</p>';
}
