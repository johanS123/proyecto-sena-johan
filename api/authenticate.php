<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Entity.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users_entity = new Entity('users');
    $sth = $users_entity
        ->select('*')
        ->where(['email' => "'" . $req->email . "'"])
        ->execute();

    $user = $sth->fetch(PDO::FETCH_OBJ);
    $resp = ['authenticated' => false];

    if ($user && password_verify($req->password, $user->password)) {
        $resp['authenticated'] = true;
        $resp['user'] = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role' => $user->role
        ];

        endJson($resp, 200);
    } else {
        endJson($resp, 200);
    }
}
