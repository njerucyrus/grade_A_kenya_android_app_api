<?php
/**
 * Created by PhpStorm.
 * User: njerucyrus
 * Date: 1/23/18
 * Time: 12:02 PM
 */

namespace src\controllers;


interface CrudInterface
{
    public function create($data);
    public function update($data);
    public static function delete($id);
    public static function getId($id);
    public static function all();
}