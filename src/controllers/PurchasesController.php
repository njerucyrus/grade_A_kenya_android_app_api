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
                ->prepare("INSERT INTO purchases(payee_name, phone_number,
                          payment_description, authorised_by,receipt_no, vat_no,
                          kra_pin_no,product_names, amount_paid) VALUES (:payee_name,
                           :phone_number,:payment_description, :authorised_by,:receipt_no,
                           :vat_no,:kra_pin_no,:product_names, :amount_paid)");

            $stmt->bindValue(":payee_name", $data['payee_name']);
            $stmt->bindValue(":phone_number", $data['phone_number']);
            $stmt->bindValue(":payment_description", $data['payment_description']);
            $stmt->bindValue(":authorised_by", $data['authorised_by']);
            $stmt->bindValue(":receipt_no", $data['receipt_no']);
            $stmt->bindValue(":vat_no", $data['vat_no']);
            $stmt->bindValue(":kra_pin_no", $data['kra_pin_no']);
            $stmt->bindValue(":product_names", $data['product_names']);
            $stmt->bindValue(":amount_paid", $data['amount_paid']);

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $this->db->closeConnection();
                return [
                    "status_code" => 201,
                    "message" => "Transaction Recorded Successfully"
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
            $stmt = $this->conn->prepare("UPDATE purchases SET payee_name=:payee_name,
                           phone_number=:phone_number,payment_description=:payment_description, 
                           authorised_by=:authorised_by,receipt_no=:receipt_no,vat_no=:vat_no,
                           kra_pin_no=:kra_pin_no,product_names=:product_names,
                           amount_paid=:amount_paid WHERE id=:id");
            $stmt->bindValue(":id", $data['id']);
            $stmt->bindValue(":payee_name", $data['payee_name']);
            $stmt->bindValue(":phone_number", $data['phone_number']);
            $stmt->bindValue(":payment_description", $data['payment_description']);
            $stmt->bindValue(":authorised_by", $data['authorised_by']);
            $stmt->bindValue(":receipt_no", $data['receipt_no']);
            $stmt->bindValue(":vat_no", $data['vat_no']);
            $stmt->bindValue(":kra_pin_no", $data['kra_pin_no']);
            $stmt->bindValue(":product_names", $data['product_names']);
            $stmt->bindValue(":amount_paid", $data['amount_paid']);

            if ($stmt->execute()) {
                $this->db->closeConnection();
                return [
                    "status_code" => 201,
                    "message" => "Transaction Updated Successfully"
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
                ->prepare("DELETE FROM purchases WHERE id=:id");
            $stmt->bindParam(":id", $id);

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                (new self)->db->closeConnection();

                return [
                    "status_code" => 201,
                    "message" => "Transaction Record deleted Successfully"
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
                ->prepare("SELECT * FROM purchases WHERE id=:id LIMIT 1");
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
                "message" => "Error occurred => {$e->getMessage()}"
            ];
        }
    }

    public static function all()
    {
        try {
            $stmt = (new self)->conn
                ->prepare("SELECT * FROM purchases WHERE  1");

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                (new self)->db->closeConnection();
                return [
                    "status_code" => 200,
                    "data" => $stmt->fetchAll(\PDO::FETCH_ASSOC)
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

    public static function filterByDate($date)
    {
        try {
            $stmt = (new self)->conn
                ->prepare("SELECT * FROM purchases WHERE  date(date_paid)=:date_paid");
            $stmt->bindValue(":date_paid", $date);

            if ($stmt->execute() && $stmt->rowCount() > 0) {

                (new self)->db->closeConnection();

                return [
                    "status_code" => 200,
                    "data" => $stmt->fetchAll(\PDO::FETCH_ASSOC)
                ];
            } else {
                return [
                    "status_code" => 500,
                    "message" => "No matching record found"
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