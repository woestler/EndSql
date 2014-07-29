<?php
include '../Autoload.php';
$db = EndSql::getInstance();
$select = $db->select();

/*************************
     subquery            
**************************/

$select->from("context");
$subselect = $db->select("tumblr")->columns(array("type_id"));
$subselect->where()->equal(array('type_id' => 333 ));
$subselect->limit(1);
$select->where()->equal(array("type_id" => $subselect));
$select->order(array("id DESC","caption DESC"))->limit(2);;  
echo $select->getSql()."\n";  
// Output : SELECT * FROM context WHERE ( type_id = (SELECT type_id FROM tumblr WHERE ( type_id = 333) LIMIT 1)) ORDER BY id DESC,caption DESC LIMIT 2
$data = $select->exec(); 
if($data) {
	//print_r($data);
} else {
	print_r($select->getLastError());
}


/*************************************
      JOIN method
****************************************/
$select->clear();
$select->from("context")->join("tumblr",array("context.type_id"=>"tumblr.type_id"),EndSql::JOIN_RIGHT);
//default (EndSql::JOIN_INNNER)
echo $select->getSql()."\n";
// Output: SELECT * FROM context inner JOIN tumblr ON context.type_id=tumblr.type_id
$data = $select->exec(); 
if($data) {
	// print_r($data);
} else {
	print_r($select->getLastError());
}


/**************************************
   GROUP method
********************************************/
$select->clear();
$select->from("context")->group("type_id")->columns(array("count(*) as total","id"));
echo $select->getSql()."\n";

// SELECT id FROM context GROUP BY type_id


/**************************************************
   ORDER method;
***************************************************/
$select->clear();
$select->from("context")->order("id DESC");
// ->order(array("id DESC","type_id DESC"))
echo $select->getSql()."\n";
//SELECT * FROM context ORDER BY id DESC


/*****************************************************
        LIMIT method
***************************************************/
$select->clear();
$select->from("context")->limit(3);
// ->limit(array(1,4));
echo $select->getSql()."\n";
//SELECT * FROM context LIMIT 3





/**************************************************
