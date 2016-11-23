<?php

function endJson($content, $status_code) {
    header('Content-Type: application/json');
    http_response_code($status_code);
    echo json_encode($content);
    exit;
}

function getInput () {
    $data = file_get_contents('php://input');
    return json_decode($data);
}

$req = getInput();
