<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 12:20 PM
 */

namespace src\controllers;

use src\db\DB;

class PurchasesController implements CrudInterface
{
    protected $conn;
    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connect();
    }


    public function create($data)
    {

    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }

    public static function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public static function getId($id)
    {
        // TODO: Implement getId() method.
    }

    public function all()
    {

       try{
           $stmt = (new self)->conn
               ->prepare("SELECT * FROM users WHERE 1");
           if($stmt->execute() && $stmt->rowCount() > 0){
               return $stmt->fetchAll(\PDO::FETCH_ASSOC);
           }else{
               return [
                   "error"=>"{$stmt->errorInfo()[2]}"
               ];
           }
       } catch (\PDOException $e){
           echo $e->getMessage();
           return [
               "error"=>$e->getMessage()
           ];
       }
    }

}