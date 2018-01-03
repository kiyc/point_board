<?php

require_once(dirname(dirname(__FILE__)) . '/api/utils.php');

$users = [
    [
        'name' => 'user1',
        'point' => 200,
        'password' => 'user1'
    ],
    [
        'name' => 'user2',
        'point' => 100,
        'password' => 'user2'
    ],
    [
        'name' => 'user3',
        'point' => 50,
        'password' => 'user3'
    ],
];

try {
    $dbh = db_connect();

    $dbh->beginTransaction();

    foreach ($users as $user) {
        $stmt = $dbh->prepare('INSERT INTO users(id, name, point, password) VALUES (null, ?, ?, ?)');
        $stmt->execute([$user['name'], $user['point'], password_hash($user['password'], PASSWORD_DEFAULT)]);
    }

    $dbh->commit();
} catch (Exception $e) {
    $dbh->rollBack();
    echo $e->getMessage() . PHP_EOL;
}
