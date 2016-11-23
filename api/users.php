<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Entity.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'helpers.php';

$users_entity = new Entity('users');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $users_entity->select('*');

        if (property_exists($req, 'id')) {
            $sth = $users_entity
                ->where(['id' => ':id'])
                ->execute([':id' => $req->id]);

            $user = $sth->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                endJson($user, 200);
            } else {
                http_response_code(404);
            }
        } else {
            $sth = $users_entity->execute();
            $users = $sth->fetchAll(PDO::FETCH_ASSOC);

            if ($users) {
                endJson($users, 200);
            } else {
                http_response_code(404);
            }
        }
        break;
    case 'POST':
        $schema = [
            'first_name' => ':first_name',
            'last_name' => ':last_name',
            'email' => ':email',
            'password' => ':password',
            'role' => ':role',
            'last_login' => ':last_login',
            'actions_performed' => ':actions_performed'
        ];

        if (property_exists($req, 'id')) {
            $users_entity->update($schema)->where(['id' => $req->id]);
        } else {
            $users_entity->insert($schema);
        }

        $users_entity->execute([
            ':first_name' => $req->first_name,
            ':last_name' =>$req->last_name,
            ':email' => $req->email,
            ':password' => password_hash($req->password, PASSWORD_DEFAULT),
            ':role' => $req->role,
            ':last_login' => date('Y-m-d H:i:s'),
            ':actions_performed' => $req->actions_performed
        ]);

        http_response_code(201);
        break;
    case 'DELETE':
        $users_entity->delete(['id' => $req->id]);
        http_response_code(204);
        break;
}

