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
 * @version 1.0.0
 * @copyright 2013-2014 Woestler
 * @author Woestler
 * @link http://EndSQL.org/ EndSQL
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

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
