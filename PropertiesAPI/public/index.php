<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use App\Providers\RouteServiceProvider;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    'settings' => [
        'db' => [
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD']
        ]
    ],
    PDO::class => function (\Psr\Container\ContainerInterface $c) {
        $dbSettings = $c->get('settings')['db'];
        $pdo = new PDO(
            'mysql:host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['database'],
            $dbSettings['username'],
            $dbSettings['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    },
]);


$container = $containerBuilder->build();

$app = AppFactory::createFromContainer($container);

new RouteServiceProvider($app);

$app->addErrorMiddleware(true, true, true);

$app->run();
