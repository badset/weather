<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] !== '' ? $request['mid'] : $request['id']);

Loader::includeModule($module_id);

$aTabs = array(
    array(
        'DIV'     => 'edit1',
        'TAB'     => Loc::getMessage('WEATHER_OPTIONS'),
        'TITLE'   => Loc::getMessage('WEATHER_OPTIONS'),
        'OPTIONS' => array(
            array(
                'key',
                Loc::getMessage('WEATHER_API_KEY'),
                '',
                array('text', 50)
            ),
            array(
                'city',
                Loc::getMessage('WEATHER_CITY'),
                'Москва',
                array('text', 50)
            ),
            array(
                'unit',
                Loc::getMessage('WEATHER_UNIT'),
                'metric',
                array(
                    'selectbox',
                    array(
                        'metric'  => Loc::getMessage('WEATHER_UNIT_METRIC'),
                        'imperial' => Loc::getMessage('WEATHER_UNIT_IMPERIAL')
                    )
                )
            ),
        )
    )
);

$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs
);

$tabControl->begin();
?>
    <form action="<?=$APPLICATION->getCurPage()?>?mid=<?=$module_id?>&lang=<?=LANGUAGE_ID?>" method="post">
        <?= bitrix_sessid_post(); ?>
        <?php
        foreach ($aTabs as $aTab) {
            if ($aTab['OPTIONS']) {
                $tabControl->beginNextTab();
                __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
            }
        }
        $tabControl->buttons();
        ?>
        <input type="submit" name="apply"
               value="<?=Loc::GetMessage('WEATHER_APPLY')?>" class="adm-btn-save" />
        <input type="submit" name="default"
               value="<?=Loc::GetMessage('WEATHER_DEFAULT')?>" />
    </form>

<?php
$tabControl->end();

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) { // цикл по вкладкам
        foreach ($aTab['OPTIONS'] as $arOption) {
            if ($request['apply']) { // сохраняем введенные настройки
                $optionValue = $request->getPost($arOption[0]);
                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
            } elseif ($request['default']) { // устанавливаем по умолчанию
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->getCurPage().'?mid='.$module_id.'&lang='.LANGUAGE_ID);
}
?>