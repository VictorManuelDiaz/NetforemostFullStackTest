<?php

namespace App\Providers;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\PropertyController;
use App\Services\PropertyService;

class RouteServiceProvider
{
    public function __construct(App $app)
    {
        $this->registerRoutes($app);
    }

    protected function registerRoutes(App $app)
    {
        $app->group('/api', function (RouteCollectorProxy $group) use ($app) {
            $group->post('/import-csv', function (Request $request, Response $response, $args) use ($app) {
                $controller = new PropertyController($app->getContainer()->get(PropertyService::class));
                return $controller->importFromCSVAction($request, $response);
            });

            $group->get('/properties', function (Request $request, Response $response, $args) use ($app) {
                $controller = new PropertyController($app->getContainer()->get(PropertyService::class));
                return $controller->filterPropertiesAction($request, $response);
            });

            $group->get('/average-price', function (Request $request, Response $response, $args) use ($app) {
                $controller = new PropertyController($app->getContainer()->get(PropertyService::class));
                return $controller->averagePriceAction($request, $response);
            });

            $group->get('/generate-report', function (Request $request, Response $response, $args) use ($app) {
                $controller = new PropertyController($app->getContainer()->get(PropertyService::class));
                return $controller->generateReportAction($request, $response);
            });
        });
    }
}

