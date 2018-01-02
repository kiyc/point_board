<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $id    = isset($_POST['id']) ? $_POST['id'] : null;
    $point = isset($_POST['point']) ? $_POST['point'] : null;

    if (!$id || !$point) throw new InvalidArgumentException('Invalid Argument Exception: id or point');

    $dbh->beginTransaction();

    // update users
    $stmt = $dbh->prepare('UPDATE users SET point = point + ? WHERE id = ?');
    $stmt->execute([(int)$point, (int)$id]);
    // insert point_logs
    $stmt = $dbh->prepare('INSERT INTO point_logs (id, user_id, point_diff, created) values (null, ?, ?, ?)');
    $stmt->execute([(int)$id, (int)$point, date('Y-m-d H:i:s')]);

    $dbh->commit();

    echo 0;
} catch (Exception $e) {
    $dbh->rollBack();
    echo $e->getMessage();
}
