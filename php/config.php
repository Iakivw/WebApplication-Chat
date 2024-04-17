<?php
include 'classDBManager.php';

$dbm = new classDBManager(SERVER,USER,PASS,DBNAME);
//$names =  $dbm->select('*','names');
if ($dbm->checkConnection()) {
    echo 'BD Connected!';
    echo "\n";
}

//$usr1 = new User(-1,'ridik123', '1111',false);

//$usr1 = new User(-1,'ridik', '111',false);
$msg = new Message(2, -1,"hello1",1,1,false);

//if ($dbm->sendMessage($msg)){
//    echo 'Message sent!';
//}

//$dbm->userDelete(1);
//$dbm->chatDelete(2);

//if($dbm->registration($usr1)){
//    echo 'Registration Successful!';
//}

//if ($dbm->createTables())
//{
//    echo 'Tables Created!';
//    echo "\n";
//}
//
//echo $dbm->deleteAll();
//echo $dbm->insertTestData()."\n";

//$upd_val = array(
//    'login' => 'vladimir0',
//    'password' => 345,
//    'privilege' => 0
//);
//if ($dbm->update('users', $upd_val, 'user_id = 5'))
//{
//    echo 'Table Updated!';
//    echo "\n";
//}



