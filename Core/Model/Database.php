<?php
    namespace Core\Model;

    use \Illuminate\Database\Capsule\Manager as Capsule;

    class Database
    {
        public static $capsule;

        public static function connectEloquent() {
            self::$capsule = new Capsule;

            self::$capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => DB_HOST,
                'database'  => DB_NAME,
                'username'  => DB_USERNAME,
                'password'  => DB_PASSWORD,
                'charset'   => DB_CHARSET,
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);

            self::$capsule->setAsGlobal();

            self::$capsule->bootEloquent();
        }
    }