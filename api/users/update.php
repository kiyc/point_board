<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $id    = isset($_POST['id']) ? $_POST['id'] : null;
    $point = isset($_POST['point']) ? $_POST['point'] : null;

    if (!$id || !$point) throw new InvalidArgumentException('Invalid Argument Exception: id or point');

    $stmt = $dbh->prepare('UPDATE users SET point = point + ? WHERE id = ?');
    $stmt->execute([(int)$point, (int)$id]);

    echo 0;
} catch (Exception $e) {
    echo $e->getMessage();
}
