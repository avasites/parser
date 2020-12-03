<?php
namespace Zheev;


abstract class Parser
{

    /**
     * Метод для получения названия дочернего класса
     *
     * @return string
     */
    public static function getClassName(){

        $class = static::class;
        $class = str_replace("Zheev\\Parser", '', $class);

        return strtolower($class);
    }

    /**
     * Метод для получения сущности
     *
     * @param $limit
     * @return mixed
     */
    abstract public function get($limit);

    /**
     * метод для сохранения данных
     *
     * @return mixed
     */
    abstract public function add($fields);


}