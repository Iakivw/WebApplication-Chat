<?php
include 'classDBManager.php';

$dbm = new classDBManager(SERVER,USER,PASS,DBNAME);
//$names =  $dbm->select('*','names');
if ($dbm->checkConnection()) {
    echo 'BD Connected!';
    echo "\n";
}
//if ($dbm->createBanWordsTable()){
//    echo 'Ban Words Table Created!';
//    $dbm->fillBanWordsTable();
//}
//$usr1 = new User(-1,'ridik123', '1111',false);

//$usr1 = new User(-1,'ridik', '111',false);

$msg = new Message(3, -1,"banword0",1,0,false);

if ($dbm->sendMessage($msg)){
    echo 'Message sent!';
}

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



