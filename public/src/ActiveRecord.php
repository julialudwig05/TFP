<?php

namespace julia\tfp;
require_once __DIR__ . '/../../vendor/autoload.php';

interface ActiveRecord
{
    public static function findAll();
    public static function findById($id);
    public function save();
    public function delete();
}
