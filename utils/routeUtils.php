<?php

function getIdNaRequisicao($path) {
    $paths = explode("/", $path);
    return json_encode($paths[count($paths) - 1]);
}

?>