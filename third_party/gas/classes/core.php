<?php namespace Gas;

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
 * Gas\Core Class.
 *
 * @package     Gas ORM
 * @version     2.0.0
 */

use Gas\Data;
use Gas\Janitor;

class Core {

	/**
	 * @var  string  Global version value 
	 */
	const GAS_VERSION = '2.0.0';
	
	/**
	 * @var  object  Hold DB Instance
	 */
	public static $db;

	/**
	 * @var  object  Hold DB Util Instance
	 */
	public static $dbutil;

	/**
	 * @var  object  Hold DB Forge Instance
	 */
	public static $dbforge;

	/**
	 * @var  object  Empty data collection
	 */
	public static $data;

	/**
	 * @var  array  Hold DB AR properties
	 */
	public static $ar = array(
		'select'                => array(),
		'from'                  => array(),
		'join'                  => array(),
		'where'                 => array(),
		'like'                  => array(),
		'groupby'               => array(),
		'having'                => array(),
		'keys'                  => array(),
		'orderby'               => array(),
		'set'                   => array(),
		'wherein'               => array(),
		'aliased_tables'        => array(),
		'store_array'           => array(),
		'where_group_started'   => FALSE,
		'distinct'              => FALSE,
		'limit'                 => FALSE,
		'offset'                => FALSE,
		'order'                 => FALSE,
		'where_group_count'     => 0,
	);

	/**
	 * @var  array  Hold all defined action collections
	 */
	public static $dictionary = array(
		'transaction_pointer'  => array('trans_off', 
		                                'trans_start', 
		                                'trans_begin'),
		'transaction_executor' => array('trans_complete', 
		                                'trans_rollback', 
		                                'trans_commit'),
		'selector'             => array('select', 
		                                'select_max', 
		                                'select_min', 
		                                'select_avg', 
		                                'select_sum'),
		'condition'            => array('join', 
		                                'where', 
		                                'or_where', 
		                                'where_in', 
		                                'or_where_in', 
		                                'where_not_in', 
		                                'or_where_not_in', 
		                                'like', 
		                                'or_like', 
		                                'not_like', 
		                                'or_not_like', 
		                                'group_by', 
		                                'distinct', 
		                                'having', 
		                                'or_having', 
		                                'order_by', 
		                                'limit', 
		                                'set',
		                                'group_start',
		                                'or_group_start',
		                                'not_group_start',
		                                'group_end'),
		'executor'             => array('insert_string', 
		                                'update_string', 
		                                'insert', 
		                                'insert_batch', 
		                                'update', 
		                                'delete', 
		                                'get', 
		                                'empty_table', 
		                                'truncate', 
		                                'count_all', 
		                                'count_all_results', 
		                                'insert_id', 
		                                'affected_rows', 
		                                'platform', 
		                                'version', 
		                                'last_query'),
		'transaction_status'   => array('trans_status'),

	);

	/**
	 * @var  array  Hold all defined datatypes collections
	 */
	public static $datatypes = array(
		'numeric'  => array('TINYINT', 'SMALLINT',
		                    'MEDIUMINT', 'INT',
		                    'INT2', 'INT4',
		                    'INT8', 'INTEGER',
		                    'BIGINT', 'DECIMAL',
		                    'FLOAT', 'FLOAT4',
		                    'FLOAT8', 'DOUBLE',
		                    'REAL', 'BIT',
		                    'BOOL', 'SERIAL',
		                    'SERIAL8', 'BIGSERIAL',
		                    'DOUBLE PRECISION', 'NUMERIC'),
		'datetime' => array('DATE', 'DATETIME',
		                    'TIMESTAMP', 'TIMESTAMPTZ',
		                    'TIME', 'TIMETZ',
		                    'YEAR', 'INTERVAL'),
		'string'   => array('CHAR', 'BPCHAR',
		                    'CHARACTER', 'VARCHAR',
		                    'TINYTEXT', 'TEXT',
		                    'MEDIUMTEXT', 'LONGTEXT',
		                    'BINARY', 'VARBINARY',
		                    'TINYBLOB', 'MEDIUMBLOB',
		                    'LONGBLOB', 'ENUM',
		                    'SET'),
		'spatial'  => array('GEOMETRY', 'POINT',
		                    'LINESTRING', 'POLYGON',
		                    'MULTIPOINT', 'MULTILINESTRING',
		                    'MULTIPOLYGON', 'GEOMETRYCOLLECTION'),
	);

	/**
	 * @var  array  Hold all default datatypes collections
	 */
	public static $default_datatypes = array(
		'datetime' => 'DATETIME', 
		'string'   => 'TEXT', 
		'spatial'  => 'GEOMETRY', 
		'char'     => 'VARCHAR', 
		'numeric'  => 'TINYINT', 
		'auto'     => 'INT', 
		'int'      => 'INT', 
		'email'    => 'VARCHAR'
	);

	/**
	 * @var  array   Entity meta data repositories
	 */
	public static $entity_repository;

	/**
	 * @var  mixed   Hold tasks tree detail for every compile process
	 */
	public static $task_manager;

	/**
	 * @var  mixed   Hold compile result
	 */
	public static $thread_resource;

	/**
	 * @var  array   Hold monitored resorce stated
	 */
	public static $resource_state;

	/**
	 * @var  bool    Per-request cache flag
	 */
	private static $cache = TRUE;

	/**
	 * @var  mixed   Hold cached compile result collection
	 */
	private static $cached_resource = array();

	/**
	 * @var  array   Hold hashed recorder bundle 
	 */
	private static $cache_key;

	/**
	 * @var  bool    Core class initialization flag
	 */
	private static $init = FALSE;

	/**
	 * Constructor
	 * 
	 * @param  object Database instance
	 * @return void
	 */
	public function __construct(\CI_DB $DB)
	{
		if (self::init_status() == FALSE)
		{
			// Generate needed class name
			$forge = 'CI_DB_'.$DB->dbdriver.'_forge';
			$util  = 'CI_DB_'.$DB->dbdriver.'_utility';

			// Load the DB, DB Util and DB Forge instances
			static::$db      = $DB;
			static::$dbutil  = new $util();
			static::$dbforge = new $forge();

			// Generate new collection of needed properties
			static::$data              = new Data();
			static::$entity_repository = new Data();

			// Instantiate process has done now
			self::init();
		}
	}

	/**
	 * Set core initialization status
	 * 
	 * @return void
	 */
	public function init()
	{
		static::$init = TRUE;
	}

	/**
	 * Retrieve core initialization status
	 * 
	 * @return void
	 */
	public function init_status()
	{
		return static::$init;
	}

	/**
	 * Serve static calls for core instantiation
	 * 
	 * @param  object Database instance
	 * @return object
	 */
	public static function make(\CI_DB $DB)
	{
		return new static($DB);
	}

	/**
	 * Serve static calls for Data instantiation
	 * 
	 * @return object Empty data collection
	 */
	public static function data()
	{
		return static::$data;
	}

	/**
	 * Get all records based by default table name
	 *
	 * @param   object Gas Instance
	 * @return  object Gas Instance
	 */
	final public static function all($gas)
	{
		// Set table and return the execution result
		$gas::$recorder->set('get', array($gas->validate_table()->table));

		return self::_execute($gas);
	}

	/**
	 * Get record based by given primary key arguments
	 *
	 * @param   object Gas Instance
	 * @param   mixed
	 * @return  object Gas Instance
	 */
	final public static function find($gas, $args)
	{
		// Get WHERE IN clause and execute `find_where_in` method,
		// with appropriate arguments.
		$in   = Janitor::get_input(__METHOD__, $args, TRUE);

		// Sort and remove duplicate id
		// Sort the ids and remove same id
		$in = array_unique($in);
		sort($in);

		$gas  = self::compile($gas, 'where_in', array($gas->primary_key, $in));

		return self::all($gas);
	}

	/**
	 * Save (INSERT or UPDATE) the record
	 *
	 * @param   object Gas Instance
	 * @param   bool   Whether to perform validation or not
	 * @return  bool
	 */
	final public static function save($gas, $check = FALSE)
	{
		// If `check` set to TRUE, do a validation
		if ($check)
		{
			// Run _before_check and set initial valid mark
			$gas   = call_user_func(array($gas, '_before_check'));
			$valid = TRUE;

			// Do the validation rules, if run from CI environment
			if (function_exists('get_instance') && defined('CI_VERSION'))
			{
				$valid = self::_check($gas);

				if ( ! $valid) return FALSE;
			}
			
			// Run _after_check
			$gas = call_user_func(array($gas, '_after_check'));
		}

		// Run _before_save hook
		$gas = call_user_func(array($gas, '_before_save'));

		// Get the table and entries
		$table   = $gas->validate_table()->table;
		$pk      = $gas->primary_key;
		$entries = $gas->record->get('data');

		// Determine whether to perform INSERT or UPDATE operation
		// by checking `empty` property
		if ($gas->empty)
		{
			// INSERT
			$gas::$recorder->set('insert', array($table, $entries));
		}
		else
		{
			// Extract the identifier
			$identifier = array($pk => $entries[$pk]);
			unset($entries[$pk]);

			// UPDATE
			$gas::$recorder->set('update', array($table, $entries, $identifier));
		}

		// Perform requested saving method
		$save = self::_execute($gas);

		// Run _after_save hook
		$gas = call_user_func(array($gas, '_after_save'));

		return $save;
	}

	/**
	 * Serve `query` for ORM
	 *
	 * @param  string SQL statement
	 * @param  bool   Whether to do `query` or `simple_query` 
	 * @return mixed
	 */
	public static function query($sql, $simple = FALSE)
	{
		if (preg_match('/^SELECT([^)]+)(.*?)$/', $sql, $m) and count($m) == 3)
		{
			// Initial properties
			$result  = NULL;
			$tables  = array();
			$cached  = TRUE;

			// Split into each subquery
			$queries = array_filter(explode('SELECT', $sql));

			// Find corresponding resource name(s)
			foreach ($queries as $query)
			{
				if (preg_match('/FROM([^(]+)WHERE/', $query, $match) and count($match) == 2)
				{
					$tables[] = str_replace(array('`', ' '), '', $match[1]);
					
				}
			}

			// Start cache process
			$token = md5(serialize(array($sql)));
			self::cache_start(array($sql), FALSE);

			// Validate cache
			if (self::validate_cache($token))
			{
				foreach ($tables as $table)
				{
					if (self::changed_resource($table))
					{
						// If any of corresponding table involve, has been modified
						// Clear cached flag
						$cached = FALSE;

						break;
					}
				}
			}
			else
			{
				// No valid cache 
				$cached = FALSE;
			}

			// Determine to fetch the cache of perform fresh query onto DB
			if ($cached == TRUE)
			{
				$result = self::fetch_cache($token);
			}
			else
			{
				$result = self::$db->query($sql);
				self::cache_end($result, $token);
			}

			return $result;
		}

		// No need to process anything, 
		// Just forward the query into DB instance
		return ($simple) ? self::$db->simple_query($sql) : self::$db->query($sql);
	}

	/**
	 * Serve compile method for ORM
	 *
	 * @param  object Gas instance
	 * @param  string 
	 * @param  mixed 
	 * @return mixed
	 */
	public static function compile($gas, $method, $args)
	{
		// Interpret the method and merge argument, for internal method calls
		$internal_method = array('\\Gas\\Core', $method);
		$arguments       = array_merge(array($gas), $args);
		
		if (is_callable($internal_method, TRUE))
		{
			return call_user_func_array($internal_method, $arguments);
		}
	}

	/**
	 * Identify meta-data field spec from various type
	 *
	 * @param   object
	 * @param   string
	 * @param   string
	 * @return  array
	 */
	public static function identify_field($meta_data, $type = 'gas_field', $driver = '')
	{
		// Get name and raw type
		$field_gas_type = '';
		$field_name     = $meta_data->name;
		$field_raw_type = strtoupper($meta_data->type);

		// Determine whether this field is a primary key or not
		$is_key = (bool) $meta_data->primary_key;

		// Determine the global datatype
		foreach (self::$default_datatypes as $gas_type => $default)
		{
			if ($field_raw_type == $default)
			{
				$field_gas_type = $gas_type;

				break;
			}
		}

		// Determine the gas spec datatype
		if ($field_gas_type == '')
		{
			$field_gas_type = self::diagnostic($field_raw_type, 'datatypes');
		}

		// Set the `auto` annotation
		if ($is_key && $field_gas_type == 'int') $field_gas_type = 'auto';

		// Set the `char` annotation
		if ( ! strpos($field_name, 'email') && $field_gas_type == 'email') 
		{
			$field_gas_type = 'char';
		}
		
		if ($type == 'gas_field')
		{
			// Set Gas type and constraint spec
			$field_type   = $field_gas_type;
			$field_length = ($meta_data->max_length > 0) ? '['.$meta_data->max_length.']' : '';
		}
		elseif ($type == 'forge_field')
		{
			// Set Forge type and constraint spec
			if (self::$default_datatypes[$field_gas_type] != $field_raw_type)
			{
				$field_type = $field_raw_type;
			}
			else
			{
				$field_type = '';
			}

			// Set Forge constraint spec
			$field_length = ($meta_data->max_length > 0) ? $meta_data->max_length : 0;
		}
		else
		{
			$field_type   = '';
			$field_length = 0;
		}

		return array($field_name, $field_type, $field_length, $is_key);
	}

	/**
	 * Identify annotation
	 *
	 * @param   array
	 * @return  array
	 */
	public static function identify_annotation($annotation)
	{
		$boolean        = array('unsigned', 'null', 'auto_increment');
		$new_annotation = array();

		// Iterate the annotation and diagnose it based by datatypes collection
		foreach ($annotation as $type)
		{
			if (in_array($type, $boolean))
			{
				$new_annotation[$type] = TRUE;
			}
			elseif (self::diagnostic($type, 'datatypes') != '')
			{
				$new_annotation['type'] = $type;
			}
			elseif (is_numeric($type))
			{
				$new_annotation['constraint'] = (int) $type;
			}
		}

		return $new_annotation;
	}

	/**
	 * Diagnostic an item, against Core dictionary or datatypes
	 *
	 * @param   string
	 * @param   string
	 * @return  string
	 */
	public static function diagnostic($name, $source = 'dictionary')
	{
		// Determine an item based by selected collection
		foreach (self::$$source as $type => $nodes)
		{
			if (in_array($name, $nodes)) return $type;
		}

		return '';
	}

	/**
	 * Stop caching
	 *
	 * @return	void
	 */
	public function cache_flush()
	{
		// Flush the cached resources
		self::$cached_resource = array();

		return;
	}

	/**
	 * Writes cache pointer for each compile tasks
	 *
	 * @param   array
	 * @param   bool   Whether to save into global cache key or not
	 * @return  void
	 */
	public static function cache_start($task, $global = TRUE)
	{
		if ( ! self::cache_status()) return;

		// Hash the task, and assign it into cache key collection
		$key = md5(serialize($task));

		if ($global)
		{
			self::$cache_key = $key;
		}

		if ( ! array_key_exists($key, self::$cached_resource))
		{
			// Generate empty cache holder
			self::$cached_resource[$key] = NULL;
		}

		return;
	}
	
	/**
	 * Writes sibling hash for each resource's records
	 *
	 * @param   mixed    DB resource or any data
	 * @param   string   Cache key
	 * @return  void
	 */
	public static function cache_end($resource, $key = NULL)
	{
		if ( ! self::cache_status()) return;

		// Assign it into cache resource collection
		if (empty($key))
		{
			$key = self::$cache_key;
		}
	
		self::$cached_resource[$key] = $resource;

		return;
	}

	/**
	 * Validate cache state
	 * 
	 * @param   string  Cache key
	 * @return  bool
	 */
	public static function validate_cache($key = NULL)
	{
		if ( ! self::cache_status()) return;

		if (empty($key))
		{
			$key = self::$cache_key;
		}

		// Determine whether a resource is a valid cached 
		if (array_key_exists($key, self::$cached_resource) && ! empty(self::$cached_resource[$key]))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Fetching cache collections
	 * 
	 * @param   string  Cache key
	 * @return  mixed
	 */
	public static function fetch_cache($key = NULL)
	{
		if ( ! self::cache_status()) return;

		if (empty($key))
		{
			$key = self::$cache_key;
		}

		// Return the cached resource
		return self::$cached_resource[$key];
	}

	/**
	 * Get cache base configuration
	 *
	 * @access  public
	 * @return  bool
	 */
	public static function cache_status()
	{
		// Get the global caching flag
		return self::$cache;
	}

	/**
	 * Tracking resource state
	 *
	 * @param   string
	 * @param   string
	 * @return  void
	 */
	public static function track_resource($resource, $action)
	{
		// If it not exists, create an empty ones
		if ( ! isset(self::$resource_state[$resource]))
		{
			self::$resource_state[$resource] = array();
		} 

		// Set the action name
		$action = strtoupper($action);

		if ( ! isset(self::$resource_state[$resource][$action]))
		{
			// If the resource has not been monitored, create one
			self::$resource_state[$resource][$action] = 1;
		}
		else
		{
			// Otherwise, increase the counter number
			$action_count = self::$resource_state[$resource][$action];
			$action_count++;
			self::$resource_state[$resource][$action] = $action_count;
		}

		return;
	}

	/**
	 * Monitoring resource state
	 *
	 * @param   string
	 * @return  bool
	 */
	public static function changed_resource($resource)
	{
		// Return the resource state
		return isset(self::$resource_state[$resource]);
	}

	/**
	 * Reset Select properties within query builder instance
	 *
	 * @param   mixed
	 * @param   string
	 * @return  void
	 */
	public static function reset_query()
	{
		// Reset query and get the cached resource
		if (method_exists(self::$db, 'reset_query'))
		{
			self::$db->reset_query();
		}
		else
		{
			// Get all corresponding AR properties
			$ar = static::$ar;

			array_walk($ar, function ($default, $prop) use(&$ar) { 
				// Set AR property to default value
				$property            = 'ar_'.$prop;
				\Gas\Core::$db->$property = $default;
			});
		}

		return;
	}

	/**
	 * Generate the related entities of model/instance
	 *
	 * @param  object Gas instance
	 * @param  mixed  Gas relationship spec
	 * @return object Child Gas 
	 */
	public static function generate_entity($gas, $relationship, $resources = array())
	{
		// Get the relationship properties
		$path    = $relationship['path'];
		$child   = $relationship['child'];
		$single  = $relationship['single'];
		$options = $relationship['options'];
		$roadmap = explode('=', $path);

		// Now we are in serious business
		if ( ! empty($resources))
		{
			// Generate original identifier and entities holder
			$holder         = new Data();
			$original_table = $gas->table;
			$original_pk    = $gas->primary_key;
			$original_ids   = array();

			foreach ($resources as $resource)
			{
				// Populate the ids
				$original_ids[] = $resource[$original_pk];

				// Generate new token and empty holder for each original identifier
				$token = $original_table.':'.$original_pk.'.';
				$index = $resource[$original_pk];
				$holder->set("$token$index", array($index));
			}
		}

		// Generate the tuple
		$tuples = array();
		$index  = 0;
		$max    = count($roadmap) - 1;

		// The goal is to parse full path :
		//		Model\Foo=>Model\Bar<=Model\Lorem
		//
		// Into paired tuples like :
		//		Model\Foo>Model\Bar
		//		Model\Bar<Model\Lorem
		//
		// `>` or `<`, thus identify entity ownership
		do {
			$dirty_tuple = $roadmap[$index].$roadmap[$index+1];

			if (in_array(substr($dirty_tuple, 0, 1), array('>', '<')))
			{
				$tuples[] = substr($dirty_tuple, 1);
			}
			elseif (in_array(substr($dirty_tuple, -1), array('>', '<')))
			{
				$tuples[] = substr($dirty_tuple, 0, -1);
			}
			else
			{
				$tuples[] = $dirty_tuple;
			}

			$index++;
		} while ($index < $max);

		// Query holder
		$queries = array();
			
		// Then generate nested query to fetch each record entity
		foreach ($tuples as $level => $tuple)
		{
			list($domain, $key, $identifier) = self::generate_identifier($tuple);

			if ($level == 0)
			{
				if (isset($holder))
				{
					// This mean we really have a business
					$ids = $original_ids;
				}
				else
				{
					// We handle a single instance here
					$ids[] = $gas->record->get('data.'.$identifier);
				}

				$queries[] = array($domain, $key, '');
			}
			else
			{
				// Get previous tier index
				$lower_level = $queries[$level-1];

				if (isset($holder))
				{
					// If holder exists we need to also adding corresponding collumn
					$paired_cols = array_unique(array($identifier, $lower_level[1]));
					$lower_query = self::generate_clause($lower_level[0], $paired_cols, $lower_level[1], '');
					$queries[]   = array($domain, $key, $lower_query);
				}
				else
				{
					// Straight forward sub-query
					$lower_query = self::generate_clause($lower_level[0], $identifier, $lower_level[1], $lower_level[2]);
					$queries[]   = array($domain, $key, $lower_query);
				}
			}
		}

		// Parse the ids into string
		$ids = implode(', ', $ids);

		// Finalize entity generator
		if (count($queries) == 1)
		{
			// We handle one level of relationship, easy...
			$query     = array_shift($queries);
			$subquery  = $ids;
			$domain    = $query[0];
			$candidate = $query[1];
		}
		else
		{
			// If there was a holder, we have to do something first 
			if (isset($holder))
			{
				// Before doing anything, get as much info as possible
				$original_queries = $queries;

				// Parse necessary info
				$query     = array_pop($queries);
				$subquery  = sprintf(array_pop($query), $ids);
				$domain    = $query[0];
				$candidate = $query[1];

				// Doing effective sub-queries for `with` marked records
				foreach ($original_queries as $level => $original_query)
				{
					if (empty($original_query[2]))
					{
						// Take the identifier for further use
						$holder->set('identifier', $original_query[1]);
						$holder->set('ids', $original_ids);
					}
					else
					{
						$sql         = sprintf($original_query[2], implode(',', $holder->get('ids')));
						$subresults  = self::query($sql)->result_array();
						
						$identifier  = $original_query[1];
						$matched_id  = array();
						$subids      = array();

						foreach ($subresults as $index => $subresult)
						{
							$all_identifier = array_keys($subresult);
							$old_identifier = $holder->get('identifier');

							if (count($all_identifier) == 1)
							{
								$new_identifier = array_shift($all_identifier);
							}
							else
							{
								$new_identifier = array_diff($all_identifier, array($old_identifier));
								$new_identifier = array_shift($new_identifier);
							}

							$matcher_id     = $subresult[$old_identifier];
							$identifier_id  = $subresult[$new_identifier];

							foreach ($original_ids as $original_id)
							{
								if ( ! is_array($holder->get($token.$original_id)))
								{
									// Do nothing
								}
								elseif (is_array($holder->get($token.$original_id)))
								{
									// we have assoc ids
									if (in_array($matcher_id, $holder->get($token.$original_id)))
									{
										// Found matched identifier, save it to holder
										$matched_id[$original_id][] = $identifier_id;
									}
									else
									{
										// Generate empty values
										$matched_id[$original_id][] = NULL;
									}
								}
								else
								{
									// We've lost!
									throw new \InvalidArgumentException('empty_arguments:'. __METHOD__);
								}
							}

							// Save the identifier ids for further use
							$subids[] = $identifier_id;
						}

						// Make sure we have unique ids
						$subids = array_unique($subids);
						sort($subids);

						// Save above process into holder Data
						$holder->set('ids', $subids);
						$holder->set('identifier', $identifier);
						
						// Perform checking to assign each new identifier id
						// For further process, into each original ids
						foreach($matched_id as $id => $matched)
						{
							$holder->set($token.$id, array_filter($matched));
						}
					}
				}

				// Build the subquery
				$subquery = implode(', ', $holder->get('ids'));
			}
			else
			{
				// We have more than one tiers level, get the last...
				$query     = array_pop($queries);
				$subquery  = sprintf(array_pop($query), $ids);
				$domain    = $query[0];
				$candidate = $query[1];
			}
		}

		// Initiate empty additional queries
		$order_by = '';
		$limit    = '';
		
		// Initial select would be SELECT *
		// unless there are pre-query option to overide it
		$key = '*';

		// Do we have pre-process query options ?
		if (count($options) > 0)
		{
			$additional_queries = self::generate_options($options);

			// Do we need to overide the default key for SELECT clause ?
			if (array_key_exists('select', $additional_queries))
			{
				$key = $additional_queries['select'];

				// Lets make sure the identifier was included
				if ( ! in_array($candidate, $key)) $key[] = $candidate;
			}

			// Do we have ORDER BY clause ?
			if (array_key_exists('order_by', $additional_queries))
			{
				$order_by = ' ORDER BY `$domain`'.$additional_queries['order_by'];
			}

			// Do we have LIMIT clause ?
			if (array_key_exists('limit', $additional_queries))
			{
				$limit = ' LIMIT '.$additional_queries['limit'];
			}
		}

		// By now, we could generate the result
		$childs = array();
		$sql    = self::generate_clause($domain, $key, $candidate, $subquery);
		$res    = self::query($sql)->result_array();

		// In case we handle a holder...
		$matched_id = array();

		foreach ($res as $row)
		{
			// Hydrate child entities
			$child_instance        = new $child($row);
			$child_instance->empty = FALSE;

			// We have associative ids to check
			if (isset($holder))
			{
				foreach ($original_ids as $original_id)
				{
					// Get the identifier to check
					$matcher_id = $row[$holder->get('identifier')];

					// We have assoc ids to check against it
					if (in_array($matcher_id, $holder->get($token.$original_id)))
					{
						$matched_id[$original_id][] = $child_instance;
					}
					else
					{
						$matched_id[$original_id][] = NULL;
					}
				}
			}

			$childs[] = $child_instance;
		}
		
		// All done
		if (isset($holder))
		{
			$final_key = substr($token,0,-1);

			list($table, $identifier) = explode(':', $final_key);

			// Build the holder
			$holder->set('data', array_filter($matched_id));
			$holder->set('identifier', $identifier);
			$holder->set('ids', array_keys($matched_id));

			// Transfer into save place, then unset the holder
			$final_entities = $holder;

			unset($holder);

			return $final_entities;
		}
		else
		{
			return ($single) ? array_shift($childs) : $childs;
		}
		
	}

	/**
	 * Generate the all necessary identifier based a tuple
	 *
	 * @param  string  Tuple
	 * @return array   Domain, key and identifier
	 */
	public function generate_identifier($tuple)
	{
		if ( ! self::$entity_repository->get('tuples.'.$tuple))
		{
			// Initial empty
			$direction = '';

			if (strpos($tuple, '<') !== FALSE)
			{
				// We found this pattern direction :
				// 		Model\Foo<Model\Bar
				// This mean Model\Bar is OWNED by Model\Foo
				list($left, $right) = explode('<', $tuple);
				$direction = '<=';
			}
			elseif  (strpos($tuple, '>') !== FALSE)
			{
				// We found this pattern direction :
				// 		Model\Foo>Model\Bar
				// This mean Model\Foo is OWNED by Model\Bar
				list($left, $right) = explode('>', $tuple);
				$direction = '=>';
			}
			else
			{
				// We dont know this one, for sure
				throw new \LogicException('models_found_no_relations:'.$tuple);
			}

			// Build parent information
			$parent_model = $left::make();
			$parent_name  = '\\'.$parent_model->model();
			$parent_table = $parent_model->table;
			$parent_pk    = $parent_model->primary_key;

			// Build child information
			$child_model  = $right::make();
			$child_name   = '\\'.$child_model->model();
			$child_table  = $child_model->table;
			$child_pk     = $child_model->primary_key;

			// Generate `key` and `identifier` information for query processing
			switch ($direction)
			{
				case '<=':
					if (array_key_exists($parent_name, $child_model->foreign_key))
					{
						$key = $child_model->foreign_key[$parent_name];
					}
					else
					{
						$key = $parent_table.'_'.$parent_pk;
					}

					$identifier = $parent_pk;

					break;

				case '=>':
					$key = $child_pk;

					if (array_key_exists($child_name, $parent_model->foreign_key))
					{
						$identifier = $parent_model->foreign_key[$child_name];
					}
					else
					{
						$identifier = $child_table.'_'.$child_pk;
					}


					break;
			}

			// Build the tuple information
			$tuple_information = array($child_table, $key, $identifier);

			// Save onto entity repositories
			self::$entity_repository->set('tuples.'.$tuple, $tuple_information);
		}
		else
		{
			// Build the tuple information from entity repositories
			$tuple_information = self::$entity_repository->get('tuples.'.$tuple);
		}

		// Give them final tuple information
		return $tuple_information;
	}

	/**
	 * Generate the relationship option for pre-process queries
	 *
	 * @param  array  Gas relationship option spec
	 * @return array  Formatted option
	 */
	public function generate_options($options)
	{
		// Initiate new queries holder, and define allowable options
		$queries = array();
		$allowed = array('select', 'order_by', 'limit');

		// Loop over it
		foreach ($options as $option)
		{
			// Parse option annotation
			list($method, $args) = explode(':', $option);

			if ( ! in_array($method, $allowed))
			{
				// No valid method found
				continue;
			}
			else
			{
				// Casting the argument annotation
				// and do the pre-process 
				switch ($method)
				{
					case 'select':
						$select_statement = explode(',', $args);
						$queries[$method] = Janitor::arr_trim($select_statement);

						break;

					case 'limit':
						$queries[$method] = " 0, $args";

						break;
					
					case 'order_by':
						if (preg_match('/^([^\n]+)\[(.*?)\]$/', $args, $m) AND count($m) == 3)
						{
							$queries[$method] = "`$m[1]` strtoupper($m[2])";
						}

						break;
				}
			}
		}

		// Return the formatted queries options
		return $queries;
	}

	/**
	 * Generate SELECT %s FROM %s WHERE & IN (%s) clauses
	 * This is used by entity generator only (internal usage).
	 *
	 * @param  string  Table name
	 * @param  string  Key collumn name
	 * @param  string  Identifier collumn name
	 * @param  string  Either ids or subquery
	 * @return array   Formatted SQL clause
	 */
	public function generate_clause($domain, $key, $identifier, $ids = '')
	{
		// Generate subquery
		if ($key == '*')
		{
			// Do we have special selector char
			$pattern = "SELECT * FROM `$domain` WHERE `$domain`.`$identifier` IN (%s)";
		}
		elseif (is_array($key))
		{
			// Initial empty select
			$select = array();

			// We need to add protector and identifier
			foreach ($key as $collumn)
			{
				$select[] = "`$domain`.`$collumn`";
			}

			$key = implode(', ', $select);
			
			$pattern = "SELECT $key FROM `$domain` WHERE `$domain`.`$identifier` IN (%s)";
		}
		else
		{
			// Default pattern
			$pattern = "SELECT `$domain`.`$key` FROM `$domain` WHERE `$domain`.`$identifier` IN (%s)";
		}

		// Do we need to replace the string identifier
		// Either into sub-query or the real COLUMN value(s) ?
		if ( ! empty($ids))
		{
			$pattern = sprintf($pattern, $ids);
		}

		// Statement is ready
		return $pattern;
	}

	/**
	 * Execute the compilation command
	 *
	 * @param  object Gas instance
	 * @return object Finished Gas 
	 */
	protected static function _execute($gas)
	{
		// Build the tasks tree
		$tasks = self::_play_record($gas::$recorder);

		// Mark every compile process into our caching pool
		self::cache_start($tasks);

		// Prepare tasks bundle
		$engine    = get_class(self::$db);
		$compiler  = array('gas' => $gas);
		$executor  = static::$dictionary['executor'];
		$write     = array_slice($executor, 0, 6);
		$flag      = array('condition', 'selector');
		$bundle    = array('engine'   => $engine,
		                   'compiler' => $compiler,
		                   'write'    => $write,
		                   'flag'     => $flag);

		// Assign the task to the right person
		self::$task_manager = $bundle;

		// Lets dance...
		array_walk($tasks, function ($task_list, $key) use(&$tasks) { 
			// Only sort if there are valid task and the task manager hold its task list
			if ( ! empty($task_list) or ! empty(\Gas\Core::$task_manager))
			{
				array_walk($task_list, function ($arguments, $key, $task) use(&$task_list) {
					// Only do each task if the task manager hold its task list
					if ( ! empty(\Gas\Core::$task_manager)) 
					{
						// Diagnose the task
						$action = key($arguments);
						$args   = array_shift($arguments);
						$flag   = in_array($task, \Gas\Core::$task_manager['flag']);
						$write  = in_array($action, \Gas\Core::$task_manager['write']);
						$gas    = \Gas\Core::$task_manager['compiler']['gas'];
						$table  = $gas->table;

						if ( ! $flag)
						{
							// Find within cache resource collection
							if ($action == 'get' 
							    && \Gas\Core::validate_cache() 
							    && ! \Gas\Core::changed_resource($table))
							{
								$res = \Gas\Core::fetch_cache();
								\Gas\Core::reset_query();
							}
							else
							{
								$res = call_user_func_array(array(\Gas\Core::$db, $action), $args);
								\Gas\Core::cache_end($res);
							}

							// Post-processing query
							if ($write)
							{
								// Track the resource for any write operations
								\Gas\Core::track_resource($table, $action);
							}
							elseif ($action == 'get')
							{
								// Hydrate the gas instance
								$instances = array();
								$entities  = array();
								$ids       = array();
								$model     = $gas->model();
								$includes  = $gas->related->get('include', array());
								$relation  = $gas->meta->get('entities');

								// Do we have entities to eagerly-loaded?
								if (count($includes))
								{
									// Then generate new colleciton holder for it
									$tuples = new \Gas\Data();
								}

								// Get the array of fetched rows
								$results   = $res->result_array();

								// Generate the entitiy records
								foreach ($results as $result)
								{
									// Passed the result as record
									$instance        = new $model($result);
									$instance->empty = FALSE;

									foreach ($includes as $include)
									{
										if (array_key_exists($include, $relation))
										{
											$table      = $instance->table;
											$pk         = $instance->primary_key;
											$identifier = $instance->record->get('data.'.$pk);
											$concenate  = $table.':'.$pk.':'.$identifier;
											$tuple      = $relation[$include];

											if ($tuples->get('entities.'.$include))
											{
												// Retrieve this user entity
												$assoc_entities = $tuples->get('entities.'.$include);
											}
											else
											{
												$assoc_entities = \Gas\Core::generate_entity($gas, $tuple, $results);
												$tuples->set('entities.'.$include, $assoc_entities);
											}

											// Assign the included entity, respectively
											$entity = array_values(array_filter($assoc_entities->get('data.'.$identifier)));
											$instance->related->set('entities.'.$include, $entity);
										}
									}
								
									// Pool to instance holder and unset the instance
									$instances[] = $instance;
									unset($instance);
								}
								
								// Determine whether to return an instance or a collection of instance(s)
								$res = count($instances) > 1 ? $instances : array_shift($instances);
							}

							// Tell task manager to take a break, and fill the resource holder
							\Gas\Core::$task_manager    = array();
							\Gas\Core::$thread_resource = $res;
						}
						else
						{
							// Return the native DB driver method execution
							return call_user_func_array(array(\Gas\Core::$db, $action), $args);
						}
					}
				}, $key);
			}
		});

		// Get the result and immediately flush the temporary resource holder
		$resource = self::$thread_resource and self::$thread_resource = NULL;
		
		// The compilation is done, send the song to listen
		return $resource;
	}

	/**
	 * Generate the Gas tasks spec
	 *
	 * @param  Data  the recorder
	 * @return array task spec
	 */
	protected static function _play_record(Data $recorder)
	{
		// Prepare the tree and set recorder cursor
		$tasks      = array();
		$blank_disc = array_fill(0, count(self::$dictionary), array());
		$tasks      = array_combine(array_keys(self::$dictionary), $blank_disc);
		$recorder->rewind();

		// Iterate over the recorder and match against task dictionary
		while ($recorder->valid())
		{
			foreach (self::$dictionary as $type => $nodes)
			{
				if (in_array($recorder->key(), $nodes))
				{
					$arguments = array($recorder->key() => $recorder->current());
					array_push($tasks[$type], $arguments);
				}
			}

			$recorder->next();
		}

		return $tasks;
	}

	/**
	 * Check for validation process
	 *
	 * @param  object  Gas Instance
	 * @return bool 
	 */
	private static function _check($gas)
	{
		// Initial valid mark
		$valid  = TRUE;
		$errors = array();

		// Grab CI super object and load form validation
		$CI =& get_instance();
		$CI->load->library('form_validation');

		// Grab all necessary lang files
		$CI->lang->load('gas');
		$CI->lang->load('form_validation');

		// Grab the instance records, and set the POST (since CI validator only invoked by it)
		// if there are any POST data, save it temporarily
		$entries  = $gas->record->get('data');
		$old_post = $_POST;
		$_POST    = $entries;
		
		// Extract the rules, and separate beetween,
		// internal callback and CI validation rule
		foreach ($entries as $field => $entry)
		{
			// Get all necessary property for perform validation
			$label     = ucfirst(str_replace('_', ' ', $field));
			$rules     = $gas->meta->get($field.'.rules', '');
			$callbacks = $gas->meta->get($field.'.callbacks', '');

			// Set each field's rule respectively	
			$CI->form_validation->set_rules($field, $label, $rules);

			// First we will perform internal callbacks
			if ( ! empty($callbacks))
			{
				foreach ($callbacks as $callback)
				{
					// If defined callback not exists, show error
					if ( ! is_callable(array($gas, $callback)))
					{
						throw new \InvalidArgumentException($callback.' was invalid callback method');
					}

					// Check the callback result
					$success = call_user_func_array(array($gas, $callback), array($entry));
					$method  = substr($callback, 1);

					// If not success, grab the error message
					if ( ! $success)
					{
						// Default callbacks
						$datatype_errors = array('auto_check',
						                         'char_check',
						                         'date_check');

						// If it was default internal error, grab
						// corresponding Gas lang line
						if (in_array($method, $datatype_errors))
						{
							$error = $CI->lang->line($method);
						}
						else
						{
							if (FALSE === ($error = $CI->lang->line($callback)))
							{
								if (FALSE === ($error = $CI->lang->line($method)))
								{
									$error = $callback.' method error with no explanation for %s';
								}
							}
						}

						// Set callback error
						$errors[] = $callback;
						$gas->errors[$field] = sprintf($error, $label);
					}
				}
			}
		}

		// Perform CI validation
		if ($CI->form_validation->run() == FALSE)
		{
			// Set an error boundary
			$boundary = '<ERROR>';

			// Get each error 
			foreach ($entries as $field => $entry)
			{
				if (($error = $CI->form_validation->error($field, $boundary, $boundary)) and $error != '')
				{
					// Parse the error and put it into appropriate field
					$error               = str_replace($boundary, '', $error);
					$gas->errors[$field] = $error;
				}
			}

			$valid = FALSE;
		}

		// Combine internal callback result with CI validation result
		if (count($errors) > 0 or ! $valid)
		{
			$valid = FALSE;
		}

		// Validation has been done, set back the old post and return the validation result
		$_POST = $old_post;

		return $valid;
	}

	
	/**
	 * Overloading static method triggered when invoking special method.
	 *
	 * @param	string
	 * @param	array
	 * @return	mixed
	 */
	public static function __callStatic($name, $args)
	{
		// Defined DBAL and low-level query function
		$dbal  = array('forge', 'util');
		$query = array('query', 'simple_query');

		if (in_array($name, $dbal))
		{
			// Return corresponding component (DB Forge or DB Util)
			$dbal_component = 'db'.$name;

			return static::$$dbal_component;

		}
		elseif (in_array($name, $query))
		{
			return call_user_func_array(array(static::$db, $name), array(array_pop($args)));
			
		}
		elseif (preg_match('/^find_by_([^)]+)$/', $name, $m) && count($m) == 2)
		{
			// Get the instance, passed field and value for WHERE condition
			$gas   = array_shift($args);
			$field = $m[1];
			$value = array_shift($args);
			
			// Build the task onto the Gas instance
			$gas::$recorder->set('where', array($field, $value));
			
			return self::all($gas);
		}
		elseif (preg_match('/^(min|max|avg|sum)$/', $name, $m) && count($m) == 2)
		{
			// Get the instance, passed arguments for SELECT condition
			$gas   = array_shift($args);
			$type  = $m[1];
			$value = array_shift($args);
			$value = (empty($value)) ? $gas->primary_key : $value;
			
			// Build the task onto the Gas instance
			$gas::$recorder->set('select_'.$type, array($value));
			
			return self::all($gas);
		}
		elseif (preg_match('/^(first|last)$/', $name, $m) && count($m) == 2)
		{
			// Get the instance, passed arguments for ORDER BY condition
			$gas     = array_shift($args);
			$order   = ($m[1] == 'first') ? 'asc' : 'desc';
			$collumn = array_shift($args);
			$collumn = is_null($collumn) ? $gas->primary_key : $collumn;

			// Build the task onto the Gas instance
			$gas::$recorder->set('order_by', array($collumn, $order));
			$gas::$recorder->set('limit', array('1'));
			
			return self::all($gas);
		}
		elseif (($method_type = self::diagnostic($name)) && ! empty($method_type))
		{
			// Give appropriate return, based by each task node needs
			if ($method_type == 'condition' or $method_type == 'selector')
			{
				// Always, sanitize arguments
				$args = Janitor::get_input($name, $args, TRUE);

				// Ensure once, in case there are some deprecated method
				if ( ! is_callable(array(self::$db, $name)))
				{
					throw new \BadMethodCallException('['.$name.']Unknown method.');
				}
		    		
				// Build the task onto the Gas instance
				$gas = array_shift($args);
				$gas::$recorder->set($name, $args);

				return $gas;
			}
			elseif ($method_type == 'executor')
			{
				$executor  = static::$dictionary['executor'];
				$write     = array_slice($executor, 0, 6);
				$operation = array_slice($executor, 6, 4);
				$utility   = array_slice($executor, 10, 6);
				
				if (in_array($name, $utility))
				{
					// This not affected any row or any record
					return self::$db->$name();
				}
				else
				{
					// Always, sanitize arguments
					$args = Janitor::get_input($name, $args, TRUE);

					// Ensure once, in case there are some deprecated method
					if ( ! is_callable(array(self::$db, $name)))
					{
						throw new \BadMethodCallException('['.$name.']Unknown method.');
					}
			    		
					// Build the task onto the Gas instance
					$gas = array_shift($args);

					// Merge the table alongside with sent arguments
					$table    = $gas->validate_table()->table;
					$argument = array_unshift($args, $table);
					$gas::$recorder->set($name, $args);

					return self::_execute($gas);
				}
			}
		}
		else
		{
			// Last try check relationships
			$gas = array_shift($args);

			if (FALSE != ($relationship = $gas->meta->get('entities.'.$name)))
			{
				// Gotcha!
				// Check for any pre-process options
				if ( ! empty($args))
				{
					$relationship['options'] = array_merge($args, $relationship['options']);
				}

				return self::generate_entity($gas, $relationship);
			}
			
			// Good bye
			throw new \BadMethodCallException('['.$name.']Unknown method.');
		}
	}
}