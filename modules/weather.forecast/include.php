<?php

use Bitrix\Weather\Forecast;

try {
    Bitrix\Main\Loader::registerAutoloadClasses(
        "weather.forecast",
        array(
            Forecast::class => "lib/Forecast.php",
        )
    );
} catch (\Bitrix\Main\LoaderException $e) {
    //
}