<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register libs
require __DIR__ . '/../src/lib/data.php';
require __DIR__ . '/../src/lib/NumberToLetterConverter.php';
//
require __DIR__ . '/../src/lib/base.php';
require __DIR__ . '/../src/lib/comprobante.php';
require __DIR__ . '/../src/lib/nota.php';
require __DIR__ . '/../src/lib/baja.php';
//
require __DIR__ . '/../src/lib/factura.php';
require __DIR__ . '/../src/lib/boleta.php';
require __DIR__ . '/../src/lib/resumen_diario.php';
require __DIR__ . '/../src/lib/pdf.php';
require __DIR__ . '/../src/lib/see_util.php';
require __DIR__ . '/../src/lib/see.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
