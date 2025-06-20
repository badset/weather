<?php
use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage("WEATHER_TITLE"),
    "DESCRIPTION" => "",
    'CACHE_PATH' => 'Y',
    'SORT' => 10,
    'COMPLEX' => 'N',
    'PATH' => array(
        'ID' => 'weather',
        'NAME' => Loc::getMessage("WEATHER_PATH"),
    )
);
