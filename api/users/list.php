<?php

require_once(dirname(dirname(__FILE__)) . '/utils.php');

header('Access-Control-Allow-Origin: *');

try {
    $dbh = db_connect();

    $stmt = $dbh->query('SELECT * FROM users ORDER BY point DESC');
    $users = $stmt->fetchAll();

    $stmt = $dbh->query('SELECT user_badge.*, badges.* FROM user_badge JOIN badges ON badges.id = user_badge.badge_id');
    $user_badges = $stmt->fetchAll();

    foreach ($users as &$user) {
        foreach ($user_badges as $user_badge) {
            if ($user['id'] != $user_badge['user_id']) continue;
            if (!isset($user['badges'])) {
                $user['badges'] = [];
            }
            $user['badges'][] = ['id' => $user_badge['badge_id'], 'name' => $user_badge['name'], 'src' => $user_badge['src']];
        }
    }

    echo json_encode($users);
} catch (Exception $e) {
    echo $e->getMessage();
}
