<?php


namespace Zheev;


use Bitrix\Main\Loader;

class ParserPoints extends Parser
{
    use HelperIBlock;

    public $iblock_id = 26;
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
        if(!Loader::IncludeModule('iblock'))
            return false;

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