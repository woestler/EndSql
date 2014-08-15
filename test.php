<?php
/**
 * EndSQL
 *
 * Database abstract layer.
 * 
 *
 * Copyright (c) 2013-2014, Woestler
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * 	* Redistributions of source code must retain the above copyright notice, this list of
 * 	  conditions and the following disclaimer.
 *
 * 	* Redistributions in binary form must reproduce the above copyright notice, this list
 * 	  of conditions and the following disclaimer in the documentation and/or other materials
 * 	  provided with the distribution.
 *
 * 	* Neither the name of the EndSQL Team nor the names of its contributors may be used
 * 	  to endorse or promote products derived from this software without specific prior
 * 	  written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package EndSQL
 * @version 1.0.2
 * @copyright 2013-2014 Woestler
 * @author Woestler
 * @link http://EndSQL.org/ EndSQL
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
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


