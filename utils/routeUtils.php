<?php

function getIdNaRequisicao() {
    $path = $_SERVER['REQUEST_URI'];
    $path = removeParametrosPath($path);
    $paths = explode("/", $path);
    return $paths[count($paths) - 1];
}

function removeParametrosPath($path) {
    return explode("?", $path)[0];
}

function getBody() {
    $body = file_get_contents("php://input");
    return json_decode($body);
}

?>