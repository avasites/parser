<?php
namespace Zheev;


class ParserGroup extends Parser
{

    /**
     * Класс для записи сущности
     * @var string
     */
    private $entityClass = '\Bitrix\Main\GroupTable';

    public function getTableName(): string
    {
        return 'b_group';
    }

    public function get($limit)
    {

    }

    /**
     *
     * Получаем данные из таблицы с данными с прода
     *
     * @return $this
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public function getFromDB()
    {
        $sql = "select * from ".self::getTableName();
        $res = $this->db->query($sql);
        while ($item = $res->fetch())
        {
           $this->items[$item['ID']] = $item;
        }

        return $this;
    }

    public function add($fields)
    {

        foreach ($this->items as $key => $item)
        {
            if($this->entityClass::getById($key))
                $this->entityClass::delete($key);

            $this->entityClass::add($item);
        }

    }

}