<?php
namespace Zheev;

class ParserUsers extends Parser
{

    private $number;
    private $firstID;
    private $lastID;

    /**
     * Массив с общими для класса параметрами записи в файл
     *
     * @var string[]
     */
    private $dataForWrite = [
        'format' => 'json',
        'as_json'=> 'Y',
        'fileName' => 'list_users'
    ];

    /**
     * @inheritDoc
     */
    public function add($fields)
    {
        $files = $this->getFiles();

        foreach ($files as $file)
        {
            if(!$this->addUsersFromFile($file))
                return false;

//            unlink($_SERVER['DOCUMENT_ROOT'].ParserHelper::$dir.$file);
        }
    }

    private function addUsersFromFile($file)
    {
        /**
         * Получаем содержимое файла
         */
        $users = file_get_contents($_SERVER['DOCUMENT_ROOT'].ParserHelper::$dir.$file);
        /**
         * Преобразуем в ассоциативный массив
         */
        $userList = json_decode($users, true);



        return false;
    }

    private function getFiles()
    {
        $path = $_SERVER['DOCUMENT_ROOT'].ParserHelper::$dir;

        $files = scandir($path, SCANDIR_SORT_NONE);

        if(!$files)
            return false;

        /**
         * Массив, будем складывать файлы и сортировать
         */
        $arFiles = [];

        foreach ($files as $file)
        {

            if($file === '.' || $file === '..')
                continue;

            $name = explode('.', $file);

            $number = intval($name[1]);

            $arFiles[$number] = $file;
        }

        /**
         * сортируем
         */

        ksort($arFiles);

        return $arFiles;
    }

    /**
     * @inheritDoc
     */
    public function get($limit)
    {

        \CModule::IncludeModule('iblock');

        $by = "id";
        $order="asc";

        /**
         * Получаем пользователей, в диапозоне ID от $this->firstID до $this->lastID
         * Огрничиев количеством $limit
         */
        $users = \CUser::GetList($by, $order, [
            '>ID' => $this->firstID,
            '<=ID' => $this->lastID
        ], [
            'NAV_PARAMS' => [
                'nPageSize' => $limit,
            ],
            'SELECT' => [
                'UF_*'
            ]
        ]);

        $item = [];

        while($arUser = $users->Fetch())
        {
            /**
             * Запишем в массив
             */
            $item[$arUser['ID']] = $arUser;
            $item[$arUser['ID']]['GROUPS'] = \CUser::GetUserGroup($arUser['ID']);

        }

        /**
         * Запишем полученных пользователей
         */
        $this->dataForWrite['body'] = $item;

        $this->dataForWrite['iterator'] = $this->number;

        \Zheev\ParserHelper::writeInFile($this->dataForWrite);

        $lastItem = end($item);

        return $lastItem['ID'];

    }


    /**
     * @inheritDoc
     */
    public function generator($limit)
    {

        $by = "id";
        $order="asc";

        $users = \CUser::GetList($by, $order, [
            '>ID' => 1
        ], []);

        /**
         * Получим количество пользоватлей
         */
        $countUsers = $users->SelectedRowsCount();

        /**
         * Получим количество итераций записи
         */
        $countQueue = ($limit > $countUsers ? 1 : $countUsers/$limit);

        /**
         * Округлим в большую сторону
         */
        $countQueue = ceil($countQueue);

        $this->lastID = 0;

        for($iteration = 0; $iteration < $countQueue; $iteration++)
        {
            /**
             * Запишем в свойство number текущую итерацию
             */
            $this->number = $iteration;
            /**
             * Запишем в начальный ID, текущий последний
             */
            $this->firstID = $this->lastID;

            /**
             * Получим пользователей и новый последний ID
             */
            $this->lastID = $this->get($limit);
        }


    }

}