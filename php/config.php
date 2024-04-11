<?php
include 'classDBManager.php';

$dbm = new classDBManager(SERVER,USER,PASS,DBNAME);
//$names =  $dbm->select('*','names');
if ($dbm->checkConnection()) {
    echo 'BD Connected!';
    echo "\n";
}

//if ($dbm->createAndInsertTables())
//{
//    echo 'Table Exists!';
//    echo "\n";
//}

$upd_val = array(
    'login' => 'vladimir0',
    'password' => 345,
    'privilege' => 0
);
if ($dbm->update('users', $upd_val, 'user_id = 5'))
{
    echo 'Table Updated!';
    echo "\n";
}



