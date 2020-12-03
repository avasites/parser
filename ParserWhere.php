<?php


namespace Zheev;


class ParserWhere extends Parser
{

    use HelperIBlock;

    public $iblock_id = 24;

    /**
     * Массив с общими для класса параметрами записи в файл
     *
     * @var string[]
     */
    public $dataForWrite = [
        'format' => 'json',
        'as_json'=> 'Y'
    ];

    /**
     * @inheritDoc
     */
    public function get($limit)
    {
        $this->getProperties();
        $this->getUserFields();
        $this->getSections();
        $this->getElements($limit);
    }

    /**
     * @inheritDoc
     */
    public function add($fields)
    {
        // TODO: Implement add() method.
    }
}