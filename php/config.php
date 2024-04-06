<?php
include 'classDBManager.php';

$dbm = new classDBManager(SERVER,USER,PASS,DBNAME);
//$names =  $dbm->select('*','names');
if ($dbm->checkConnection()) {
    echo 'BD Connected!';
    echo "\n";
}

if ($dbm->createAndInsertTables())
{
    echo 'Table Exists!';
    echo "\n";
}



