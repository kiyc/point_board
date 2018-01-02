<?php

require_once(dirname(__FILE__) . '/config.php');

function db_connect()
{
    $dbh = new PDO('sqlite:' . dirname(dirname(__FILE__)) . '/' . DB_NAME);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $dbh;
}
