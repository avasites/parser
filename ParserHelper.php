<?php


namespace Zheev;


class ParserHelper
{

    public static $dir = '/parser/files/';

    /**
     *
     *  array['fileName'] содержит название файла
     *       ['format'] с каким расширением запишется файл
     *       ['body'] данные, которые надо запсать
     *       ['as_json'] если Y, то перевести данные в json
     *       ['iterator'] указать номер файла
     *
     * @param array $data (See above)
     * @return false
     */
    public static function writeInFile($data)
    {

        self::isDirToCreate();

        if(!$data['fileName'] || !$data['format'] || !$data['body'])
            return false;

        if($data['as_json'] === 'Y')
            $data['body'] = json_encode($data['body'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        if($data['iterator'])
            $data['fileName'] .='.'.$data['iterator'];

        $data['fileName'].= '.'.$data['format'];

        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'].self::$dir.$data['fileName'],
            $data['body'].PHP_EOL ,
            FILE_APPEND
        );
    }

    /**
     * Проверяет наличие папки, либо создаёт её
     */
    private static function isDirToCreate()
    {
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].self::$dir))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].self::$dir, 0755);
        }
    }
}