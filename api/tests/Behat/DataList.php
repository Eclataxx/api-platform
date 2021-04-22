<?php


namespace App\Tests\Behat;


class DataList
{
    /**
     * @var DataList
     * @access private
     * @static
     */
    private static $_instance = null;

    public $data = [];

    /**
     *
     * @param void
     * @return void
     */
    private function __construct() {
    }

    public function getData(string $list): Array
    {
        return $this->data;
    }

    /**
     * @param void
     * @return DataList
     */
    public static function getInstance() {

        if(is_null(self::$_instance)) {
            self::$_instance = new DataList();
        }

        return self::$_instance;
    }
}
