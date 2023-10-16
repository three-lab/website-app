<?php

namespace System\Components;

use PDO;
use System\Utils\DataModel;
use System\Utils\QueryModel;

class Model
{
    use DataModel, QueryModel;

    protected string $table = '';
    protected string $primaryKey = 'id';

    private static PDO $_connection;

    public static function boot()
    {
        $dbhost = config('database.host');
        $database = config('database.name');

        $pdo = new PDO(
            dsn: "mysql:host={$dbhost};dbname={$database}",
            username: config('database.username'),
            password: config('database.password')
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        static::$_connection = $pdo;
    }

    public function conn(): PDO
    {
        return static::$_connection;
    }
}
