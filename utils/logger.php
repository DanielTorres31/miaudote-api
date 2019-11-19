<?php

function logApp( $msg, $detalhe )
{
    $file = '../log-application.log';
    $date = date( 'Y-m-d H:i:s' );
    $msg = sprintf( "[%s] : %s - %s%s", $date, $msg, $detalhe, PHP_EOL );
 
    file_put_contents( $file, $msg, FILE_APPEND );
}

?>
