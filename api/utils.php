<?php

require_once(dirname(__FILE__) . '/config.php');

function db_connect()
{
    if (DB === 'sqlite') {
        $dbh = new PDO('sqlite:' . dirname(dirname(__FILE__)) . '/' . DB_NAME);
    } elseif (DB === 'mysql') {
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
    } else {
        throw new PDOException('Not defined valid DB in config.php');
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $dbh;
}
