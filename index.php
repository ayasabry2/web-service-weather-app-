<?php
require_once 'vendor/autoload.php';
require_once 'classes/City.php';
require_once 'classes/WeatherService.php';
require_once 'classes/WeatherReport.php';

use WeatherApp\City;
use WeatherApp\WeatherService;
use WeatherApp\WeatherReport;

// Initialize City class to get Egyptian cities
$cityHandler = new City('city.list.json');
$egyptianCities = $cityHandler->getEgyptianCities();

$clientType = $_POST['client_type'] ?? 'curl'; 
$weatherService = new WeatherService('0b1f67f2a5b7df48a55a168b02963feb', $clientType);

$weatherData = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city_id'])) {
    $cityId = $_POST['city_id'];
    $weatherData = $weatherService->getWeatherByCityId($cityId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weather Forecast</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .weather-report { margin-top: 20px; padding: 20px; border: 1px solid #ccc; }
        .weather-report h2 { margin: 0 0 10px; }
        select, button, input { padding: 10px; margin: 5px; }
        .client-type { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Weather Forecast</h1>
    <form method="POST">
        <div>
            <label for="city_id">Select a city:</label>
            <select name="city_id" id="city_id" required>
                <option value="">Select a city</option>
                <?php foreach ($egyptianCities as $city): ?>
                    <option value="<?php echo htmlspecialchars($city['id']); ?>">
                        <?php echo htmlspecialchars($city['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="client-type">
            <label>Select method:</label>
            <input type="radio" name="client_type" value="curl" id="curl" <?php echo $clientType === 'curl' ? 'checked' : ''; ?> required>
            <label for="curl">cURL</label>
            <input type="radio" name="client_type" value="guzzle" id="guzzle" <?php echo $clientType === 'guzzle' ? 'checked' : ''; ?>>
            <label for="guzzle">Guzzle</label>
        </div>
        <button type="submit">Get Weather</button>
    </form>

    <?php if ($weatherData): ?>
        <div class="weather-report">
            <?php
            $report = new WeatherReport($weatherData);
            echo $report->render();
            ?>
        </div>
    <?php endif; ?>
</body>
</html>