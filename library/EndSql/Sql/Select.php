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
 * @link http://EndSQL.com/ EndSQL
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

class EndSql_Sql_Select extends EndSql_Sql_AbstractSql {
	 

	const SELECT              = 'select';
    const QUANTIFIER          = 'quantifier';
    const COLUMNS             = 'columns';
    const TABLE               = 'table';
    const JOINS               = 'joins';
    const WHERE               = 'where';
    const GROUP               = 'group';
    const HAVING              = 'having';
    const ORDER               = 'order';
    const LIMIT               = 'limit';
    const OFFSET              = 'offset';
    const QUANTIFIER_DISTINCT = 'DISTINCT';
    const QUANTIFIER_ALL      = 'ALL';
    const JOIN_INNER          = 'inner';
    const JOIN_OUTER          = 'outer';
    const JOIN_LEFT           = 'left';
    const JOIN_RIGHT          = 'right';
    const SQL_STAR            = '*';
    const ORDER_ASCENDING     = 'ASC';
    const ORDER_DESCENDING    = 'DESC';
    const COMBINE             = 'combine';
    const COMBINE_UNION       = 'union';
    const COMBINE_EXCEPT      = 'except';
    const COMBINE_INTERSECT   = 'intersect';

	 /**
     * @var bool
     */
    protected $tableReadOnly = false;

    /**
     * @var bool
     */
    protected $prefixColumnsWithTable = true;

    /**
     * @var string|array|TableIdentifier
     */
    protected $table = null;

    /**
     * @var null|string|Expression
     */
    protected $quantifier = null;

    /**
     * @var array
     */
    protected $columns = array(self::SQL_STAR);

    /**
     * @var array
     */
    protected $joins = array();

    /**
     * @var Where
     */
    protected $where = null;

    /**
     * @var array
     */
    protected $order = array();

    /**
     * @var null|string
     */
    protected $group = null;

    /**
     * @var null|string|array
     */
    protected $having = null;

    /**
     * @var int|array|null
     */
    protected $limit = null;

    /**
     * @var int|null
     */
    protected $offset = null;

    /**
     * @var array
     */
    protected $combine = array();

    /**
     * @var array|null
     */
    protected $alias = null;

     /**
     * @var string|array|null
     */
    protected $distinct = null;



    /**
     * Constructor
     *
     * @param  null|string|array|TableIdentifier $table
     */
  
    public function from($table) {

         if(is_array($table)) {
         	 $this->table = $table;
         } elseif(is_string($table)) {
         	 $this->table[] = $table;
         } else {
         	 throw new Exception("parameter must be string or array", 1);
         	
         }
         return $this;
    }

    public function join($name, array $columns, $type = self::JOIN_INNER)
    {
        $this->joins = array(
            'name'    => $name,
            'columns' => $columns,
            'type'    => $type
        );
        return $this;
    }

    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    public function order($order)
    {
        if (is_string($order)) {            
            $order = (array) $order;
        } elseif (!is_array($order)) {
            $order = array($order);
        }
        foreach ($order as $k => $v) {
            if (is_string($k)) {
                $this->order[$k] = $v;
            } else {
                $this->order[] = $v;
            }
        }
        return $this;
    }

    public function limit($limit)
    {
        if (is_array($limit)) {
            if(count($limit)>2) {
                throw new Exception(sprintf(
                    '%s expects parameter to be numeric, "%s" given',
                    __METHOD__,
                    (is_object($limit) ? get_class($limit) : gettype($limit))
                ));
            }
        }

        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        if (!is_numeric($offset)) {
            throw new Exception(sprintf(
                '%s expects parameter to be numeric, "%s" given',
                __METHOD__,
                (is_object($offset) ? get_class($offset) : gettype($offset))
            ));
        }

        $this->offset = $offset;
        return $this;
    }

    public function combine(Select $select, $type = self::COMBINE_UNION, $modifier = '')
    {
        if ($this->combine !== array()) {
            throw new Exception('This Select object is already combined and cannot be combined with multiple Selects objects');
        }
        $this->combine = array(
            'select'   => $select,
            'type'     => $type,
            'modifier' => $modifier
        );
        return $this;
    }

    public function columns(array $columns, $prefixColumnsWithTable = true)
    {
        $this->columns = $columns;
        $this->prefixColumnsWithTable = (bool) $prefixColumnsWithTable;
        return $this;
    }

    public function alias(array $alias) {
        $this->alias = $alias;
    }

    public function distinct($distinct) {
        if(is_array($distinct) || is_string($distinct)) {
            $this->distinct = $distinct;
        } else {
            throw new Exception(sprintf(
                '%s expects parameter to be array or string, "%s" given',
                __METHOD__,
                (is_object($distinct) ? get_class($distinct) : gettype($distinct))
            ));
        }
    }

    public function processColumns() {
        $sql = "SELECT ";
        if(!$this->columns) {
            $sql .= self::SQL_STAR;
            return $sql;
        }
    	
    	foreach ($this->columns as $value) {
    		$sql .= $value.',';
    	} $sql = substr($sql, 0, -1);
    	return $sql;
    }

    public function processLimit() {
        if(!$this->limit) return false;
        if(is_array($this->limit)) {
            $limit1 = $this->limit[0];
            $limit2 = $this->limit[1];
            $sql = " LIMIT $limit1,$limit2";
        } else {
            $sql = " LIMIT $this->limit";
        }
        return $sql;
    }

    public function processDistinct() {
        if(!$this->distinct) return;

    }

    public function processFrom() {
    	$sql = " FROM ";
    	if(!$this->table) throw new Exception("table is empty");
        if(is_array($this->table)) {
    	    foreach ($this->table as $value) {
    		    $sql .= $value.',';
    	    } return substr($sql, 0, -1);
        } else {
            $sql .= $this->table;
            return $sql;
        }
    }

    public function processOrder() {
    	if(!$this->order) return;
    	$sql = ' ORDER BY ';
    	foreach ($this->order as $value) {
    		$sql .= $value.',';
    	} return substr($sql, 0, -1);        
    }

    public function processGroup() {
        if(!$this->group) return;
        $sql = " GROUP BY $this->group";
        return $sql;
    }

    public function processJoin() {
    	if(!$this->joins) return;
        $left = key($this->joins['columns']);
        $right = $this->joins['columns'][$left];
        $sql = sprintf(" %s JOIN %s ON %s=%s",$this->joins['type'],
                         $this->joins['name'],
                         $left,
                         $right
                      );
        return $sql;
    }

    public function getSql() {
        $sql = $this->processColumns();
        $sql .= $this->processFrom();
        $sql .= $this->processJoin();
        $sql .= ' '.$this->where->getSql();
        $sql .= $this->processOrder();
        $sql .= $this->processLimit();
        $sql .= $this->processGroup();
        return $sql;
    }

    public function exec() {
        $resultSet = $this->pdo->query($this->getSql());
        if(!$resultSet) {
            return false;
        } else {
            return $resultSet->fetchAll();
        }
    }

    public function clear() {
        $this->order = null;
        $this->table = null;
        $this->columns = null;
        $this->joins = null;
        $this->where->clear();
        $this->limit = null;
        $this->group = null;
        $this->distinct = null;
        $this->combine = null;
        $this->offset = null;
        $this->alias = null;
        return $this;
    }
}
