<?php


namespace Zheev;

/**
 * Класс для работы со сценариями парсинга
 *
 * Class CScenario
 * @package Zheev
 */
class CScenario
{

    private $limit;

    private $error = [];

    /**
     * Точка входа
     *
     * @param $scenario
     * @return false
     */
    public function __construct($scenario, $limit = 10)
    {

        $this->limit = $limit;

        /**
         * Разобьём полученную "команду", и получим класс и метод
         */
        preg_match('/[A-Z][^A-Z]*?/U', $scenario, $ar );

        /**
         * Записываем метод и класс
         */
        $method = $ar[0];

        $class = str_replace($method, '', $scenario);

        $class = trim($class);

        $class = "\\Zheev\\Parser".$class;

        /**
         * метод переводим в нижний регистр
         */
        $method = strtolower(trim($method));

        /**
         * Если метода нет, то ошибка
         */
        if(!method_exists($class, $method)) {
            $this->error[] = $scenario . ' не существует';
            return false;
        }

        /**
         * Вызов метода
         */
        (new $class())->$method($limit);


    }

    /**
     * Метод для возврата ошибок
     *
     * @return array
     */
    function getError()
    {
        return $this->error;
    }

}