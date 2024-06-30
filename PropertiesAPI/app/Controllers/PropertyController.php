<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\PropertyService;

class PropertyController
{
    private $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }


    public function importFromCSVAction(Request $request, Response $response)
    {
        $requestData = json_decode(file_get_contents('php://input'), true);
        $csvFilePath = $requestData['csv_file'] ?? '';

        try {
            $this->propertyService->importFromCSV($csvFilePath);
            $responseData = ['message' => $csvFilePath];

            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $responseData = ['error' => $e->getMessage()];

            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function filterPropertiesAction(Request $request, Response $response)
    {
        $minPrice = $request->getQueryParams()['min_price'] ?? null;
        $maxPrice = $request->getQueryParams()['max_price'] ?? null;
        $bedrooms = $request->getQueryParams()['bedrooms'] ?? null;

        $properties = $this->propertyService->filterProperties($minPrice, $maxPrice, $bedrooms);

        $response->getBody()->write(json_encode(['properties' => $properties]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function averagePriceAction(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();
        $latitude = $queryParams['latitude'] ?? 0;
        $longitude = $queryParams['longitude'] ?? 0;
        $distance = $queryParams['distance'] ?? 10;

        $averagePricePerSqm = $this->propertyService->calculateAveragePricePerSqm($latitude, $longitude, $distance);


        $response->getBody()->write(json_encode(['average_price_per_sqm' => $averagePricePerSqm]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function generateReportAction(Request $request, Response $response)
    {
        $latitude = $request->getQueryParams()['latitude'] ?? null;
        $longitude = $request->getQueryParams()['longitude'] ?? null;
        $reportType = strtolower($request->getQueryParams()['report_type']) ?? null;

        $this->propertyService->generateReport($latitude, $longitude, $reportType);

        $response->getBody()->write(json_encode(['mesaage' => 'Report successfully generated!']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
?>
