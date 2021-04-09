<?php 
    require __DIR__ . '/vendor/autoload.php';
    $config = require __DIR__ . '/Core/config.php';

    (new Core\Controller\Api($config))->init();
