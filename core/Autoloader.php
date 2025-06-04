<?php
class Autoloader {
    private static $directories = [
        __DIR__ . '/../core/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/controllers/'
    ];

    public static function register() {
        spl_autoload_register([__CLASS__, 'load']);
    }

    public static function load($className) {
        foreach (self::$directories as $directory) {
            $file = $directory . $className . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}