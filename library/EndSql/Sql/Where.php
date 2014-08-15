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
 * @link http://EndSQL.com/ EndSQL
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class EndSql_Sql_Where {

	const OP_OR  = 'OR';
	const OP_AND  = 'AND';

	protected $sql = '';
	
	public function isNull($col, $op = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op;
		if(is_string($col)) {
			$sql .= ' '.$col.' IS NULL';
			$this->sql .= $sql;
			return;
		}
        foreach($col as $value) {
         	$sql .= ' '.$value . ' IS NULL AND';
        }
        $sql = substr($sql, 0, -4);
        $this->sql .= $sql;
	}

	public function notNull($col, $op = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op;
		if(is_string($col)) {
			$sql .= ' '.$col.' IS NOT NULL';
			$this->sql .= $sql;
			return;
		}
        foreach($col as $value) {
         	$sql .= $value . ' IS NOT NULL '.$op;
        }
        $offset = 0-(strlen($op)+1);
        $this->sql .= substr($sql, 0, $offset);
	}

	public function in(array $in, $op = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op;
		foreach($in as $key => $value) {
			if(is_object($value)) {
				$sql = $key . ' IN'.$this->subSelect($value).' '.$op.' ';
			} else {
                $sql .= $key . ' IN (';
                foreach($value as $element) {
            	    $sql  .= '"'.$element.'",';
                } $sql = substr($sql, 0, -1);
                $sql .= ') '.$op.' ';
            }
		}
		 $offset = 0-(strlen($op)+2);
		$sql = substr($sql, 0, $offset);
		$this->sql .= $sql;
	}

	public function notIn(array $notIn, $op = self::OP_AND) {
        if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op;
		foreach($notIn as $key => $value) {
			if(is_object($value)) {
				$sql = $key . ' NOT IN'.$this->subSelect($value).' '.$op.' ';
			} else {
                $sql .= $key . ' NOT IN (';
                foreach($value as $element) {
            	    $sql  .= '"'.$element.'",';
                } $sql = substr($sql, 0, -1);
                $sql .= ') '.$op.' ';
            }
		}
		 $offset = 0-(strlen($op)+2);
		$sql = substr($sql, 0, $offset);
		$this->sql .= $sql;
	}

	public function between($fileds, array $between, $op1 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;

		if(!is_string($fileds)) {
			new Exception(sprintf(
                    '%s expects parameter to be string, "%s" given',
                    __METHOD__,
                    (is_object($limit) ? get_class($limit) : gettype($limit))
                ));
		}
        
        $sql .= ' '.$fileds.' BETWEEN '.$between[0].' AND '.$between[1];

		$this->sql .= $sql;
	}

	public function like(array $like, $op = self::OP_AND,$op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		foreach ($like as $key => $value) {
			$sql .= ' '.$key.' LIKE '.$value.' '.$op2;
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$this->sql .= $sql;
	}

	public function notLike(array $notLike, $op = self::OP_AND,$op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		foreach ($notLike as $key => $value) {
			$sql .= ' '.$key.' NOT LIKE '.$value.' '.$op2;
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$this->sql .= $sql;
	}

	public function lessThan(array $less, $op = self::OP_AND,$op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		$sql .= ' (';
		foreach($less as $key => $value) {
			if(is_object($value)) {
				$sql .= ' '.$key.' <'.$this->subSelect($value).' '.$op2;
			} else {
			    $sql .= ' '.$key.' < '.$value.' '.$op2;
		    }
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$sql .= ')';
		$this->sql .= $sql;
	}

	public function not(array $less, $op = self::OP_AND, $op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		foreach($less as $key => $value) {
			if(is_object($value)) {
				$sql .= ' '.$key.' !='.$this->subSelect($value).' '.$op2;
			} else {
			    $sql .= ' '.$key.' != '.$value.' '.$op2;
			}
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$this->sql .= $sql;
	}

	public function less(array $less, $op1 = self::OP_AND, $op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		$sql .= ' (';
		foreach($less as $key => $value) {
			if(is_object($value)) {
				$sql .= ' '.$key.' <='.$this->subSelect($value).' '.$op2;
			} else {
			    $sql .= ' '.$key.' <= '.$value.' '.$op2;
			}
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$sql .= ')';
		$this->sql .= $sql;
	}

	public function greaterThan(array $great , $op1 = self::OP_AND, $op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		$sql .= ' (';
		foreach($great as $key => $value) {
			if(is_object($value)) {
				$sql .= ' '.$key.' >'.$this->subSelect($value).' '.$op2;
			} else {
			    $sql .= ' '.$key.'> '.$value.' '.$op2;
			}
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$sql .= ')';
		$this->sql .= $sql;
	}

	public function greater(array $great , $op1 = self::OP_AND, $op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		$sql .= ' (';
		foreach($great as $key => $value) {
			if(is_object($value)) {
				$sql .= ' '.$key.' >='.$this->subSelect($value).' '.$op2;
			} else {
			    $sql .= ' '.$key.' >= '.$value.' '.$op2;
			}
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$sql .= ')';
		$this->sql .= $sql;
	}

	public function equal(array $equal,$op1 = self::OP_AND ,$op2 = self::OP_AND) {
		if(!$this->sql)
		    $sql = '';
		else 
			$sql = ' '.$op1;
		$sql .= ' (';
		foreach($equal as $key => $value) {
			if(is_object($value)) {
				$sql .= ' '.$key.' ='.$this->subSelect($value).' '.$op2;
			} else {
				if(is_string($value))
			       $sql .= ' '.$key.' = "'.$value.'" '.$op2;
			    else 
			       $sql .= ' '.$key.' = '.$value.' '.$op2;
		    }
		}
		$offset = 0-(strlen($op2)+1);
		$sql = substr($sql, 0, $offset);
		$sql .= ')';
		$this->sql .= $sql;
	}

	protected function subSelect(EndSql_Sql_Select $select) {
		$sql = ' ('.$select->getSql().')';
		return $sql;
	}

	public function getSql() {
		if($this->sql)
		   return "WHERE ".trim($this->sql);
		else 
		   return;
	}

	public function clear() {
		$this->sql ='';
	}

}

