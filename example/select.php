<?php
include '../Autoload.php';

$db = EndSql::getInstance();

$select = $db->select();

$select->from("context"); 

$data = $select->exec(); //execute : select * from context

$data = $select->columns(array("id","caption"));  // select id from context;

$select->where()->equal(array("id" => 0)); // select id from context where (id=0);


$select->where()->isNull(array("id"));       

$select->clear();

$select->from("context");
$sql = $select->getSql();

echo $sql;

$data = $select->exec();
print_r($data);



/***********************************************************************************************
 "$select->where()"" return a instance of class "EndSql_Sql_Where".

 class "where" containing method :
                     
                      ->equal($data,EndSql::SQL_AND,EndSql::SQL_OR);   $data = array("id" => 4);
                      ->not( $data );   $data = array("id" => 4,"t_id" => 5); 
                      ->less( $data );  $data = array("id" => 4);
                      ->lessThan($data , EndSql::SQL_AND, EndSql::SQL_OR);
                      ->greater();
                      ->greaterThan();
                      ->isNull();
                      ->notNull();
                      ->in();
                      ->notIn();
                      ->between();
                      ->like();
                      ->notLike();
                      ->getSql();

/*********************************************************************************************

 class "EndSql_Select" containing method :
                      ->from($table);    $table = "context" | $table = array("context","user");
                      ->join($table, $on, EndSql::JOIN_LEFT); $table = array("user"),
                                                              $on = array("user.user_id","context.user_id");
                      ->order();
                      ->group();
                      ->limit();
                      -offset();
                      ->combine();
                      ->columns();
                      ->distinct();
                      ->getSql();
                      ->exec();

/****************************************************************************************************

 class "EndSql_Insert" containing method :

                      ->into("user");
                      ->columns(array("user_id","username"));
                      ->values(array(NULL,"woestler"));
                      ->exec();

/******************************************************************************************************

class "EndSql_Updata" containing method :
 
 int count ( mixed $var [, int $mode = COUNT_NORMAL ] )

                    object table(mixed $var);
                      ->set(array("username" => "woestler","password" => "123456"));
                      ->where()->isNull("id"); 
                      ->where()->notNull("id");

***********************************************************************/






