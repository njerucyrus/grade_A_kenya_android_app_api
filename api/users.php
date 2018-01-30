<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 5:47 PM
 */

require_once __DIR__ . '/../vendor/autoload.php';

use src\controllers\UserController;

$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
$data = json_decode(file_get_contents('php://input'));

switch ($requestMethod) {
    case "POST":
        create();
        break;
    case "GET":
        read();
        break;
    case "PUT":
        update();
        break;
    case "DELETE":
        delete();
        break;
    default:
        echo "";
}

function create()
{
    global $data;

    if (!empty($data)) {
        $data_array = [];
        foreach ($data as $key => $value) {
            $data_array[$key] = $value;
        }

        if (!empty($_REQUEST['action'] == 'create_account') and isset($_REQUEST['action'])) {
            $userCtrl = new UserController();


            print_r(json_encode($userCtrl->create($data_array)));
        }

        if (!empty($_REQUEST['action'] == 'login') and isset($_REQUEST['action'])) {
            print_r(json_encode(UserController::authenticate($data_array['username'], $data_array['password'])));
        }

        if (!empty($_REQUEST['action'] == 'change_password') and isset($_REQUEST['action'])) {
            print_r(json_encode(UserController::changePassword($data_array)));
        }


    } else {

        print_r(json_encode([
            "status" => 500,
            "message" => "No json data received"
        ]));
    }
}

function update()
{
    global $data;

    if (!empty($data)) {
        $userCtrl = new UserController();

        $data_array = [];
        foreach ($data as $key => $value) {
            $data_array[$key] = $value;
        }
        print_r(json_encode($userCtrl->update($data_array)));
    } else {

        print_r(json_encode([
            "status" => 500,
            "message" => "No json data received"
        ]));
    }
}

function read()
{

        if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
            print_r(json_encode(UserController::getId($_REQUEST['id'])));
        } else {
            print_r(json_encode(UserController::all()));
        }


}

function delete()
{
    if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {

        print_r(json_encode(UserController::delete($_REQUEST['id'])));
    } else {
        print_r(json_encode([
            "status_code" => 500,
            "message" => "Id not specified"
        ]));
    }
}
