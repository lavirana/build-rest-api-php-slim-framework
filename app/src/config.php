<?php


class config {
    private $dbSettings;
    private $errorSettings;

    public function __construct()
    {
         $this->dbSettings = [
                'dbname' => "slimphp",
                'user' => "ashish",
                'password' => "password",
                'host' => "mysql",
                'driver' =>  "pdo_mysql"
        ];
    }

    public function getDbConfig() {
        return $this->dbSettings;
    }
}