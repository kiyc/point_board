<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $id    = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) throw new InvalidArgumentException('Invalid Argument Exception: id');

    $stmt = $dbh->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int)$id]);

    $user = $stmt->fetch();

    if (!$user) throw new Exception('User Not Found Exception');

    echo json_encode($user);
} catch (Exception $e) {
    echo $e->getMessage();
}
