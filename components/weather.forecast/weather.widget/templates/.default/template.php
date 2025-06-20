<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/** @var array $arResult */
/** @var array $arParams */

if (!$arResult['result']) {
    ShowError(Loc::getMessage('WEATHER_ERROR'));
} else {
?>
    <?=Loc::getMessage('WEATHER_TITLE')?> <b><?=$arParams['CITY']?></b><br /><br />
    <?=Loc::getMessage('WEATHER_TEMP')?>: <?=$arResult['data']['temp'] ? '+' . $arResult['data']['temp'] : $arResult['data']['temp']?>
        <?=$arParams['UNITS'] === 'imperial' ? '°F' : '°C'?><br />
    <?=Loc::getMessage('WEATHER_HUMIDITY')?>: <?=$arResult['data']['humidity']?>%<br />
    <?=Loc::getMessage('WEATHER_PRESSURE')?>: <?=$arResult['data']['pressure']?> <?=Loc::getMessage('WEATHER_PRESSURE_UNIT')?>
<?php
}