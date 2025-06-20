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

        if (!$apiKey) {
            throw new \RuntimeException(Loc::GetMessage('WEATHER_API_ERROR'));
        }

        $cache = Cache::createInstance();
        if ($cache->initCache(1800, 'forecast_' . md5($city . $unit), 'weather')) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $data = json_decode(
                file_get_contents(
                    "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units={$unit}"
                ),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (is_array($data) && count($data)) {
                $result = [
                    'temp' => ceil($data['main']['temp']),
                    'humidity' => $data['main']['humidity'],
                    'pressure' => ceil($data['main']['pressure'] * 0.75)
                ];
            }

            $cache->endDataCache($result);
        }

        return $result;
    }

}