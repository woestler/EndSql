<?php
require 'Autoload.php';
$start =  microtime(true);
$db = EndSql::getInstance();
// $insert = $db->insert("type");
// $pdo = $db->getPdo();

// $pdo->prepare("INSERT INTO type (type_id,type) VALUES (?,?)")->execute(array(NULL,'test1'));


$delete = $db->delete();
$update = $db->update();
$select = $db->select();
$insert = $db->insert();

$data = array(NULL,'TEST_Y');
// $select->columns(array("count(*)","type"));
// $select->group("type");


 // echo $subselect->getSql(); echo "\n";
$data = array("id" => 3,
	          "admin" => "woestler");
$delete->from("context")->where()->greaterThan(array("id"=>20));
echo $delete->exec();

// echo $select->getSql();
// print_r($select->exec());


// $delete->where()->greaterThan(array("type_id" => 60));
// $delete->from("type");
// echo $delete->exec();
// $insert->into("tumblr");
// $insert->values(array('woestler@gmail.com','dongyuhan',"jffjowjfowfo","1324535322","233522"));
// $insert->exec();
// for($i=0;$i<100;$i++) {
//   $insert->values(array(NULL,"TEST1".$i));
//   $insert->exec();
// }
// 
// $update->where()->equal(array("type_id" => 300));
// $update->where()->isNull(array("type"));
// $update->table("type");

// echo $update->set(array("type" => "new"))->exec();

$end = microtime(true);
 // usleep(1000000);


