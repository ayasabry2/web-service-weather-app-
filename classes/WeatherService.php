<?php
namespace WeatherApp;

use GuzzleHttp\Client;

class WeatherService {
    private $apiKey;
    private $clientType; // 'curl' or 'guzzle'
    private $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct($apiKey, $clientType = 'curl') {
        $this->apiKey = $apiKey;
        $this->clientType = $clientType;
    }

    public function getWeatherByCityId($cityId) {
        if ($this->clientType === 'guzzle') {
            return $this->fetchWithGuzzle($cityId);
        }
        return $this->fetchWithCurl($cityId);
    }

    private function fetchWithCurl($cityId) {
        $url = "{$this->baseUrl}?id={$cityId}&units=metric&appid={$this->apiKey}";
        
        file_put_contents('debug.log', "cURL URL: $url\n", FILE_APPEND);
        if (empty($url)) {
            throw new \Exception('cURL error: URL is empty');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        $response = curl_exec($ch);
        if ($response === false) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['main'])) {
            throw new \Exception('Invalid API response: ' . json_last_error_msg());
        }
        return $data;
    }

    private function fetchWithGuzzle($cityId) {
        $client = new Client(['verify' => false]); 
        $url = "{$this->baseUrl}?id={$cityId}&units=metric&appid={$this->apiKey}";
        file_put_contents('debug.log', "Guzzle URL: $url\n", FILE_APPEND);
        $response = $client->get($url);
        $data = json_decode($response->getBody(), true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['main'])) {
            throw new \Exception('Invalid API response: ' . json_last_error_msg());
        }
        return $data;
    }
}