<?php

function getConnectionGestionPedidos() {
    $conn=pg_connect("host=10.65.164.18 dbname=metroune user=postgres password=Animo");
    if (!$conn) {
        return false;
    }
    return $conn;
}

?>

