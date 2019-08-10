<?php

function getIdNaRequisicao() {
    $path = $_SERVER['REQUEST_URI'];
    $paths = explode("/", $path);
    return json_encode($paths[count($paths) - 1]);
}

function getBody() {
    $body = file_get_contents("php://input");
    return json_decode($body);
}

?>