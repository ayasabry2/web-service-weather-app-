<?php
namespace WeatherApp;

class City {
    private $cities;

    public function __construct($jsonFilePath) {
        if (!file_exists($jsonFilePath)) {
            throw new \Exception("City JSON file not found: $jsonFilePath");
        }
        $jsonContent = file_get_contents($jsonFilePath);
        $this->cities = json_decode($jsonContent, true);
        if ($this->cities === null) {
            throw new \Exception("Failed to decode JSON file: $jsonFilePath");
        }
    }

    public function getEgyptianCities() {
        $egyptianCities = array_filter($this->cities, function ($city) {
            return isset($city['country']) && $city['country'] === 'EG';
        });
        return array_values($egyptianCities); 
    }
}