<?php

$autoloaders = [    
    __DIR__ . "/../autoload.php",
    __DIR__ . "/../vendor/autoload.php",
    __DIR__ . "/../../vendor/autoload.php",
    __DIR__ . "/../../../vendor/autoload.php",
    __DIR__ . "/../../../../vendor/autoload.php",
];
// Root from which to refer to src/. assists with bundling into a phar.
foreach ($autoloaders as $path) {
    if (file_exists($path)) {
        require_once($path);
        break;
    }
}
