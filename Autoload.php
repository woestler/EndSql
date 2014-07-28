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
 

spl_autoload_register(array(new EndSql_Autoloader(), 'autoload'));

class EndSql_Autoloader
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->path = dirname(__FILE__).DIRECTORY_SEPARATOR.'library';
	}

	/**
	 * Autoloader
	 *
	 * @param string $class The name of the class to attempt to load.
	 */
	public function autoload($class)
	{
		if (strpos($class, 'EndSql') !== 0)
		{
			return;
		}

		$filename = $this->path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
		
		include $filename;
	}
}