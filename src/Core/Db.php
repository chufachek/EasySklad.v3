<?php
class Db
{
    private static $pdo;

    public static function connection()
    {
        if (self::$pdo) {
            return self::$pdo;
        }
        $config = include __DIR__ . '/../../config/config.php';
        $db = $config['db'];
        $dsn = 'mysql:host=' . $db['host'] . ';dbname=' . $db['name'] . ';charset=' . $db['charset'];
        self::$pdo = new PDO($dsn, $db['user'], $db['pass']);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return self::$pdo;
    }
}
