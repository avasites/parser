<?php

spl_autoload_register(function ($class_name) {

    if ($pos = strpos($class_name, 'Zheev\\') === false)
        return false;

    /**
     * Убираем NameSpace. Получаем название файла
     */
    $fileName = substr($class_name, ((int)$pos + (int)strlen('Zheev\\')));


    include $_SERVER['DOCUMENT_ROOT'] . '/parser/' . $fileName . '.php';
});