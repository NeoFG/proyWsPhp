<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Configurar el base path
$app->setBasePath("/inventario_nuevo/public");

// Middleware de errores
$app->addErrorMiddleware(true, true, true);

// Cargar las rutas
(require __DIR__ . '/../src/Routes.php')($app);

$app->run();
