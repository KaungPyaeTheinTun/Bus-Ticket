<?php

// require_once "config/config.php";
// require_once "helpers/url_helper.php";
// require_once "helpers/message_helper.php";
// require_once "helpers/UserValidator.php";

// // require_once "libraries/Database.php";
// // require_once "libraries/Core.php";
// // require_once "libraries/Controller.php";

// spl_autoload_register(function ($class) {
//     require_once 'libraries/' . $class . '.php';
// });


require_once "config/config.php";
require_once "helpers/url_helper.php";
require_once "helpers/message_helper.php";
require_once "helpers/UserValidator.php";

spl_autoload_register(function ($class) {
    // Adjust the path based on where this file (class_loader.php) lives:
    $paths = [
        'libraries/' . $class . '.php',
        'models/' . $class . '.php',
        'controllers/' . $class . '.php' // optional, rarely needed
    ];

    foreach ($paths as $file) {
        if (file_exists(__DIR__ . '/' . $file)) {
            require_once __DIR__ . '/' . $file;
            return;
        }
    }

    // Optional: throw error if class not found
    // throw new Exception("Unable to load class: $class");
});
