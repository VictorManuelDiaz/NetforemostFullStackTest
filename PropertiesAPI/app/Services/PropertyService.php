<?php

namespace App\Services;

use PDO;
use App\Services\ReportService;

class PropertyService
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function importFromCSV($csvFilePath)
    {
        if (!file_exists($csvFilePath) || !is_readable($csvFilePath)) {
            throw new \Exception("The CSV file does not exist or is not readable.");
        }

        $csv = fopen($csvFilePath, 'r');
        if (!$csv) {
            throw new \Exception("Could not open the CSV file.");
        }

        $header = fgetcsv($csv);


        while (($row = fgetcsv($csv)) !== false) {

            if (preg_match('/^(\d{4})\/(\d{1,2})\/(\w+)$/', $row[28], $matches)) {
                $year = $matches[1];
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $day = '01';
                $row[28] = "{$year}-{$month}-{$day}";
            }

            $date = new \DateTime($row[28]);

            $latitude = $row[0];
            $longitude = $row[1];
            $id = $row[2];
            $title =  mb_convert_encoding($this->cleanData($row[3]), 'UTF-8', 'ISO-8859-1');
            $advertiser =  mb_convert_encoding($this->cleanData($row[4]), 'UTF-8', 'ISO-8859-1');
            $description = mb_convert_encoding($this->cleanData($row[5]), 'UTF-8', 'ISO-8859-1');
            $isRenovated = $row[6] == 'FALSE' || $row[6] == '' ? 0 : 1;
            $phones = $row[7];
            $type = $this->cleanData($row[8]);
            $price = $row[9];
            $pricePerSquareMeter = $row[10];
            $address = mb_convert_encoding($this->cleanData($row[11]), 'UTF-8', 'ISO-8859-1');
            $province = $this->cleanData($row[12]);
            $city = $this->cleanData($row[13]);
            $squareMeters = $row[14];
            $bedrooms = $row[15] == '' ? 0 : $row[15];
            $bathrooms = intval($row[16]);
            $hasParking = $row[17] == 'FALSE' || $row[17] == '' ? 0 : 1;
            $isSecondHand = $row[18] == 'FALSE' || $row[18] == '' ? 0 : 1;
            $hasBuiltInCupboards = $row[19] == 'FALSE' || $row[19] == '' ? 0 : 1;
            $builtIn = $row[20] == '' ? null : $row[20];
            $isFurnished = $row[21] == 'FALSE' || $row[21] == '' ? 0 : 1;
            $individualHeating = $row[22];
            $energyCertification = $row[23];
            $floor = intval($row[24]);
            $isOutdoor = $row[25] == 'FALSE' || $row[25] == '' ? 0 : 1;
            $isIndoor = $row[26] == 'FALSE' || $row[26] == '' ? 0 : 1;
            $hasElevator = $row[27] == 'FALSE' || $row[27] == '' ? 0 : 1;
            $houseDate = $date->format('Y-m-d');
            $street =  mb_convert_encoding($this->cleanData($row[29]), 'UTF-8', 'ISO-8859-1');;
            $neighborhood = $row[30];
            $district = $row[31];
            $hasTerrace = $row[32] == 'FALSE' || $row[32] == '' ? 0 : 1;
            $hasStorageRoom = $row[33] == 'FALSE' || $row[33] == '' ? 0 : 1;
            $hasEquippedKitchen = $row[34] == 'FALSE' || $row[34] == '' ? 0 : 1;
            $hasAirConditioning = $row[35] == 'FALSE' || $row[35] == '' ? 0 : 1;
            $hasSwimmingPool = $row[36] == 'FALSE' || $row[36] == '' ? 0 : 1;
            $hasGarden = $row[37] == 'FALSE' || $row[37] == '' ? 0 : 1;
            $usefulSquareMeters = $row[38] == '' ? 0 : $row[38];
            $isAccessible = $row[39] == 'FALSE' || $row[39] == '' ? 0 : 1;
            $floorsNumber = $row[40] == '' ? 0 : $row[40];
            $hasPetsAllowed = $row[41] == 'FALSE' || $row[41] == '' ? 0 : 1;
            $hasBalcony = $row[42] == 'FALSE' || $row[42] == '' ? 0 : 1;

            $stmt = $this->db->prepare(
                "INSERT INTO tbl_house (
                    latitude,
                    longitude,
                    id,
                    title,
                    advertiser,
                    description,
                    is_renovated,
                    phones,
                    type,
                    price,
                    price_per_square_meter,
                    address,
                    province,
                    city,
                    square_meters,
                    bedrooms,
                    bathrooms,
                    has_parking,
                    is_second_hand,
                    has_built_in_cupboards,
                    built_in,
                    is_furnished,
                    individual_heating,
                    energy_certification,
                    floor,
                    is_outdoor,
                    is_indoor,
                    has_elevator,
                    house_date,
                    street,
                    neighborhood,
                    district,
                    has_terrace,
                    has_storage_room,
                    has_equipped_kitchen,
                    has_air_conditioning,
                    has_swimming_pool,
                    has_garden,
                    useful_square_meters,
                    is_accessible,
                    floors_number,
                    has_pets_allowed,
                    has_balcony
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?
                )"
            );

            $stmt->execute([
                $latitude,
                $longitude,
                $id,
                $title,
                $advertiser,
                $description,
                $isRenovated,
                $phones,
                $type,
                $price,
                $pricePerSquareMeter,
                $address,
                $province,
                $city,
                $squareMeters,
                $bedrooms,
                $bathrooms,
                $hasParking,
                $isSecondHand,
                $hasBuiltInCupboards,
                $builtIn,
                $isFurnished,
                $individualHeating,
                $energyCertification,
                $floor,
                $isOutdoor,
                $isIndoor,
                $hasElevator,
                $houseDate,
                $street,
                $neighborhood,
                $district,
                $hasTerrace,
                $hasStorageRoom,
                $hasEquippedKitchen,
                $hasAirConditioning,
                $hasSwimmingPool,
                $hasGarden,
                $usefulSquareMeters,
                $isAccessible,
                $floorsNumber,
                $hasPetsAllowed,
                $hasBalcony
            ]);
        }

        fclose($csv);
    }


    public function filterProperties($minPrice, $maxPrice, $bedrooms)
    {
        $sql = "
            SELECT
                latitude,
                longitude,
                id,
                title,
                advertiser,
                description,
                is_renovated,
                phones,
                type,
                price,
                price_per_square_meter,
                address,
                province,
                city,
                square_meters,
                bedrooms,
                bathrooms,
                has_parking,
                is_second_hand,
                has_built_in_cupboards,
                built_in,
                is_furnished,
                individual_heating,
                energy_certification,
                floor,
                is_outdoor,
                is_indoor,
                has_elevator,
                house_date,
                street,
                neighborhood,
                district,
                has_terrace,
                has_storage_room,
                has_equipped_kitchen,
                has_air_conditioning,
                has_swimming_pool,
                has_garden,
                useful_square_meters,
                is_accessible,
                floors_number,
                has_pets_allowed,
                has_balcony
            FROM tbl_house WHERE price >= ? AND price <= ? AND bedrooms >= ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$minPrice, $maxPrice, $bedrooms]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calculateAveragePricePerSqm($latitude, $longitude, $distance)
    {
        $kmToDegrees = 1 / 111.32;

        $latMin = $latitude - ($distance * $kmToDegrees);
        $latMax = $latitude + ($distance * $kmToDegrees);
        $lonMin = $longitude - ($distance * $kmToDegrees) / cos(deg2rad($latitude));
        $lonMax = $longitude + ($distance * $kmToDegrees) / cos(deg2rad($latitude));

        $sql = "SELECT AVG(price / square_meters) AS avg_price_per_sqm
                FROM tbl_house
                WHERE latitude BETWEEN ? AND ?
                AND longitude BETWEEN ? AND ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$latMin, $latMax, $lonMin, $lonMax]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $averagePricePerSqm = $result['avg_price_per_sqm'] ?? 0;

        return $averagePricePerSqm;
    }

    public function generateReport($latitude, $longitude, $reportType)
    {
        $kmToDegrees = 1 / 111.32;

        $latMin = $latitude - (1 * $kmToDegrees);
        $latMax = $latitude + (1 * $kmToDegrees);
        $lonMin = $longitude - (1 * $kmToDegrees) / cos(deg2rad($latitude));
        $lonMax = $longitude + (1 * $kmToDegrees) / cos(deg2rad($latitude));

        $sql = "SELECT * FROM tbl_house
                WHERE latitude BETWEEN ? AND ?
                AND longitude BETWEEN ? AND ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$latMin, $latMax, $lonMin, $lonMax]);
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $service = new ReportService();

        if ($reportType === 'pdf') {
            $service->generatePDFReport($properties);
        } elseif ($reportType === 'csv') {
            $service->generateCSVReport($properties);
        } else {
            throw new \Exception("Tipo de reporte no válido. Debe ser PDF o CSV.");
        }
    }

    private function cleanData($csvString) {
        $cleanedData = mb_convert_encoding($csvString, 'UTF-8', 'UTF-8');
        $cleanedData = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]|[\x00-\x7F][\x80-\xBF]+|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2}))/', '', $cleanedData);

        $cleanedData = preg_replace('/"([^"]*)"/', '$1', $cleanedData);

        return trim($cleanedData);
    }

}
?>
