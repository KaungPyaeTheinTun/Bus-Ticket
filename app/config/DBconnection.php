<?php

abstract class DBconnection
{
    protected static $db;

    public function __construct()
    {
        //self:: is used to refer to static properties or methods inside the class.
        if (self::$db === null) {
            self::$db = new Database();
        }
    }

    protected function getDB()
    {
        return self::$db;
    }
    
}
