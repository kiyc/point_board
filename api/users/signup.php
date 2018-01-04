<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $dbh->beginTransaction();

    $name     = isset($_POST['name']) ? trim($_POST['name']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if (!$name || !$password) {
        throw new InvalidArgumentException('Invalid Argument Exception');
    }

    $stmt = $dbh->prepare('INSERT INTO users (id, name, password, point, created) VALUES (null, ?, ?, 0, ?)');
    $stmt->execute([$name, password_hash($password, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);

    $dbh->commit();

    echo 0;
} catch (Exception $e) {
    $dbh->rollBack();
    echo $e->getMessage();
}
