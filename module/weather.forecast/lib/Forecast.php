<?php

namespace Bitrix\Weather;

use Bitrix\Main\Localization\Loc;
use JsonException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;

Loc::loadMessages(__FILE__);

class Forecast
{
    /**
     * @throws JsonException
     */
    public static function getCurrentWeather(): array
    {
        return self::getWeather(
            Option::get("weather.forecast", "key"),
            Option::get("weather.forecast", "city"),
            Option::get("weather.forecast", "unit")
        );
    }

    /**
     * @throws JsonException
     */
    public static function getWeather($apiKey, $city, $unit): array
    {
        $result = [];

        $cache = Cache::createInstance();
        if ($cache->initCache(1800, 'forecast_' . md5($apiKey . $city . $unit), 'weather')) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $ch = curl_init('https://api.openweathermap.org/data/2.5/weather?' . http_build_query([
                    'q'  => $city,
                    'appid' => $apiKey,
                    'units' => $unit
                ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode(
                $response,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (is_array($data) && count($data)) {
                $result = [
                    'result' => is_array($data['main']) && count($data['main']),
                    'error' => $data['message'],
                    'data' => [
                        'temp' => ceil($data['main']['temp']),
                        'humidity' => $data['main']['humidity'],
                        'pressure' => ceil($data['main']['pressure'] * 0.75)
                    ]
                ];
            }

            $cache->endDataCache($result);
        }

        return $result;
    }

}