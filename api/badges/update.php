<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $dbh->beginTransaction();

    $id       = isset($_POST['id']) ? $_POST['id'] : null;
    $badges   = isset($_POST['badges']) ? $_POST['badges'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (!$id || !$badges || !$password) {
        throw new InvalidArgumentException('Invalid Argument Exception');
    }

    $badges   = explode(',', $badges);

    $stmt = $dbh->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int)$id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) throw new Exception('User Not Found Exception');

    // update user badge
    foreach ($badges as $badge_id) {
        $stmt = $dbh->prepare('SELECT * FROM user_badge WHERE user_id = ? AND badge_id = ?');
        $stmt->execute([(int)$id, (int)$badge_id]);
        $user_badge = $stmt->fetch();

        if ($user_badge) continue;

        $stmt = $dbh->prepare('INSERT INTO user_badge (user_id, badge_id) VALUES (?, ?)');
        $stmt->execute([(int)$id, (int)$badge_id]);
    }

    $dbh->commit();

    echo 0;
} catch (Exception $e) {
    $dbh->rollBack();
    echo $e->getMessage();
}
