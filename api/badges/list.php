<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $stmt = $dbh->query('SELECT * FROM badges ORDER BY id ASC');
    $badges = $stmt->fetchAll();

    echo json_encode($badges);
} catch (Exception $e) {
    echo $e->getMessage();
}
