<?php

namespace Zheev;

trait HelperIBlock{

    /**
     * Функция получения элементов
     *
     * @param $limit
     */
    function getElements($limit)
    {
        $this->dataForWrite['fileName'] = 'elements_'.$this->getClassName();
        $res =  \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $this->iblock_id
            ],
            false,
            [],
            ['*', 'PROPERTY_*']
        );

        /**
         * Объявляем массив, для хранения пунктов выдачи
         */
        $elementList = [];
        /**
         * Объявляем итератор
         */
        $iterator = 0;
        while ($arElements = $res->GetNextElement())
        {
            /**
             * Получаем поля и свойства элементов
             */
            $fields = $arElements->GetFields();
            $props = $arElements->GetProperties();
            $elementList[$fields['ID']] = $fields;
            $elementList[$props['ID']]['PROPERTIES'] = $props;

            /**
             * Проверяем, если мы записали в массив, больше или столько же лимита,
             * то пишем в файл
             */
            if(count($elementList) >= $limit)
            {
                $iterator++;
                $this->dataForWrite['body'] = $elementList;
                $this->dataForWrite['iterator'] = $iterator;

                \Zheev\ParserHelper::writeInFile($this->dataForWrite);
                /**
                 * Чистим массив, чтобы не было переполнения
                 */
                unset($elementList);
            }

        }

        /**
         * Если остались, данные которые не записались в файл, то запишем
         */
        if($elementList)
        {
            $iterator++;

            $this->dataForWrite['body'] = $elementList;
            $this->dataForWrite['iterator'] = $iterator;

            \Zheev\ParserHelper::writeInFile($this->dataForWrite);

        }
    }

    /**
     * Получение свойств инфоблока
     */
    function getProperties()
    {
        $this->dataForWrite['fileName'] = 'properties_'.$this->getClassName();

        $res = \CIBlockProperty::GetList(
            Array(),
            Array("IBLOCK_ID"=>$this->iblock_id)
        );

        $propsList = [];

        while ($arProps = $res->GetNext())
        {
            $propsList[] = $arProps;
        }

        $this->dataForWrite['body'] = $propsList;

        \Zheev\ParserHelper::writeInFile($this->dataForWrite);
    }

    /**
     * Получаем пользовательские поля
     */
    private function getUserFields()
    {
        $this->dataForWrite['fileName'] = 'user_fields_'.$this->getClassName();
        $this->dataForWrite['iterator'] =  1;

        $userField = \CUserTypeEntity::GetList([], [
            'ENTITY_ID' => 'IBLOCK_'.$this->iblock_id.'_SECTION'
        ]);

        /**
         * Объявляем массив, для хранения разделов
         */
        $arItems = [];

        while ($arUF = $userField->Fetch())
        {
            /**
             * Собираем в массив поля
             */
            $arItems[] = $arUF;
        }
        /**
         * Переводим в json и записываем в файл
         */
        $this->dataForWrite['body'] = $arItems;

        \Zheev\ParserHelper::writeInFile($this->dataForWrite);
    }

    /**
     * Получаем раздцлы
     *
     * @return \CIBlockResult
     */
    private function getSections()
    {

        $this->dataForWrite['fileName'] = 'section_'.$this->getClassName();
        $this->dataForWrite['iterator'] =  1;

        $res =  \CIBlockSection::GetList(
            ['ID' => 'asc'],
            [
                'IBLOCK_ID' => $this->iblock_id
            ],
            [
                '*'
            ]
        );

        /**
         * Объявляем массив, для хранения разделов
         */
        $sectionList = [];

        while($arSection = $res->Fetch())
        {
            /**
             * Собираем в массив разделы
             */
            $sectionList[] = $arSection;
        }

        /**
         * Переводим в json и записываем в файл
         */
        $this->dataForWrite['body'] = $sectionList;

        \Zheev\ParserHelper::writeInFile($this->dataForWrite);
    }

}