<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 5:23 PM
 */

namespace src\controllers;


use src\db\DB;

class UserController implements CrudInterface
{
    use Auth;
    protected $conn;
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
        $this->conn = $this->db->connect();
    }

    public function create($data)
    {
        try {
            $stmt = $this->conn
                ->prepare("INSERT INTO grade_a_db.users(fullname, email, phone_number, password) 
                              VALUES (:fullname, :email, :phone_number, :password)");

            $stmt->bindValue(":fullname", $data['fullname']);
            $stmt->bindValue(":email", $data['email']);
            $stmt->bindValue(":phone_number", $data['phone_number']);
            $stmt->bindValue(":password", password_hash($data['password'], PASSWORD_BCRYPT));


            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $this->db->closeConnection();
                return [
                    "status_code" => 201,
                    "message" => "User Account Created Successfully"
                ];

            } else {
                return [
                    "status_code" => 500,
                    "message" => "Error occurred => {$stmt->errorInfo()[2]}"
                ];
            }


        } catch (\PDOException $e) {
            return [
                "status_code" => 500,
                "message" => "Error occurred => {$e->getMessage()}"
            ];
        }
    }

    public function update($data)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE grade_a_db.users SET fullname =:fullname,
                                          email=:email,phone_number=:phone_number WHERE id=:id");
            $stmt->bindValue(":id", $data['id']);
            $stmt->bindValue(":fullname", $data['fullname']);
            $stmt->bindValue(":email", $data['email']);
            $stmt->bindValue(":phone_number", $data['phone_number']);


            if ($stmt->execute()) {
                $this->db->closeConnection();
                return [
                    "status_code" => 201,
                    "message" => "User Account Info Successfully"
                ];
            } else {
                return [
                    "status_code" => 500,
                    "message" => "Error occurred => {$stmt->errorInfo()[2]}"
                ];
            }


        } catch (\PDOException $e) {
            return [
                "status_code" => 500,
                "message" => "Error occurred => {$e->getMessage()}"
            ];
        }
    }

    public static function delete($id)
    {
        try {
            $stmt = (new self)->conn
                ->prepare("DELETE FROM grade_a_db.users WHERE id=:id");
            $stmt->bindParam(":id", $id);

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                (new self)->db->closeConnection();

                return [
                    "status_code" => 201,
                    "message" => "Account Deleted Successfully"
                ];
            } else {
                return [
                    "status_code" => 500,
                    "message" => "Error occurred. No Matching record found"
                ];
            }


        } catch (\PDOException $e) {
            return [
                "status_code" => 500,
                "message" => "Error occurred => {$e->getMessage()}"
            ];
        }
    }

    public static function getId($id)
    {
        try {
            $stmt = (new self)->conn
                ->prepare("SELECT * FROM grade_a_db.users WHERE id=:id LIMIT 1");
            $stmt->bindParam(":id", $id);

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                (new self)->db->closeConnection();
                return [
                    "status_code" => 200,
                    "data" => $stmt->fetch(\PDO::FETCH_ASSOC)
                ];
            } else {
                return [
                    "status_code" => 500,
                    "message" => "No matching record data found"
                ];
            }


        } catch (\PDOException $e) {
            return [
                "status_code" => 500,
                "message" => "No matching record found"
            ];
        }
    }

    public static function all()
    {

        try {
            $stmt = (new self)->conn
                ->prepare("SELECT * FROM grade_a_db.users WHERE  1");

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                (new self)->db->closeConnection();
                return [
                    "status_code" => 200,
                    "data" => $stmt->fetchAll(\PDO::FETCH_ASSOC)
                ];
            } else {
                return [
                    "status_code" => 500,
                    "message" => "No records found"
                ];
            }


        } catch (\PDOException $e) {
            return [
                "status_code" => 500,
                "message" => "Error occurred => {$e->getMessage()}"
            ];
        }
    }


}