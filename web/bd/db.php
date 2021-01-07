<?php

require("rb.php");
R::setup( 'mysql:host=localhost;dbname=', '', '' );
session_start();
if ( !R::testConnection() )
{
        exit ('Нет соединения с базой данных');
}
?>