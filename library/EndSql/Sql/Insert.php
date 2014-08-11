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
 *  * Redistributions of source code must retain the above copyright notice, this list of
 *    conditions and the following disclaimer.
 *
 *  * Redistributions in binary form must reproduce the above copyright notice, this list
 *    of conditions and the following disclaimer in the documentation and/or other materials
 *    provided with the distribution.
 *
 *  * Neither the name of the EndSQL Team nor the names of its contributors may be used
 *    to endorse or promote products derived from this software without specific prior
 *    written permission.
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

class EndSql_Sql_Insert extends EndSql_Sql_AbstractSql {

	protected $values;

	protected $columns = array();

	public function into($table) {
		$this->table = $table;
		return $this;
	}
    
	public function columns(array $columns) {
         $this->columns = $columns;
         return $this;
    }

    public function values(array $values) {
        $this->values = '(';
        foreach ($values as $key => $value) {
            if(!is_array($value)) {
                $type = 1;
                if(is_string($key)) {
                    $this->columns[] = $key;
                }
                $this->values .= '"'.$value.'",';
            } else {
                $type = 2;
                foreach($value as $subkey => $subvalue) {
                    if(is_string($subkey)) {
                        $this->columns[] = $subkey;
                    }
                    $this->values .= '"'.$subvalue.'",';
                }
                $this->values = substr($this->values,0,-1).'),(';
            }
        }
        $this->values = substr($this->values,0,-1);
        if($type == 1) {
            $this->values .= ')';
        } else {
            $this->values = substr($this->values,0,-1);
        }
        return $this;
    }

    public function getSql() {
        
        $sql = "INSERT INTO $this->table ";
        if($this->columns) {
            $this->columns = array_unique($this->columns); 
            $sql .= '(';
            foreach ($this->columns as $value) {
                $sql .= $value.',';
            }
            $sql = substr($sql, 0, -1).') ';
        }

        $sql .= 'VALUES '.$this->values;
        return $sql;
    }

    public function exec() {
        $this->sql = $this->getSql();

        $result = $this->pdo->exec($this->sql);

        if(!$result) {
            $error = $this->pdo->errorInfo();
            throw new Exception($error[2], 1);    
        } else {
            return $result;
        }
    }  
    
    public function clear() {
        $this->table = null;
        $this->values = null;
        $this->columns = null;
        $this->where()->clear();
        return $this;
    }
} 
