<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

class WeatherForecastWidget extends CBitrixComponent
{

    /**
     * @throws LoaderException|JsonException
     */
    public function executeComponent()
    {
        if (!$this->arParams['API_KEY']) {
            ShowError('Не указан API ключ');
            return;
        }

        if (!$this->arParams['CITY']) {
            $this->arParams['CITY'] = 'Москва';
        }

        if (!$this->arParams['UNITS']) {
            $this->arParams['UNITS'] = 'metric';
        }

        # Если есть наш модуль, обращаемся к нему, в противном случае, делаем запрос сами и кешируем
        if (Loader::includeModule('weather.forecast')) {
            $this->arResult = \Bitrix\Weather\Forecast::getWeather(
                $this->arParams['API_KEY'],
                $this->arParams['CITY'],
                $this->arParams['UNITS'],
            );
        } else {
            $result = [];
            $cache = Cache::createInstance();
            if ($cache->initCache(
                1800,
                'forecast_' . md5(
                    $this->arParams['API_KEY'] .
                    $this->arParams['CITY'] .
                    $this->arParams['UNITS']
                ),
                'weather'
            )) {
                $this->arResult = $cache->getVars();
            } elseif ($cache->startDataCache()) {
                $ch = curl_init(
                    'https://api.openweathermap.org/data/2.5/weather?' . http_build_query([
                        'q' => $this->arParams['CITY'],
                        'appid' => $this->arParams['API_KEY'],
                        'units' => $this->arParams['UNITS']
                    ])
                );
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
                $this->arResult = $result;
                $cache->endDataCache($result);
            }
        }
        $this->includeComponentTemplate();
    }

}
