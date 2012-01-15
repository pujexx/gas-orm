<?php namespace Model;

/**
 * CodeIgniter Gas ORM Packages
 *
 * A lighweight and easy-to-use ORM for CodeIgniter
 * 
 * This packages intend to use as semi-native ORM for CI, 
 * based on the ActiveRecord pattern. This ORM uses CI stan-
 * dard DB utility packages also validation class.
 *
 * @package     Gas ORM
 * @subpackage  Gas ORM Model
 * @category    ORM
 * @version     2.0.0
 * @author      Taufan Aditya A.K.A Toopay
 * @link        http://gasorm-doc.taufanaditya.com/
 * @license     BSD
 *
 * =================================================================================================
 * =================================================================================================
 * Copyright 2011 Taufan Aditya a.k.a toopay. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 * 
 * 1. Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * 
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY Taufan Aditya a.k.a toopay ‘’AS IS’’ AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL Taufan Aditya a.k.a toopay OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * The views and conclusions contained in the software and documentation are those of the
 * authors and should not be interpreted as representing official policies, either expressed
 * or implied, of Taufan Aditya a.k.a toopay.
 * =================================================================================================
 * =================================================================================================
 */

/**
 * Model\Role_user Class.
 *
 * This dummy role_user model, serve all test corresponding with Role_user table and its relation
 *
 * @package     Gas ORM
 * @version     2.0.0
 */

use \Gas\Core;
use \Gas\ORM;

class Role_user extends ORM {

	/**
	 * @var  string Table name
	 */
	public $table = 'r_u';

	/**
	 * @var  array  Composite keys (Foreign Keys)
	 */
	public $foreign_key = array('\\Model\\User' => 'u_id',
	                            '\\Model\\Role' => 'r_id');

	/**
	 * Set up method for unit testing
	 */
	public static function setUp()
	{
		// Generate a reflection
		$reflection  = self::make();
		$table       = $reflection->validate_table()->table;
		$foreign_key = $reflection->foreign_key;

		// Drop if table exists
		self::forge()->drop_table($table);

		//Build the new one now
		foreach ($reflection->meta->get('fields') as $field => $rule) 
		{
			$annotation     = $rule['annotations'];
			$fields[$field] = Core::identify_annotation($annotation);
		}

		self::forge()->add_field($fields);

		foreach ($foreign_key as $key)
		{
			self::forge()->add_key($key, TRUE);
		}

		self::forge()->create_table($table);

		// Then add some dummy data
		$data = array(
		    array('u_id' => 1, 'r_id' => 2),
		    array('u_id' => 1, 'r_id' => 3),
		    array('u_id' => 2, 'r_id' => 1),
		    array('u_id' => 2, 'r_id' => 2),
		    array('u_id' => 3, 'r_id' => 2),
		    array('u_id' => 4, 'r_id' => 3),
		);

		self::insert_batch($data); 
	}

	function _init()
	{
		// Define relationships
		self::$relationships = array(
			'user' => ORM::belongs_to('\\Model\\User'),
			'role' => ORM::belongs_to('\\Model\\Role'),
		);

		// Define fields definition
		self::$fields = array(
			'u_id'     => ORM::field('int[3]'),
			'r_id'     => ORM::field('int[3]'),
		);
	}
}