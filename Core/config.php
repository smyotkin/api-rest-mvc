<?php
    // require $_SERVER['DOCUMENT_ROOT'] . '/includes/conf.php';

    // Errors list
    
    require __DIR__ . '/errors.php';

    // Main

    define('APP_NAME', 'Api');
    define('CONTROLLER_DIR', '\\Api\\Controller\\');
    define('TOKEN_TABLE', 'TLogin_api_tokens');

    // Database

    define('DB_NAME', '');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
    define('DB_CHARSET', 'utf8');

    // Debug

    define('DEBUG_ACTIVE', 1);

    ini_set('display_errors', DEBUG_ACTIVE);
    ini_set('display_startup_errors', DEBUG_ACTIVE);
    error_reporting(DEBUG_ACTIVE == 1 ? E_ALL : 0);

    Flight::set('flight.handle_errors', false);