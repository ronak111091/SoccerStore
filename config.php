<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 12-03-2018
 * Time: 12:15
 */

$config = array(
    "db" => array(
        "mysql" => array(
            "dbname" => "test",
            "username" => "root",
            "password" => "Password",
            "host" => "localhost"
        ),
        "adminer" => array(
            "dbname" => $_SERVER['DB'],
            "username" => $_SERVER['DB_USER'],
            "password" => $_SERVER['DB_PASSWORD'],
            "host" => $_SERVER['DB_SERVER']
        )
    ),
    "urls" => array(
        "baseUrl" => "http://localhost/SoccerStore/"
    )
);