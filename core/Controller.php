<?php
/**
 * Clase base de los controladores: renderizado de vistas,
 * redirecciones y mensajes flash.
 */
abstract class Controller
{
    protected function render(string $vista, array $datos = []): void
    {
        extract($datos);
        $archivo = __DIR__ . '/../app/views/' . $vista . '.php';
        if (!is_file($archivo)) {
            http_response_code(404);
            exit('Vista no encontrada: ' . htmlspecialchars($vista));
        }
        require __DIR__ . '/../app/views/layout/header.php';
        require $archivo;
        require __DIR__ . '/../app/views/layout/footer.php';
    }

    protected function redirect(string $controlador, string $accion = 'index'): void
    {
        header("Location: index.php?c={$controlador}&a={$accion}");
        exit;
    }

    protected function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }

    protected function post(string $campo): string
    {
        return trim((string)($_POST[$campo] ?? ''));
    }
}
