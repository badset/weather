<?php
use Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "API_KEY" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("WEATHER_API_KEY"),
            "TYPE" => "STRING",
        ),
        "CITY" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("WEATHER_CITY"),
            "TYPE" => "STRING",
        ),
        "UNITS" => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage("WEATHER_UNIT"),
            'TYPE' => 'LIST',
            'VALUES' => [
                'metric' => Loc::getMessage("WEATHER_UNIT_METRIC"),
                'imperial' => Loc::getMessage("WEATHER_UNIT_IMPERIAL"),
            ],
            'DEFAULT' => 'metric',
            'MULTIPLE' => 'N',
        )
    ),
);