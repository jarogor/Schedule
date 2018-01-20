<?php

class DBController
{

    private static $_db;

    public function __construct()
    {
        if (!self::$_db)
            self::$_db = new PDO('mysql:host=localhost;dbname=schedule','root','');
    }


    public function getDb()
    {
        return self::$_db;
    }

}
