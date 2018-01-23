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
    private $conn;

    /**
     * @return null|\PDO
     */
    public function connect(){
        try{

            $this->conn = new \PDO(
                "mysql:host={$this->databaseHost};
                 dbname={$this->databaseName}",
                $this->databaseUser,
                $this->password
            );

            return $this->conn;

        } catch (\PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * @return bool
     */
    public function closeConnection(){
        $this->conn = null;
        return true;
    }
}
