<?php
/**
 * Conexión a la base de datos (patrón Singleton).
 * Una sola conexión PDO compartida por todos los modelos.
 */
class Database
{
    private static ?PDO $conexion = null;

    public static function get(): PDO
    {
        if (self::$conexion === null) {
            $cfg = require __DIR__ . '/../config/config.php';
            $db  = $cfg['db'];
            $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
            self::$conexion = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$conexion;
    }
}
