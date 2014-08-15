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
class EndSql {

	private static $pdoInstance;

	private static $dbInstance;

	const JOIN_INNER          = 'INNER';

    const JOIN_OUTER          = 'OUTER';

    const JOIN_LEFT           = 'LEFT';

    const JOIN_RIGHT          = 'RIGHT';

    const SQL_OR              = 'OR';
    
	const SQL_AND             = 'AND';


	private function __construct() {}

	public static function getInstance() {
		if(!self::$pdoInstance)
			self::$pdoInstance = self::pdoInstance();
		if(!self::$dbInstance)
			self::$dbInstance  = new self;
		return self::$dbInstance;
	}

	protected static function pdoInstance() {
		if(self::$pdoInstance) {
			return self::$pdoInstance;
		}
		$config = require(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."local.php");
		self::$pdoInstance = new PDO($config["dsn"],$config['user'],$config['password']);
		return self::$pdoInstance;
	}

	public function getPdo() {
	    if(self::$pdoInstance) {
	    	return self::$pdoInstance;
	    } else {
            self::$pdoInstance = self::pdoInstance();
            return self::$pdoInstance;
	    }
	}

	public function insert($tableName = NULL) {
		return new EndSql_Sql_Insert(self::$pdoInstance,$tableName);
	}

	public function delete($tableName = NULL) {
		return new EndSql_Sql_Delete(self::$pdoInstance,$tableName);
	}

	public function update($tableName = NULL) {
		return new EndSql_Sql_Update(self::$pdoInstance,$tableName);
	}

	public function select($tableName = NULL) {
		return new EndSql_Sql_Select(self::$pdoInstance,$tableName);
	}

	public function query($sql) {
        $resultSet = self::$pdoInstance->query($sql);
        if(!$resultSet)
            return false;
        return $resultSet;
	}

	public function getLastError() {
		return self::$pdoInstance->errorInfo();
	}
}




