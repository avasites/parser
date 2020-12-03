<?php


namespace Zheev;


class ParserUserFields extends Parser
{

    /**
     * Массив с общими для класса параметрами записи в файл
     *
     * @var string[]
     */
    private $dataForWrite = [
        'format' => 'json',
        'as_json'=> 'Y',
        'fileName' => 'user_fields'
    ];


    /**
     * @inheritDoc
     */
    public function get($limit)
    {
        $userField = \CUserTypeEntity::GetList([], [
            'ENTITY_ID' => 'USER'
        ]);

        $arItems = [];

        while ($arUF = $userField->Fetch())
        {
            $arItems[] = $arUF;
        }

        $this->dataForWrite['body'] = $arItems;

        \Zheev\ParserHelper::writeInFile($this->dataForWrite);

    }

    /**
     * @inheritDoc
     */
    public function add($fields)
    {
        $arFields = file_get_contents(
            $_SERVER['DOCUMENT_ROOT'].ParserHelper::$dir.$this->dataForWrite['fileName'].'.'.$this->dataForWrite['format']
        );

        $arFields = json_decode($arFields, true);

        foreach($arFields as $fields)
        {

        }
    }
}