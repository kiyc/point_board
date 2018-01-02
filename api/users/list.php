<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $stmt = $dbh->query('SELECT * FROM users ORDER BY point DESC');
    $users = $stmt->fetchAll();

    echo json_encode($users);
} catch (Exception $e) {
    echo $e->getMessage();
}
