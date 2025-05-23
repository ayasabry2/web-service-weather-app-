<?php
namespace WeatherApp;

class WeatherReport {
    private $weatherData;

    public function __construct($weatherData) {
        $this->weatherData = $weatherData;
    }

    public function render() {
        $city = htmlspecialchars($this->weatherData['name']);
        $date = date('l h:i A', $this->weatherData['dt']);
        $dateFull = date('jS F, Y', $this->weatherData['dt']);
        $description = htmlspecialchars(ucwords($this->weatherData['weather'][0]['description']));
        $temp = round($this->weatherData['main']['temp']);
        $tempMin = round($this->weatherData['main']['temp_min']);
        $tempMax = round($this->weatherData['main']['temp_max']);
        $humidity = $this->weatherData['main']['humidity'];
        $windSpeed = round($this->weatherData['wind']['speed'] * 3.6, 1); 

        return <<<HTML
            <h2>$city Weather Status</h2>
            <p>$date</p>
            <p>$dateFull</p>
            <p>$description</p>
            <p>{$temp}°C ({$tempMin}°C / {$tempMax}°C)</p>
            <p>Humidity: {$humidity}%</p>
            <p>Wind: {$windSpeed} km/h</p>
        HTML;
    }
}