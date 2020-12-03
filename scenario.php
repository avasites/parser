<?php
set_time_limit(200000);

if(empty($_SERVER["DOCUMENT_ROOT"]))
    $_SERVER["DOCUMENT_ROOT"] = '/var/www';


require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

require_once $_SERVER['DOCUMENT_ROOT'] . '/parser/autoload.php';

new \Zheev\CScenario('AddUserFields', 50);