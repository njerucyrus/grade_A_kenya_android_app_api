<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 12:12 PM
 */


namespace src\db;
class DB
{
    /**
     * @var string
     */
    private $databaseName = 'grade_a_db';
    /**
     * @var string
     */
    private $password = '';
    /**
     * @var string
     */
    private $databaseHost = 'localhost';
    /**
     * @var string
     */
    private $databaseUser = 'root';
    /**
     * @var
     */

    private $_db;
    static $_instance;

    public function __construct()
    {

        $this->_db = new \PDO(
            "mysql:host={$this->databaseHost};
             dbname={$this->databaseName}",
            $this->databaseUser,
            $this->password
        );

        $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public function __clone(){}

    public function connect()
    {
        return $this->_db;
    }

    public function closeConnection()
    {
        $this->_db = null;
    }


    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
