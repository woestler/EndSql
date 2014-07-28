EndSql
<hr>
### Table of Contents
**[Initialization](#initialization)**  
**[Insert Query](#insert-query)**  
**[Update Query](#update-query)**  
**[Select Query](#select-query)**  
**[Delete Query](#delete-query)**  
**[Generic Query](#generic-query-method)**  
**[Where Conditions](#where-method)**  
**[Order Conditions](#ordering-method)**  
**[Group Conditions](#grouping-method)**  
**[Limit Conditions](#limit-method)**  
**[Joining Tables](#join-method)**  
**[Subqueries](#subqueries)**  
**[Helpers](#helpers)**  

### Initialization
To utilize this class, first import Autoload.php into your project, and require it. 
You may need to edit the username and password of your database server in local.php.
And you need to enable the pdo extension for your php. 

```php
require_once ('Autoload.php');
```

After that, create a new instance of the class.

```php
$db = EndSql::getInstance();
```
Next, get a new instance of query method which you want by call the relevant methods. 

### Insert Query
Simple example
```php
$insert = $db->insert();
$data = Array ("login" => "admin",
               "firstName" => "John",
               "lastName" => 'Doe'
              );
$count = $insert->into("user")->values($data)->exec();
if($count)
    echo $count.' user was created.';

$insert->clear();
$data = array("admin","John","Doe");
$insert->values($data)->exec();
```

Insert mutiple lines
```php
$data = array(array("admin","Woestler","Dom"),array("admin","Forsan","Co"));
$insert->values($data)->exec();
```

### Update Query
```php
$data = array("username" => "Woestler");
$update = $db->update();
$update->table("admin"); 
// or you could pass the table name to constructer $db->update("admin");
$update->where()->equal(array("id"=>3));
$count = $update->set($data)->exec();

if ($count)
    echo $count . ' records were updated';
else
    echo 'update failed: ' . $update->getLastError();
```

### Select Query
select from single table;
```php
$select = $db->select();
$admin = $select->from("admin")->exec();
```
select with custom columns set. Functions also could be used

```php
$cols = array ("id", "name", "email");
$select->from("admin")->columns($cols);
$data = $select->exec();
```

select from mutiple tables;
```php
$select->from(array("admin","user"));
$select->columns(array("admin.firstName","user.firstName"));
$select->where()->equal(array("admin.firstName"=>"user.firsName"));
$data = $select->exec(); 
```

select with 'where' condition example
```php
$select->from("admin")->where()->equal(array("id" => 4));
$data = $select->exec();
```

### Delete Query
delete example 1
```php
$delete = $db->delete();
$delete->from("admin")->where()->less(array("id"=>4));
$delete->where()->equal(array("id" => 3),EndSql::SQL_OR);
$delete->exec();  // delete from `admin` where (id<=4) or (id=3);

```
delete example 2
```php
$delete->from("admin")->where()->equal(array("user"=>"woestler"));
$delete->where()->less(array("id"=>40,"priviledge"=>10),EndSql::SQL_OR,EndSql::SQL_AND);
$delete->exec(); 
//delete from admin where (`user`="woestler") or (`id`<=40 and `priviledge`<=10)
``` 

### Generic Query Method


```php

$db = EndSql::getInstance();
$data = $db->query("select * from user");

```


### Where Method
This method allows you to specify where parameters of the query.
WARNING: In order to use column to column comparisons only raw where conditions should be used as column name or functions cant be passed as a bind variable.

Regular == operator with variables:
```php
$select->from("users")->where()->equal(array("id"=>1,"login"=>"woestler"));
$select->exec();
// Gives: SELECT * FROM users WHERE (id=1 AND login='woestler');
```

Regular == operator
```php
$select->from("users")->where()->equal(array("id"=>1,"login"=>"woestler"));
$select->where()->lessThan(array("u_id"=>4,"t_id"=>5),EndSql::SQL_OR,EndSql::SQL_AND);
$select->exec()
// Gives: SELECT * FROM users WHERE (id=1 AND login='woestler') OR (u_id<4 AND t_id<5);
```
Regular NOT IN operator

```php
$select->from("users")->where()->notIn(array("id"=>array(1,2,3)));
// Gives: SELECT * FROM users WHERE id NOT IN (1,2,3);
```

Regular IN operator

```php
$select->from("users")->where()->in(array("id"=>array(1,2,3)));
// Gives: SELECT * FROM users WHERE id IN (1,2,3);
```

Regular LIKE operator
```php
$select->from("users")->where()->like(array("name"=>"%woestler%"));
// Gives: SELECT * FROM users WHERE name LIKE '%woestler%';
```
Regular NOT LIKE operator
```php
$select->from("users")->where()->notLike(array("name"=>"%woestler%"));
// Gives: SELECT * FROM users WHERE name NOT LIKE '%woestler%';
```

BETWEEN
```php
$select->from("users")->where()->between("id",array(4,20));
// Gives: SELECT * FROM users WHERE id BETWEEN 4 AND 20
```


NOT NULL comparison:
```php
$select->from("user")->where()->notNull("lastName");
$results = $db->get("users");
// Gives: SELECT * FROM users where lastName NOT NULL
$select->from("user")->where()->notNull(array("firstName","lastName"),EndSql::SQL_OR);
// Gives: SELECT * FROM users where firstName NOT NULL OR lastName NOT NULL
```

IS NULL comparison
```php
$select->from("user")->where()->isNull("lastName");
$results = $db->get("users");
// Gives: SELECT * FROM users where lastName IS NULL
$select->from("user")->where()->isNull(array("firstName","lastName"),EndSql::SQL_OR);
// Gives: SELECT * FROM users where firstName IS NULL OR lastName IS NULL
```


You can  call where method multiple times.

```php
$select->from("user")->where()->equal(array("username"=>"woestler"));
$select->where()->lessThan(array("id"=>4));
// GIVES: SELECT * FROM user where username="woestler" AND id<4;

```

### Ordering method
```php
$select = $db->select("user");
$select->order("id DESC");
// Gives: SELECT * FROM user ORDER BY id DESC;
$select->clear();
$select->from("user")->order(array("id DESC","login DESC"));
//Gives: SELECT * FROM user ORDER BY id DESC,login DESC
```

### Grouping method
```php
$select->from("users")->group("name");
// Gives: SELECT * FROM users GROUP BY name;
```

### limit-method
```php
$select->from("users")->limit(1);
$select->exec();
// Gives: SELECT * FROM users LIMIT 1;

$select->clear();
$select->from("users")->limit(array(1,3))->exec();
//Gives: SELECT * FROM users LIMIT 1,3
```


### JOIN method

JOIN_LEFT | JOIN_RIGHT | JOIN_INNER

```php
$select->from("user")->join("comment",array("comment.user_id" => "user.user_id"),EndSql::JOIN_LEFT);
//Gives: SELECT * FROM user LEFT JOIN comment ON comment.user_id = user.user_id;

```


### Subqueries
Subquery in selects example1:
```php
$select = $db->select();
$subSelect = $db->select();
$subSelect->from("products")->columns(array("userId"))
          ->where()->greaterThan(array("qty"=>2));
$select->where()->in(array("id"=>$subSelect));

// Gives SELECT * FROM users WHERE id IN (SELECT userId FROM products WHERE qty > 2)
```

Subquery in selects example2:
```php
$select = $db->select();
$subSelect = $db->select();
$subSelect->from("products")->columns(array("userId"))
          ->where()->equal(array("qty"=>2));
$select->where()->equal(array("id"=>$subSelect));

// Gives SELECT * FROM users WHERE id = (SELECT userId FROM products WHERE qty = 2)
```



### helpers

Get last SQL query.
Please note that function returns SQL query only for debugging purposes as its execution most likely will fail due missing quotes around char variables.
```php
    $db = EndSql::getInstance();
    $select = $db->select("user");
    $select->getSql();
    // return : SELECT * FROM user;
```
Error handing

```php
  $data = $select->from("user")->exec();
  if(!$data) {
      print_r($select->getLastError());
  }
```

