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
        try {
            $stmt = (new self)->conn
                ->prepare("SELECT * FROM grade_a_db.users WHERE phone_number=:username OR email=:username LIMIT 1");
            $stmt->bindParam(":username", $username);

            $stmt->execute();
            $response = [];

            if ($stmt->rowCount() == 1) {

                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                if (password_verify($password, $row['password'])) {
                    $response = [
                        "status" => 201,
                        "message" => "Login Successful",
                        "data" =>$row
                    ];
                } else {
                    $response = [
                        "status" => 500,
                        "message" => "Invalid Credentials supplied",
                        "data" => [
                            "username" => "",
                            "user_type" => ""
                        ]
                    ];
                }
            } else {
                $response = [
                    "status" => 500,
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
        $response = null;
        $auth = self::authenticate($data['username'], $data['old_password']);

        if ($auth['status'] == "success") {
            $response = self::updatePassword($data['username'], $data['new_password']);
        } else {
            $response = [
                "status" => 500,
                "message" => "Invalid old credentials please try again latter"
            ];
        }
        return $response;
    }

    public static function updatePassword($username, $newPassword)
    {
        try {

            $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = (new self)->conn
                ->prepare("UPDATE users SET password=:password WHERE username=:usermae");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password_hash);
            $query = $stmt->execute();
            if ($query) {
                return [
                    "status" => 201,
                    "message" => "Password Changed successfully Successfully "
                ];
            } else {
                return [
                    "status" => 500,
                    "message" => "Error Occurred While update your password {$stmt->errorInfo()[2]}"
                ];
            }
        } catch (\PDOException $e) {
            return [
                "status" => 500,
                "message" => "Exception Error {$e->getMessage()}"
            ];
        }
    }
}