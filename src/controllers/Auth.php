<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 12:21 PM
 */

namespace src\controllers;

use src\db\DB;

trait Auth
{
    public static function authenticate($username, $password)
    {
        $db = new DB();
        try {
            $stmt = $db->connect()
                ->prepare("SELECT * FROM users WHERE username=:username LIMIT 1");
            $stmt->bindParam(":username", $username);

            $stmt->execute();
            $response = [];

            if ($stmt->rowCount() == 1) {

                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                if (password_verify($password, $row['password'])) {
                    $response = [
                        "status" => "success",
                        "message" => "Login Successful",
                        "data" =>$row
                    ];
                } else {
                    $response = [
                        "status" => "error",
                        "message" => "Invalid Credentials supplied",
                        "data" => [
                            "username" => "",
                            "user_type" => ""
                        ]
                    ];
                }
            } else {
                $response = [
                    "status" => "error",
                    "message" => "Invalid.. Credentials supplied",
                    "data" => [
                        "username" => "",
                        "user_type" => ""
                    ]
                ];
            }
            return $response;

        } catch (\PDOException $e) {
            return [
                "status" => "error",
                "message" => "Exception Error {$e->getMessage()}"
            ];
        }
    }

    public static function generateCsrfToken()
    {
        return sha1(md5(uniqid("auth_token", true)));
    }

    public static function changePassword($data)
    {
        //perform the checks to ensure data passed is an array
        //and contains the expected keys.
        if (!is_array($data)) {
            throw new \UnexpectedValueException("The parameter passed must be an array");
        }
        if (!array_key_exists("old_password", $data)) {
            throw new \UnexpectedValueException("missing old_password key in the array passed");
        }
        if (!array_key_exists("new_password", $data)) {
            throw new \UnexpectedValueException("missing new_password key in the array passed");
        }
        if (!array_key_exists("username", $data)) {
            throw new \UnexpectedValueException("missing username key in the array passed");
        }

        $response = null;
        $auth = self::authenticate($data['username'], $data['old_password']);

        if ($auth['status'] == "success") {
            $response = self::updatePassword($data['username'], $data['new_password']);
        } else {
            $response = [
                "status" => "error",
                "message" => "Invalid old credentials please try again latter"
            ];
        }
        return $response;
    }

    public static function updatePassword($username, $newPassword)
    {
        try {
            $db = new DB();
            $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $db->connect()
                ->prepare("UPDATE users SET password=:password WHERE username=:usermae");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password_hash);
            $query = $stmt->execute();
            if ($query) {
                return [
                    "status" => "success",
                    "message" => "Password Changed successfully Successfully "
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "Error Occurred While update your password {$stmt->errorInfo()[2]}"
                ];
            }
        } catch (\PDOException $e) {
            return [
                "status" => "error",
                "message" => "Exception Error {$e->getMessage()}"
            ];
        }
    }
}