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
// $select->from("type");
// $select->group("type");
// $select->group("type");


$select->from("context");
$subselect = $db->select("tumblr")->columns(array("type_id"));
$subselect->where()->equal(array('type_id' => 333 ));
$subselect->limit(1);
$select->where()->equal(array("type_id" => $subselect));
$select->limit(2);
$select->order(array("id DESC","caption DESC"));  

echo $select->getSql();
//SELECT * FROM context WHERE ( type_id = (SELECT type_id FROM tumblr WHERE ( type_id = 333) LIMIT 1)) ORDER BY id DESC,caption DESC LIMIT 2

print_r($select->exec());
// Gives: SELECT * FROM users LIMIT 1;

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


