<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 12:50 PM
 */
require_once __DIR__.'/../vendor/autoload.php';

use src\controllers\PurchasesController;

print_r(json_encode(PurchasesController::all()));