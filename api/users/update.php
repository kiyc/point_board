<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $dbh->beginTransaction();

    $id       = isset($_POST['id']) ? $_POST['id'] : null;
    $point    = isset($_POST['point']) ? $_POST['point'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (!$id || !$point || !$password) {
        throw new InvalidArgumentException('Invalid Argument Exception');
    }

    $stmt = $dbh->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int)$id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) throw new Exception('User Not Found Exception');

    // update users
    $stmt = $dbh->prepare('UPDATE users SET point = point + ? WHERE id = ?');
    $stmt->execute([(int)$point, (int)$id]);
    // insert point_logs
    $stmt = $dbh->prepare('INSERT INTO point_logs (id, user_id, point_diff, created) VALUES (null, ?, ?, ?)');
    $stmt->execute([(int)$id, (int)$point, date('Y-m-d H:i:s')]);

    $dbh->commit();

    echo 0;
} catch (Exception $e) {
    $dbh->rollBack();
    echo $e->getMessage();
}
