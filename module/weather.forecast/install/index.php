<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class weather_forecast extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_ID = 'weather.forecast';
        $this->MODULE_NAME = Loc::getMessage('WEATHER_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('WEATHER_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('WEATHER_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://google.com';
    }

    public function doInstall(): void
    {
        global $APPLICATION;
        ModuleManager::registerModule($this->MODULE_ID);
        $APPLICATION->includeAdminFile(
            Loc::getMessage('INSTALL_TITLE'),
            __DIR__ . '/step.php'
        );
    }

    public function doUninstall(): void
    {
        global $APPLICATION;
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->includeAdminFile(
            Loc::getMessage('UNINSTALL_TITLE'),
            __DIR__ . '/unstep.php'
        );
    }

}