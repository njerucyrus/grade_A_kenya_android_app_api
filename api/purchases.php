<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 12:50 PM
 */
require_once __DIR__ . '/../vendor/autoload.php';

use src\controllers\PurchasesController;

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
        $purchaseCtrl = new PurchasesController();

        $data_array = [];
        foreach ($data as $key => $value) {
            $data_array[$key] = $value;
        }
        print_r(json_encode($purchaseCtrl->create($data_array)));
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
    if (!empty($_REQUEST['action'])) {
        if ($_REQUEST['action'] == 'archive' and !empty($_REQUEST['id'])) {
            print_r(json_encode(PurchasesController::addToArchive($_REQUEST['id'])));
        }
        elseif ($_REQUEST['action'] == 'remove_archive') {
            print_r(json_encode(PurchasesController::removeArchive($_REQUEST['id'])));
        }

        elseif (!empty($data) and $_REQUEST['action'] == 'update') {
            $purchaseCtrl = new PurchasesController();

            $data_array = [];
            foreach ($data as $key => $value) {
                $data_array[$key] = $value;
            }
            print_r(json_encode($purchaseCtrl->update($data_array)));
        } else {

            print_r(json_encode([
                "status" => 500,
                "message" => "No json data received"
            ]));
        }
    }
}


function read()
{
    if (!empty($_REQUEST['filter']) and $_REQUEST['filter'] == 'non') {
        if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
            print_r(json_encode(PurchasesController::getId($_REQUEST['id'])));
        } else {
            print_r(json_encode(PurchasesController::all()));
        }
    }

    if (!empty($_REQUEST['filter']) and $_REQUEST['filter'] == 'date') {
        if (!empty($_REQUEST['date_value']) and isset($_REQUEST['date_value'])) {
            print_r(json_encode(PurchasesController::filterByDate($_REQUEST['date_value'])));
        } else {
            print_r(json_encode([
                "status_code" => 500,
                "message" => "date not specified"
            ]));
        }

    }

    if (!empty($_REQUEST['filter']) and $_REQUEST['filter'] == 'archives') {
        print_r(json_encode(PurchasesController::getArchives()));
    }

    if (!empty($_REQUEST['query'])) {
        print_r(json_encode(PurchasesController::search($_REQUEST['query'])));
    }
}

function delete()
{
    if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {

        print_r(json_encode(PurchasesController::delete($_REQUEST['id'])));
    } else {
        print_r(json_encode([
            "status_code" => 500,
            "message" => "Id not specified"
        ]));
    }
}