<?php
if(!check_bitrix_sessid()) {
    return;
}

\CAdminMessage::ShowNote("Модуль «Прогноз погоды» удалён.");