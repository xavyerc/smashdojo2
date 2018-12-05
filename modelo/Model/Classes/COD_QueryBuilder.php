<?php
/**
 * CodeDmx
 *
 * An open source application development framework for PHP
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 - 2016
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package CodeDmx
 * @author  https://github.com/mxra8
 * @copyright   Copyright (c) 2014 - 2016, Code Dmx (http://codedmx.com/)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    https://codedmx.com
 * @since   Version 1.0
 * @filesource
 *
 * CodeDmx Query Builder
 *
 * @category  Database Access
 * @author  https://github.com/mxra8
 * @copyright Copyright (c) 2016-2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version   1.0-master
 */
class COD_QueryBuilder
{
    /**
     * Static instance of self
     * 
     * @var $mysqli
     */
    protected $mysqli;

    /**
     * Database credentials
     * 
     * @var string
     */
    protected $username;
    protected $password;
    protected $db;
    protected $host;
    protected $port;
    protected $charset;

    /**
     * Table prefix
     * 
     * @var string
     */
    public static $prefix = '';

    /**
     * The SQL query
     * 
     * @var string
     */
    protected $query;

    /**
     * The SQL query options required after SELECT, INSERT, UPDATE or DELETE
     * 
     * @var array
     */
    protected $query_options = array();

    /**
     * Dynamic type list for order by condition value
     * 
     * @var array
     */
    protected $order_by = array();

    /**
     * Dynamic type list for group by condition value
     * 
     * @var array
     */
    protected $group_by = array();

    /**
     * An array that holds where joins
     * 
     * @var array
     */
    protected $join = array();

    /**
     * An array that holds where join ands
     *
     * @var array
     */
    protected $join_and = array();

    /**
     * An array that holds where conditions
     * 
     * @var array
     */
    protected $where = array();

    /**
     * An array that holds having conditions
     * 
     * @var array
     */
    protected $having = array();

    /**
     * The previously executed SQL query
     * 
     * @var string
     */
    protected $last_query;

    /**
     * Static instance of self
     * 
     * @var QueryBuilder
     */
    protected static $instance;

    /**
     * Dynamic type list for tempromary locking tables. 
     * 
     * @var array
     */
    protected $table_locks = array();
    
    /**
     * Variable which holds the current table lock method.
     * 
     * @var string
     */
    protected $table_lock_method = "READ";
    
    /**
     * Dynamic array that holds a combination of where condition/table data value types and parameter references
     * 
     * @var array
     */
    protected $bind_params = array(''); // Create the empty 0 index

    /**
     * Variable which holds an amount of returned rows during get / get_one / select queries
     * 
     * @var string
     */
    public $count = 0;

    /**
     * Variable which holds an amount of returned rows during get / get_one / select queries with with_total_count()
     * 
     * @var string
     */
    public $total_count = 0;

    /**
     * Variable which holds last statement error
     * 
     * @var string
     */
    protected $stmt_error;

    /**
     * Variable which holds last statement error code
     * 
     * @var int
     */
    protected $stmt_errno;

    /**
     * Is Subquery object
     * 
     * @var bool
     */
    protected $is_subquery = false;

    /**
     * Name of the auto increment column
     * 
     * @var int
     */
    protected $last_insert_id = null;

    /**
     * Column names for update when using on_duplicate method
     * 
     * @var array
     */
    protected $update_colums = null;

    /**
     * Return type: 
     * 'array' to return results as array
     * 'object' as object
     * 'json' as json string
     * 
     * @var string
     */
    public $return_type = 'array';

    /**
     * Should join() results be nested by table
     * 
     * @var bool
     */
    protected $nest_join = false;

    /**
     * Table name (with prefix, if used)
     * 
     * @var string 
     */
    private $table_name = '';

    /**
     * FOR UPDATE flag
     * 
     * @var bool
     */
    protected $for_update = false;

    /**
     * LOCK IN SHARE MODE flag
     * 
     * @var bool
     */
    protected $lock_in_share_mode = false;

    /**
     * Key field for Map()'ed result array
     * 
     * @var string
     */
    protected $map_key = null;

    /**
     * Variables for query execution tracing
     */
    protected $trace_start_q;
    protected $trace_enabled;
    protected $trace_strip_prefix;
    public $trace = array();

    /**
     * Per page limit for pagination
     *
     * @var int
     */
    public $page_limit = 20;

    /**
     * Variable that holds total pages count of last paginate() query
     *
     * @var int
     */
    public $total_pages = 0;

    /**
     * Method to initialize the class
     * 
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     * @param int $port
     * @param string $charset
     * 
     * @return QueryBuilder
     */
    public function __construct($host = null, $username = null, $password = null, $db = null, $port = 3306, $charset = 'utf8')
    {
        if ($host == null)
            return;
        
        $is_subquery = false;

        # if params were passed as array
        if (is_array($host)) 
        {
            foreach ($host as $key => $val) 
                $$key = $val;
        }
        # if host were set as mysqli socket
        if (is_object($host)) 
            $this->mysqli = $host;
        else
            $this->host = $host;

        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
        $this->port = $port;
        $this->charset = $charset;

        if ($is_subquery)
        {
            $this->is_subquery = true;
            return;
        }

        if (isset($prefix))
            $this->set_prefix($prefix);

        self::$instance = $this;
    }

    /**
     * A method to connect to the database
     * 
     * @throws Exception
     * @return void
     */
    public function connect()
    {
        if ($this->is_subquery)
            return;

        if (empty($this->host)) 
            throw new Exception('MySQL host is not set');

        $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->db, $this->port);

        if ($this->mysqli->connect_error) 
            throw new Exception('Connect Error ' . $this->mysqli->connect_errno . ': ' . $this->mysqli->connect_error, $this->mysqli->connect_errno);

        if ($this->charset) 
            $this->mysqli->set_charset($this->charset);
    }

    /**
     * A method to get mysqli object or create it in case needed
     * 
     * @return mysqli
     */
    public function mysqli()
    {
        if ( ! $this->mysqli) 
            $this->connect();

        return $this->mysqli;
    }

    /**
     * A method of returning the static instance to allow access to the
     * instantiated object from within another class.
     * Inheriting this class would require reloading connection info.
     *
     * @uses $db = QueryBuilder::get_instance();
     *
     * @return QueryBuilder Returns the current instance.
     */
    public static function get_instance()
    {
        return self::$instance;
    }

    /**
     * Reset states after an execution
     *
     * @return QueryBuilder Returns the current instance.
     */
    protected function reset()
    {
        if ($this->trace_enabled)
            $this->trace[] = array($this->last_query, (microtime(true) - $this->trace_start_q), $this->trace_get_caller());

        $this->where = array();
        $this->having = array();
        $this->join = array();
        $this->join_and = array();
        $this->order_by = array();
        $this->group_by = array();
        $this->bind_params = array(''); # Create the empty 0 index
        $this->query = null;
        $this->query_options = array();
        $this->return_type = 'array';
        $this->nest_join = false;
        $this->for_update = false;
        $this->lock_in_share_mode = false;
        $this->table_name = '';
        $this->last_insert_id = null;
        $this->update_colums = null;
        $this->map_key = null;
    }

    /**
     * Helper function to create an object with JSON return type
     *
     * @return QueryBuilder
     */
    public function json_builder()
    {
        $this->return_type = 'json';
        return $this;
    }

    /**
     * Helper function to create an object with array return type
     * Added for consistency as thats default output type
     *
     * @return QueryBuilder
     */
    public function array_builder()
    {
        $this->return_type = 'array';
        return $this;
    }

    /**
     * Helper function to create an object with object return type.
     *
     * @return QueryBuilder
     */
    public function object_builder()
    {
        $this->return_type = 'object';
        return $this;
    }

    /**
     * Method to set a prefix
     *
     * @param string $prefix
     * 
     * @return QueryBuilder
     */
    public function set_prefix($prefix = '')
    {
        self::$prefix = $prefix;
        return $this;
    }

    /**
     * Pushes a unprepared statement to the mysqli stack.
     * WARNING: Use with caution.
     * This method does not escape strings by default so make sure you'll never use it in production.
     * 
     * @author Jonas Barascu
     * @param [[Type]] $query [[Description]]
     */
    private function query_unprepared($query)
    {   
        $stmt = $this->mysqli()->query($query);

        if ( ! $stmt) 
            throw new Exception("Unprepared Query Failed, ERRNO: ".$this->mysqli()->errno." (".$this->mysqli()->error.")", $this->mysqli()->errno);
        
        return $stmt;
    }
    
    /**
     * Execute raw SQL query.
     *
     * @param string $query     
     * @param array  $bindParams 
     *
     * @return array 
     */
    public function raw_query($query, $bindParams = null)
    {
        $params = array(''); # Create the empty 0 index
        $this->query = $query;
        $stmt = $this->prepare_query();

        if (is_array($bindParams) === true) {
            foreach ($bindParams as $prop => $val) {
                $params[0] .= $this->determine_type($val);
                array_push($params, $bindParams[$prop]);
            }

            call_user_func_array(array($stmt, 'bind_param'), $this->ref_values($params));
        }

        $stmt->execute();
        $this->count = $stmt->affected_rows;
        $this->stmt_error = $stmt->error;
        $this->stmt_errno = $stmt->errno;
        $this->last_query = $this->replace_place_holders($this->query, $params);
        $res = $this->dynamic_bind_results($stmt);
        $this->reset();

        return $res;
    }

    /**
     * Helper function to execute raw SQL query and return only 1 row of results.
     * Note that function do not add 'limit 1' to the query by itself
     * Same idea as get_one()
     *
     * @param string $query      
     * @param array  $bindParams 
     *
     * @return array | null 
     */
    public function raw_query_one($query, $bindParams = null)
    {
        $res = $this->raw_query($query, $bindParams);
        if (is_array($res) && isset($res[0])) 
            return $res[0];

        return null;
    }

    /**
     * Helper function to execute raw SQL query and return only 1 column of results.
     * If 'limit 1' will be found, then string will be returned instead of array
     * Same idea as get_value()
     *
     * @param string $query      
     * @param array  $bindParams 
     *
     * @return mixed 
     */
    public function raw_query_value($query, $bindParams = null)
    {
        $res = $this->raw_query($query, $bindParams);
        if ( ! $res) 
            return null;

        $limit = preg_match('/limit\s+1;?$/i', $query);
        $key = key($res[0]);
        if (isset($res[0][$key]) && $limit == true) 
            return $res[0][$key];

        $newRes = Array();
        for ($i = 0; $i < $this->count; $i++) 
            $newRes[] = $res[$i][$key];

        return $newRes;
    }

    /**
     * A method to perform select query
     * 
     * @param string $query   
     * @param int|array $numRows 
     *
     * @return array 
     */
    public function query($query, $numRows = null)
    {
        $this->query = $query;
        $stmt = $this->build_query($numRows);
        $stmt->execute();
        $this->stmt_error = $stmt->error;
        $this->stmt_errno = $stmt->errno;
        $res = $this->dynamic_bind_results($stmt);
        $this->reset();

        return $res;
    }

    /**
     * Update query. Be sure to first call the "where" method.
     *
     * @param string $tableName 
     * @param array  $table_data 
     * @param int    $numRows   
     *
     * @return bool
     */
    public function update($tableName, $table_data, $numRows = null)
    {
        if ($this->is_subquery){
            return;
        }

        $this->query = "UPDATE " . self::$prefix . $tableName;

        $stmt = $this->build_query($numRows, $table_data);
        $status = $stmt->execute();
        $this->reset();
        $this->stmt_error = $stmt->error;
        $this->stmt_errno = $stmt->errno;
        $this->count = $stmt->affected_rows;

        return $status;
    }

    /**
     * Delete query. Call the "where" method first.
     *
     * @param string  $tableName 
     * @param int|array $numRows 
     *
     * @return bool
     */
    public function delete($tableName, $numRows = null)
    {
        if ($this->is_subquery){
            return;
        }

        $table = self::$prefix . $tableName;

        if (count($this->join)) 
            $this->query = "DELETE " . preg_replace('/.* (.*)/', '$1', $table) . " FROM " . $table;
        else
            $this->query = "DELETE FROM " . $table;

        $stmt = $this->build_query($numRows);
        $stmt->execute();
        $this->stmt_error = $stmt->error;
        $this->stmt_errno = $stmt->errno;
        $this->reset();

        return ($stmt->affected_rows > 0);
    }

    /**
     * This method allows you to specify multiple (method chaining optional) options for SQL queries.
     *
     * @param string|array $options The optons name of the query.
     * 
     * @throws Exception
     * @return QueryBuilder
     */
    public function set_query_option($options)
    {
        $allowedOptions = Array('ALL', 'DISTINCT', 'DISTINCTROW', 'HIGH_PRIORITY', 'STRAIGHTJOIN', 'SQL_SMALL_RESULT',
            'SQL_BIG_RESULT', 'SQL_BUFFER_RESULT', 'SQL_CACHE', 'SQL_NO_CACHE', 'SQL_CALC_FOUND_ROWS',
            'LOW_PRIORITY', 'IGNORE', 'QUICK', 'MYSQLINESTjOIN', 'FOR UPDATE', 'LOCK IN SHARE MODE');

        if ( ! is_array($options)) 
            $options = Array($options);

        foreach ($options as $option) 
        {
            $option = strtoupper($option);
            if ( ! in_array($option, $allowedOptions)) 
                throw new Exception('Wrong query option: ' . $option);

            if ($option == 'MYSQLINESTjOIN')
                $this->nest_join = true;
            elseif ($option == 'FOR UPDATE')
                $this->for_update = true;
            elseif ($option == 'LOCK IN SHARE MODE')
                $this->lock_in_share_mode = true;
            else
                $this->query_options[] = $option;
        }

        return $this;
    }

    /**
     * Function to enable SQL_CALC_FOUND_ROWS in the get queries
     *
     * @return QueryBuilder
     */
    public function with_total_count()
    {
        $this->set_query_option('SQL_CALC_FOUND_ROWS');
        return $this;
    }

    /**
     * A convenient SELECT * function.
     *
     * @param string  $tableName 
     * @param int|array $numRows 
     * @param string $columns 
     *
     * @return array
     */
    public function get($tableName, $numRows = null, $columns = '*')
    {
        
        if (empty($columns)) 
            $columns = '*';
        
        $column = is_array($columns) ? implode(', ', $columns) : $columns;

        if (strpos($tableName, '.') === false)
            $this->table_name = self::$prefix . $tableName;
        else
            $this->table_name = $tableName;
        
        $this->query = 'SELECT ' . implode(' ', $this->query_options) . ' ' .
            $column . " FROM " . $this->table_name;
        
        $stmt = $this->build_query($numRows);
        
        if ($this->is_subquery){
            return $this;
        }
            
        

        $stmt->execute();
        $this->stmt_error = $stmt->error;
        $this->stmt_errno = $stmt->errno;
        $res = $this->dynamic_bind_results($stmt);
        $this->reset();

        return $res;
    }

    /**
     * A convenient SELECT * function to get one record.
     *
     * @param string  $tableName 
     * @param string  $columns 
     * 
     * @return array 
     */
    public function get_one($tableName, $columns = '*')
    {
        $res = $this->get($tableName, 1, $columns);

        if ($res instanceof COD_QueryBuilder) 
            return $res;
        elseif (is_array($res) && isset($res[0])) 
            return $res[0];
        elseif ($res)
            return $res;

        return null;
    }

    /**
     * A convenient SELECT COLUMN function to get a single column value from one row
     *
     * @param string  $tableName 
     * @param string  $column    
     * @param int     $limit     
     *
     * @return mixed 
     */
    public function get_value($tableName, $column, $limit = 1)
    {
        $res = $this->array_builder()->get($tableName, $limit, "{$column} AS retval");

        if ( ! $res) 
            return null;

        if ($limit == 1) 
        {
            if (isset($res[0]["retval"])) 
                return $res[0]["retval"];

            return null;
        }

        $newRes = Array();
        for ($i = 0; $i < $this->count; $i++) 
            $newRes[] = $res[$i]['retval'];
        
        return $newRes;
    }

    /**
     * Insert method to add new row
     *
     * @param string $tableName 
     * @param array $insertData 
     *
     * @return bool 
     */
    public function insert($tableName, $insertData)
    {
        return $this->build_insert($tableName, $insertData, 'INSERT');
    }
    
    /**
     * Insert method to add several rows at once
     *
     * @param string $tableName 
     * @param array  $multiInsertData 
     * @param array  $dataKeys 
     *
     * @return bool|array 
     */
    public function insert_multi($tableName, array $multiInsertData, array $dataKeys = null)
    {
        # only auto-commit our inserts, if no transaction is currently running
        $autoCommit = (isset($this->transaction_in_progress) ? ! $this->transaction_in_progress : true);
        $ids = [];
        if($autoCommit)
            $this->start_transaction();

        foreach ($multiInsertData as $insertData) 
        {
            if ($dataKeys !== null) 
            {
                // apply column-names if given, else assume they're already given in the data
                $insertData = array_combine($dataKeys, $insertData);
            }
            $id = $this->insert($tableName, $insertData);
            if ( ! $id) 
            {
                if ($autoCommit)
                    $this->rollback();

                return false;
            }
            $ids[] = $id;
        }
        if($autoCommit)
            $this->commit();
        
        return $ids;
    }

    /**
     * Replace method to add new row
     *
     * @param string $tableName 
     * @param array $insertData 
     *
     * @return bool Boolean 
     */
    public function replace($tableName, $insertData)
    {
        return $this->build_insert($tableName, $insertData, 'REPLACE');
    }

    /**
     * A convenient function that returns TRUE if exists at least an element that
     * satisfy the where condition specified calling the "where" method before this one.
     *
     * @param string  $tableName 
     *
     * @return array 
     */
    public function has($tableName)
    {
        $this->get_one($tableName, '1');
        return $this->count >= 1;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND HAVING statements for SQL queries.
     *
     * @param string $havingProp 
     * @param mixed  $havingValue
     * @param string $operator
     *
     * @return QueryBuilder
     */
    public function having($havingProp, $havingValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        # forkaround for an old operation api
        if (is_array($havingValue) && ($key = key($havingValue)) != "0") {
            $operator = $key;
            $havingValue = $havingValue[$key];
        }

        if (count($this->having) == 0) {
            $cond = '';
        }

        $this->having[] = array($cond, $havingProp, $operator, $havingValue);
        return $this;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) OR HAVING statements for SQL queries.
     *
     * @param string $havingProp  
     * @param mixed  $havingValue 
     * @param string $operator 
     *
     * @return QueryBuilder
     */
    public function or_having($havingProp, $havingValue = null, $operator = null)
    {
        return $this->having($havingProp, $havingValue, $operator, 'OR');
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND WHERE statements for SQL queries.
     *
     * @param string $whereProp  
     * @param mixed  $whereValue 
     * @param string $operator 
     * @param string $cond 
     *
     * @return QueryBuilder
     */
    public function where($whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        # forkaround for an old operation api
        if (is_array($whereValue) && ($key = key($whereValue)) != "0") 
        {
            $operator = $key;
            $whereValue = $whereValue[$key];
        }
        if (count($this->where) == 0) {
            $cond = '';
        }
        $this->where[] = array($cond, $whereProp, $operator, $whereValue);
        return $this;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) OR WHERE statements for SQL queries.
     *
     * @param string $whereProp  
     * @param mixed  $whereValue 
     * @param string $operator 
     *
     * @return QueryBuilder
     */
    public function or_where($whereProp, $whereValue = 'DBNULL', $operator = '=')
    {
        return $this->where($whereProp, $whereValue, $operator, 'OR');
    }

    /**
     * This function store update column's name and column name of the
     * autoincrement column
     *
     * @param array $updateColumns
     * @param string $lastInsertId
     * 
     * @return QueryBuilder
     */
    public function on_duplicate($updateColumns, $lastInsertId = null)
    {
        $this->last_insert_id = $lastInsertId;
        $this->update_colums = $updateColumns;
        return $this;
    }
    
    /**
     * This method allows you to concatenate joins for the final SQL statement.
     *
     * @param string $join_table
     * @param string $join_condition
     * @param string $join_type
     * 
     * @throws Exception
     * @return QueryBuilder
     */
    public function join($join_table, $join_condition, $join_type = '')
    {
        $allowed_types = array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER');
        $join_type = strtoupper(trim($join_type));

        if ($join_type && ! in_array($join_type, $allowed_types)) 
            throw new Exception('Wrong JOIN type: ' . $join_type);

        if ( ! is_object($join_table))
            $join_table = self::$prefix . $join_table;

        $this->join[] = Array($join_type, $join_table, $join_condition);

        return $this;
    }
    
    /**
     * This is a basic method which allows you to import raw .CSV data into a table
     * Please check out http://dev.mysql.com/doc/refman/5.7/en/load-data.html for a valid .csv file.
     
     * @author Jonas Barascu (Noneatme)
     * @param string $import_table       
     * @param string $import_file        
     * @param string $import_settings    
     * 
     * @return boolean
     */
    public function load_data($import_table, $import_file, $import_settings = null)
    {
        # Check if the file exists
        if ( ! file_exists($import_file)) {
            throw new Exception("importCSV -> import_file ".$import_file." does not exists!");
            return;
        }
        
        # Define the default values
        # We will merge it later
        $settings = Array("field_char" => ';', "line_char" => PHP_EOL, "lines_to_ignore" => 1);
        
        # Check the import settings 
        if (gettype($import_settings) == "array")
            $settings = array_merge($settings, $import_settings);
    
        $table = self::$prefix . $import_table;
        
        # Add 1 more slash to every slash so maria will interpret it as a path
        $import_file = str_replace("\\", "\\\\", $import_file);  
        
        # Build SQL Syntax
        $sql_syntax = sprintf('LOAD DATA INFILE \'%s\' INTO TABLE %s', 
                    $import_file, $table);
        
        # FIELDS
        $sql_syntax .= sprintf(' FIELDS TERMINATED BY \'%s\'', $settings["field_char"]);
        if (isset($settings["fieldEnclosure"]))
            $sql_syntax .= sprintf(' ENCLOSED BY \'%s\'', $settings["fieldEnclosure"]);
        
        # LINES
        $sql_syntax .= sprintf(' LINES TERMINATED BY \'%s\'', $settings["line_char"]);
        if (isset($settings["lineStarting"])) 
            $sql_syntax .= sprintf(' STARTING BY \'%s\'', $settings["lineStarting"]);
            
        # IGNORE LINES
        $sql_syntax .= sprintf(' IGNORE %d LINES', $settings["lines_to_ignore"]);
    
        # Exceute the query unprepared because LOAD DATA only works with unprepared statements.
        $result = $this->query_unprepared($sql_syntax);

        return (bool) $result;
    }
    
    /**
     * This method is usefull for importing XML files into a specific table.
     * Check out the LOAD XML syntax for your MySQL server.
     *
     * @param  string  $import_table    
     * @param  string  $import_file     
     * @param  string  $import_settings 
     *                                                                                           
     * @return boolean 
     */
    public function load_xml($import_table, $import_file, $import_settings = null)
    {
        # Check if the file exists
        if ( ! file_exists($import_file)) {
            throw new Exception("load_xml: Import file does not exists");
            return;
        }
        
        # Create default values
        $settings = Array("lines_to_ignore" => 0);

        # Check the import settings 
        if (gettype($import_settings) == "array") {
            $settings = array_merge($settings, $import_settings);
        }

        # Add the prefix to the import table
        $table = self::$prefix . $import_table;
        
        # Add 1 more slash to every slash so maria will interpret it as a path
        $import_file = str_replace("\\", "\\\\", $import_file);  
        
        # Build SQL Syntax
        $sql_syntax = sprintf('LOAD XML INFILE \'%s\' INTO TABLE %s', 
                                 $import_file, $table);
        
        # FIELDS
        if (isset($settings["rowTag"])) 
            $sql_syntax .= sprintf(' ROWS IDENTIFIED BY \'%s\'', $settings["rowTag"]);
            
        # IGNORE LINES
        $sql_syntax .= sprintf(' IGNORE %d LINES', $settings["lines_to_ignore"]);
        
        # Exceute the query unprepared because LOAD XML only works with unprepared statements.
        $result = $this->query_unprepared($sql_syntax);

        return (bool) $result;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) ORDER BY statements for SQL queries.
     *
     * @param string $order_by_field 
     * @param string $order_by_direction 
     * @param array $custom_fields 
     * 
     * @throws Exception
     * @return QueryBuilder
     */
    public function order_by($order_by_field, $order_by_direction = "DESC", $customFields = null)
    {
        $allowedDirection = Array("ASC", "DESC");
        $order_by_direction = strtoupper(trim($order_by_direction));
        $order_by_field = preg_replace("/[^-a-z0-9\.\(\),_`\*\'\"]+/i", '', $order_by_field);

        # Add table prefix to order_by_field if needed.
        # FIXME: We are adding prefix only if table is enclosed into `` to distinguish aliases
        # from table names
        $order_by_field = preg_replace('/(\`)([`a-zA-Z0-9_]*\.)/', '\1' . self::$prefix . '\2', $order_by_field);

        if (empty($order_by_direction) || ! in_array($order_by_direction, $allowedDirection))
            throw new Exception('Wrong order direction: ' . $order_by_direction);

        if (is_array($customFields)) 
        {
            foreach ($customFields as $key => $value) 
                $customFields[$key] = preg_replace("/[^-a-z0-9\.\(\),_` ]+/i", '', $value);

            $order_by_field = 'FIELD (' . $order_by_field . ', "' . implode('","', $customFields) . '")';
        }

        $this->order_by[$order_by_field] = $order_by_direction;
        return $this;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) GROUP BY statements for SQL queries.
     *
     * @param string $group_by_field The name of the database field.
     *
     * @return QueryBuilder
     */
    public function group_by($group_by_field)
    {
        $group_by_field = preg_replace("/[^-a-z0-9\.\(\),_\*]+/i", '', $group_by_field);

        $this->group_by[] = $group_by_field;
        return $this;
    }
    
    /**
     * This method sets the current table lock method.
     * 
     * @param  string   $method The table lock method. Can be READ or WRITE.
     *                                                                 
     * @throws Exception
     * @return QueryBuilder
     */
    public function set_lock_method($method)
    {
        # Switch the uppercase string
        switch (strtoupper($method)) 
        {
            case "READ" || "WRITE":
                $this->table_lock_method = $method;
            break;
            default:
                throw new Exception("Bad lock type: Can be either READ or WRITE");
            break;
        }
        return $this;
    }
    
    /**
     * Locks a table for R/W action.
     * 
     * @param string  $table The table to be locked. Can be a table or a view.
     *                       
     * @throws Exception
     * @return QueryBuilder
     */
    public function lock($table)
    {
        # Main Query
        $this->query = "LOCK TABLES";
        
        # Is the table an array?
        if (gettype($table) == "array") 
        {
            foreach ($table as $key => $value) 
            {
                if (gettype($value) == "string") 
                {
                    if($key > 0) 
                        $this->query .= ",";

                    $this->query .= " ".self::$prefix.$value." ".$this->table_lock_method;
                }
            }
        }
        else
        {
            $table = self::$prefix . $table;
            
            $this->query = "LOCK TABLES ".$table." ".$this->table_lock_method;
        }

        # Exceute the query unprepared because LOCK only works with unprepared statements.
        $result = $this->query_unprepared($this->query);
        $errno  = $this->mysqli()->errno;
            
        $this->reset();

        if($result) 
            return true;
        else
            throw new Exception("Locking of table ".$table." failed", $errno);

        return false;
    }

    /**
     * Unlocks all tables in a database.
     * Also commits transactions.
     * 
     * @return QueryBuilder
     */
    public function unlock()
    {
        $this->query = "UNLOCK TABLES";

        # Exceute the query unprepared because UNLOCK and LOCK only works with unprepared statements.
        $result = $this->query_unprepared($this->query);
        $errno  = $this->mysqli()->errno;

        # Reset the query
        $this->reset();

        if($result) 
            return $this;
        else 
            throw new Exception("Unlocking of tables failed", $errno);
        
        return $this;
    }

    /**
     * This methods returns the ID of the last inserted item
     *
     * @return int 
     */
    public function get_insert_id()
    {
        return $this->mysqli()->insert_id;
    }

    /**
     * Escape harmful characters which might affect a query.
     *
     * @param string $str 
     *
     * @return string 
     */
    public function escape($str)
    {
        return $this->mysqli()->real_escape_string($str);
    }

    /**
     * Method to call mysqli->ping() to keep unused connections open on
     * long-running scripts, or to reconnect timed out connections (if php.ini has
     * global mysqli.reconnect set to true). Can't do this directly using object
     * since _mysqli is protected.
     *
     * @return bool 
     */
    public function ping()
    {
        return $this->mysqli()->ping();
    }

    /**
     * This method is needed for prepared statements. They require
     * the data type of the field to be bound with "i" s", etc.
     * This function takes the input, determines what type it is,
     * and then updates the param_type.
     *
     * @param mixed $item 
     *
     * @return string 
     */
    protected function determine_type($item)
    {
        switch (gettype($item)) 
        {
            case 'NULL':
            case 'string':
                return 's';
            break;

            case 'boolean':
            case 'integer':
                return 'i';
            break;

            case 'blob':
                return 'b';
            break;

            case 'double':
                return 'd';
            break;
        }
        return '';
    }

    /**
     * Helper function to add variables into bind parameters array
     *
     * @param string 
     */
    protected function bind_param($value)
    {
        $this->bind_params[0] .= $this->determine_type($value);
        array_push($this->bind_params, $value);
    }

    /**
     * Helper function to add variables into bind parameters array in bulk
     *
     * @param array $values 
     */
    protected function bind_params($values)
    {
        foreach ($values as $value)
            $this->bind_param($value);
    }

    /**
     * Helper function to add variables into bind parameters array and will return
     * its SQL part of the query according to operator in ' $operator ?' or
     * ' $operator ($subquery) ' formats
     *
     * @param string $operator
     * @param mixed $value 
     * 
     * @return string
     */
    protected function build_pair($operator, $value)
    {
        if ( ! is_object($value)) 
        {
            $this->bind_param($value);
            return ' ' . $operator . ' ? ';
        }
        $subQuery = $value->getSubQuery();
        
        $this->bind_params($subQuery['params']);

        return " " . $operator . " (" . $subQuery['query'] . ") " . $subQuery['alias'];
    }

    /**
     * Internal function to build and execute INSERT/REPLACE calls
     *
     * @param string $tableName 
     * @param array $insertData 
     * @param string $operation 
     *
     * @return bool 
     */
    private function build_insert($tableName, $insertData, $operation)
    {
        if ($this->is_subquery){
            return;
        }

        $this->query = $operation . " " . implode(' ', $this->query_options) . " INTO " . self::$prefix . $tableName;
        $stmt = $this->build_query(null, $insertData);
        $status = $stmt->execute();
        $this->stmt_error = $stmt->error;
        $this->stmt_errno = $stmt->errno;
        $have_on_duplicate = !empty ($this->update_colums);
        $this->reset();
        $this->count = $stmt->affected_rows;

        if ($stmt->affected_rows < 1) 
        {
            # in case of on_duplicate() usage, if no rows were inserted
            if ($status && $have_on_duplicate)
                return true;

            return false;
        }

        if ($stmt->insert_id > 0)
            return $stmt->insert_id;

        return true;
    }

    /**
     * Abstraction method that will compile the WHERE statement,
     * any passed update data, and the desired rows.
     * It then builds the SQL query.
     *
     * @param int|array $numRows 
     * @param array $tableData 
     *
     * @return mysqli_stmt 
     */
    protected function build_query($numRows = null, $table_data = null)
    {
        
        # $this->build_join_old();
        $this->build_join();
        $this->build_insert_query($table_data);
        $this->build_condition('WHERE', $this->where);
        $this->build_group_by();
        $this->build_condition('HAVING', $this->having);
        $this->build_order_by();
        $this->build_limit($numRows);
        $this->build_on_duplicate($table_data);
        
        if ($this->for_update)
            $this->query .= ' FOR UPDATE';
        if ($this->lock_in_share_mode)
            $this->query .= ' LOCK IN SHARE MODE';

        $this->last_query = $this->replace_place_holders($this->query, $this->bind_params);
        
        if ($this->is_subquery){
            return;
        }

        $stmt = $this->prepare_query();

        if (count($this->bind_params) > 1) 
            call_user_func_array(array($stmt, 'bind_param'), $this->ref_values($this->bind_params));

        return $stmt;
    }

    /**
     * This helper method takes care of prepared statements' "bind_result method
     * , when the number of variables to pass is unknown.
     *
     * @param mysqli_stmt $stmt 
     *
     * @return array 
     */
    protected function dynamic_bind_results(mysqli_stmt $stmt)
    {
        $parameters = array();
        $results = array();

        # SEE http://php.net/manual/en/mysqli-result.fetch-fields.php
        $mysqlLongType = 252;
        $shouldStoreResult = false;

        $meta = $stmt->result_metadata();

        # if $meta is false yet sqlstate is true, there's no sql error but the query is
        # most likely an update/insert/delete which doesn't produce any results
        if ( ! $meta && $stmt->sqlstate)
            return array();

        $row = array();
        while ($field = $meta->fetch_field()) 
        {
            if ($field->type == $mysqlLongType)
                $shouldStoreResult = true;

            if ($this->nest_join && $field->table != $this->table_name) 
            {
                $field->table = substr($field->table, strlen(self::$prefix));
                $row[$field->table][$field->name] = null;
                $parameters[] = & $row[$field->table][$field->name];
            } 
            else 
            {
                $row[$field->name] = null;
                $parameters[] = & $row[$field->name];
            }
        }

        if ($shouldStoreResult)
            $stmt->store_result();

        call_user_func_array(array($stmt, 'bind_result'), $parameters);

        $this->total_count = 0;
        $this->count = 0;

        while ($stmt->fetch()) 
        {
            if ($this->return_type == 'object') 
            {
                $result = new stdClass ();
                foreach ($row as $key => $val) 
                {
                    if (is_array($val)) 
                    {
                        $result->$key = new stdClass ();
                        foreach ($val as $k => $v) 
                            $result->$key->$k = $v;
                    } 
                    else 
                    {
                        $result->$key = $val;
                    }
                }
            } 
            else 
            {
                $result = array();
                foreach ($row as $key => $val) 
                {
                    if (is_array($val)) 
                    {
                        foreach ($val as $k => $v) 
                            $result[$key][$k] = $v;
                    } 
                    else 
                    {
                        $result[$key] = $val;
                    }
                }
            }

            $this->count++;
            if ($this->map_key)
                $results[$row[$this->map_key]] = count($row) > 2 ? $result : end($result);
            else
                array_push($results, $result);
        }

        if ($shouldStoreResult) 
            $stmt->free_result();

        $stmt->close();

        if ($this->mysqli()->more_results())
            $this->mysqli()->next_result();

        if (in_array('SQL_CALC_FOUND_ROWS', $this->query_options)) 
        {
            $stmt = $this->mysqli()->query('SELECT FOUND_ROWS()');
            $total_count = $stmt->fetch_row();
            $this->total_count = $total_count[0];
        }

        if ($this->return_type == 'json') 
            return json_encode($results);

        return $results;
    }

    /**
     * Abstraction method that will build an JOIN part of the query
     * 
     * @return void
     */
    protected function build_join_old()
    {
        if (empty($this->join)) 
            return;

        foreach ($this->join as $data) 
        {
            list ($join_type, $join_table, $join_condition) = $data;

            if (is_object($join_table))
                $joinStr = $this->build_pair("", $join_table);
            else 
                $joinStr = $join_table;

            $this->query .= " " . $join_type . " JOIN " . $joinStr . 
                (false !== stripos($join_condition, 'using') ? " " : " on ")
                . $join_condition;
        }
    }

    /**
     * Insert/Update query helper
     * 
     * @param array $table_data
     * @param array $tableColumns
     * @param bool $isInsert
     * 
     * @throws Exception
     */
    public function build_data_pairs($table_data, $tableColumns, $isInsert)
    {
        foreach ($tableColumns as $column) 
        {
            $value = $table_data[$column];

            if ( ! $isInsert) 
            {
                if(strpos($column,'.')===false)
                    $this->query .= "`" . $column . "` = ";
                else 
                    $this->query .= str_replace('.','.`',$column) . "` = ";
            }

            # Subquery value
            if ($value instanceof COD_QueryBuilder) 
            {
                $this->query .= $this->build_pair("", $value) . ", ";
                continue;
            }

            # Simple value
            if ( ! is_array($value)) 
            {
                $this->bind_param($value);
                $this->query .= '?, ';
                continue;
            }

            # Function value
            $key = key($value);
            $val = $value[$key];
            switch ($key) 
            {
                case '[I]':
                    $this->query .= $column . $val . ", ";
                break;
                case '[F]':
                    $this->query .= $val[0] . ", ";
                    if (!empty($val[1])) 
                        $this->bind_params($val[1]);
                break;
                case '[N]':
                    if ($val == null) 
                        $this->query .= "!" . $column . ", ";
                    else
                        $this->query .= "!" . $val . ", ";
                break;
                default:
                    throw new Exception("Wrong operation");
            }
        }
        $this->query = rtrim($this->query, ', ');
    }

    /**
     * Helper function to add variables into the query statement
     *
     * @param array $tableData
     */
    protected function build_on_duplicate($table_data)
    {
        if (is_array($this->update_colums) && ! empty($this->update_colums)) 
        {
            $this->query .= " ON DUPLICATE KEY UPDATE ";
            if ($this->last_insert_id)
                $this->query .= $this->last_insert_id . "=LAST_INSERT_ID (" . $this->last_insert_id . "), ";

            foreach ($this->update_colums as $key => $val) 
            {
                if (is_numeric($key)) 
                {
                    $this->update_colums[$val] = '';
                    unset($this->update_colums[$key]);
                } 
                else 
                {
                    $table_data[$key] = $val;
                }
            }
            $this->build_data_pairs($table_data, array_keys($this->update_colums), false);
        }
    }

    /**
     * Abstraction method that will build an INSERT or UPDATE part of the query
     * 
     * @param array $tableData
     */
    protected function build_insert_query($table_data)
    {
        if ( ! is_array($table_data))
            return;

        $isInsert = preg_match('/^[INSERT|REPLACE]/', $this->query);
        $dataColumns = array_keys($table_data);
        if ($isInsert) 
        {
            if (isset ($dataColumns[0]))
                $this->query .= ' (`' . implode($dataColumns, '`, `') . '`) ';
            $this->query .= ' VALUES (';
        } 
        else 
        {
            $this->query .= " SET ";
        }

        $this->build_data_pairs($table_data, $dataColumns, $isInsert);

        if ($isInsert) 
            $this->query .= ')';
    }

    /**
     * Abstraction method that will build the part of the WHERE conditions
     * 
     * @param string $operator
     * @param array $conditions
     */
    protected function build_condition($operator, $conditions)
    {
        if (empty($conditions))
            return;

        # Prepare the where portion of the query
        $this->query .= ' ' . $operator;

        foreach ($conditions as $cond) 
        {
            list ($concat, $varName, $operator, $val) = $cond;
            
            
            
            $this->query .= " " . $concat . " " . $varName;

            switch (strtolower($operator)) 
            {
                case 'not in':
                case 'in':
                    $comparison = ' ' . $operator . ' (';
                    if (is_object($val)) 
                    {
                        $comparison .= $this->build_pair("", $val);
                    } 
                    else 
                    {
                        foreach ($val as $v) 
                        {
                            $comparison .= ' ?,';
                            $this->bind_param($v);
                        }
                    }
                    $this->query .= rtrim($comparison, ',') . ' ) ';
                break;

                case 'not between':
                case 'between':
                    $this->query .= " $operator ? AND ? ";
                    $this->bind_params($val);
                break;

                case 'not exists':
                case 'exists':
                    $this->query.= $operator . $this->build_pair("", $val);
                break;

                default:
                    if (is_array($val)) 
                    {
                        $this->bind_params($val);
                    }
                    elseif ($val === null) 
                    {
                        $this->query .= ' ' . $operator . " NULL";
                    }
                    elseif ($val != 'DBNULL' || $val == '0') 
                    {
                        $this->query .= $this->build_pair($operator, $val);
                    }
                
            }
        }
    }

    /**
     * Abstraction method that will build the GROUP BY part of the WHERE statement
     *
     * @return void
     */
    protected function build_group_by()
    {
        if (empty($this->group_by)) 
            return;

        $this->query .= " GROUP BY ";

        foreach ($this->group_by as $key => $value) 
            $this->query .= $value . ", ";

        $this->query = rtrim($this->query, ', ') . " ";
    }

    /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @return void
     */
    protected function build_order_by()
    {
        if (empty($this->order_by)) 
            return;

        $this->query .= " ORDER BY ";
        foreach ($this->order_by as $prop => $value) 
        {
            if (strtolower(str_replace(" ", "", $prop)) == 'rand()')
                $this->query .= "rand(), ";
            else 
                $this->query .= $prop . " " . $value . ", ";
        }

        $this->query = rtrim($this->query, ', ') . " ";
    }

    /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @param int|array $numRows 
     * 
     * @return void
     */
    protected function build_limit($numRows)
    {
        if (!isset($numRows))
            return;

        if (is_array($numRows)) 
            $this->query .= ' LIMIT ' . (int) $numRows[0] . ', ' . (int) $numRows[1];
        else 
            $this->query .= ' LIMIT ' . (int) $numRows;
    }

    /**
     * Method attempts to prepare the SQL query and throws an error if there was a problem.
     *
     * @return mysqli_stmt
     */
    protected function prepare_query()
    {
        if ( ! $stmt = $this->mysqli()->prepare($this->query)) 
        {
            $msg = $this->mysqli()->error . " query: " . $this->query;
            $num = $this->mysqli()->errno;
            $this->reset();
            throw new Exception($msg, $num);
        }

        if ($this->trace_enabled)
            $this->trace_start_q = microtime(true);

        return $stmt;
    }

    /**
     * Close connection
     * 
     * @return void
     */
    public function __destruct()
    {
        if ($this->is_subquery){
            return;
        }

        if ($this->mysqli) 
        {
            $this->mysqli->close();
            $this->mysqli = null;
        }
    }

    /**
     * Referenced data array is required by mysqli since PHP 5.3+
     * 
     * @param array $arr
     *
     * @return array
     */
    protected function ref_values(array &$arr)
    {
        # Reference in the function arguments are required for HHVM to work
        # https://github.com/facebook/hhvm/issues/5155
        # Referenced data array is required by mysqli since PHP 5.3+
        if (strnatcmp(phpversion(), '5.3') >= 0) 
        {
            $refs = array();
            foreach ($arr as $key => $value) 
                $refs[$key] = & $arr[$key];
            
            return $refs;
        }
        return $arr;
    }

    /**
     * Function to replace ? with variables from bind variable
     * 
     * @param string $str
     * @param array $vals
     *
     * @return string
     */
    protected function replace_place_holders($str, $vals)
    {
        $i = 1;
        $newStr = "";

        if (empty($vals)) 
            return $str;

        while ($pos = strpos($str, "?")) 
        {
            $val = $vals[$i++];
            if (is_object($val)) 
                $val = '[object]';
            
            if ($val === null) 
                $val = 'NULL';
            
            $newStr .= substr($str, 0, $pos) . "'" . $val . "'";
            $str = substr($str, $pos + 1);
        }

        $newStr .= $str;
        return $newStr;
    }

    /**
     * Method returns last executed query
     *
     * @return string
     */
    public function get_last_query()
    {
        return $this->last_query;
    }

    /**
     * Method returns mysql error
     *
     * @return string
     */
    public function get_last_error()
    {
        if ( ! $this->mysqli) 
            return "mysqli is null";
        
        return trim($this->stmt_error . " " . $this->mysqli()->error);
    }

    /**
     * Method returns mysql error code
     * 
     * @return int
     */
    public function get_last_errno () 
    {
        return $this->stmt_errno;
    }

    /**
     * Mostly internal method to get query and its params out of subquery object
     * after get() and get_all()
     *
     * @return array
     */
    public function getSubQuery()
    {
        if ( ! $this->is_subquery) {
            return null;
        }
        
        array_shift($this->bind_params);
        $val = array('query' => $this->query,
            'params' => $this->bind_params,
            'alias' => $this->host
        );
        $this->reset();
        
        return $val;
    }
        
    /* Helper functions */

    /**
     * Method returns generated interval function as a string
     *
     * @param string $diff 
     * @param string $func
     *
     * @return string
     */
    public function interval($diff, $func = "NOW()")
    {
        $types = Array("s" => "second", "m" => "minute", "h" => "hour", "d" => "day", "M" => "month", "Y" => "year");
        $incr = '+';
        $items = '';
        $type = 'd';

        if ($diff && preg_match('/([+-]?) ?([0-9]+) ?([a-zA-Z]?)/', $diff, $matches)) 
        {
            if ( ! empty($matches[1])) 
                $incr = $matches[1];

            if ( ! empty($matches[2])) 
                $items = $matches[2];

            if ( ! empty($matches[3])) 
                $type = $matches[3];

            if ( ! in_array($type, array_keys($types))) 
                throw new Exception("invalid interval type in '{$diff}'");

            $func .= " " . $incr . " interval " . $items . " " . $types[$type] . " ";
        }

        return $func;
    }

    /**
     * Method returns generated interval function as an insert/update function
     *
     * @param string $diff 
     * @param string $func 
     *
     * @return array
     */
    public function now($diff = null, $func = "NOW()")
    {
        return array("[F]" => array($this->interval($diff, $func)));
    }

    /**
     * Method generates incremental function call
     * 
     * @param int $num 
     * 
     * @throws Exception
     * @return array
     */
    public function inc($num = 1)
    {
        if (!is_numeric($num)) 
            throw new Exception('Argument supplied to inc must be a number');
        
        return array("[I]" => "+" . $num);
    }

    /**
     * Method generates decrimental function call
     * 
     * @param int $num 
     * 
     * @return array
     */
    public function dec($num = 1)
    {
        if (!is_numeric($num)) 
            throw new Exception('Argument supplied to dec must be a number');
        
        return array("[I]" => "-" . $num);
    }

    /**
     * Method generates change boolean function call
     * 
     * @param string $col 
     * 
     * @return array
     */
    public function not($col = null)
    {
        return array("[N]" => (string) $col);
    }

    /**
     * Method generates user defined function call
     * 
     * @param string $expr
     * @param array $bindParams
     * 
     * @return array
     */
    public function func($expr, $bindParams = null)
    {
        return array("[F]" => array($expr, $bindParams));
    }

    /**
     * Method creates new querybuilder object for a subquery generation
     * 
     * @param string $subQueryAlias
     * 
     * @return QueryBuilder
     */
    public static function sub_query($subQueryAlias = "")
    {
        return new self(array('host' => $subQueryAlias, 'is_subquery' => true));
    }

    /**
     * Method returns a copy of a querybuilder subquery object
     *
     * @return QueryBuilder
     */
    public function copy()
    {
        $copy = unserialize(serialize($this));
        $copy->mysqli = null;
        return $copy;
    }

    /**
     * Begin a transaction
     *
     */
    public function start_Transaction()
    {
        $this->mysqli()->autocommit(false);
        $this->transaction_in_progress = true;
        register_shutdown_function(array($this, "transaction_status_check"));
    }

    /**
     * Transaction commit
     *
     */
    public function commit()
    {
        $result = $this->mysqli()->commit();
        $this->transaction_in_progress = false;
        $this->mysqli()->autocommit(true);
        return $result;
    }

    /**
     * Transaction rollback function
     *
     */
    public function rollback()
    {
        $result = $this->mysqli()->rollback();
        $this->transaction_in_progress = false;
        $this->mysqli()->autocommit(true);
        return $result;
    }

    /**
     * Shutdown handler to rollback uncommited operations in order to keep
     * atomic operations sane.
     *
     */
    public function transaction_status_check()
    {
        if ( ! $this->transaction_in_progress) 
            return;
        
        $this->rollback();
    }

    /**
     * Query exection time tracking switch
     *
     * @param bool $enabled 
     * @param string $stripPrefix 
     * 
     * @return QueryBuilder
     */
    public function set_trace($enabled, $stripPrefix = null)
    {
        $this->trace_enabled = $enabled;
        $this->trace_strip_prefix = $stripPrefix;
        return $this;
    }

    /**
     * Get where and what function was called for query stored in $this->db->trace
     *
     * @return string 
     */
    private function trace_get_caller()
    {
        $dd = debug_backtrace();
        $caller = next($dd);
        while (isset($caller) && $caller["file"] == __FILE__) 
            $caller = next($dd);
        

        return __CLASS__ . "->" . $caller["function"] . "() >>  file \"" .
            str_replace($this->trace_strip_prefix, '', $caller["file"]) . "\" line #" . $caller["line"] . " ";
    }

    /**
     * Method to check if needed table is created
     *
     * @param array $tables
     *
     * @return bool
     */
    public function table_exists($tables)
    {
        $tables = !is_array($tables) ? Array($tables) : $tables;
        $count = count($tables);
        if ($count == 0) 
            return false;
        

        foreach ($tables as $i => $value)
            $tables[$i] = self::$prefix . $value;

        $this->where('table_schema', $this->db);
        $this->where('table_name', $tables, 'in');
        $this->get('information_schema.tables', $count);

        return $this->count == $count;
    }

    /**
     * Return result as an associative array with $idField field value used as a record key
     * 
     * Array Returns an array($k => $v) if get(.."param1, param2"), array ($k => array ($v, $v)) otherwise
     * 
     * @param string $idField 
     *
     * @return QueryBuilder
     */
    public function map($idField)
    {
        $this->map_key = $idField;
        return $this;
    }

    /**
     * Pagination wraper to get()
     *
     * @param string  $table 
     * @param int $page 
     * @param array|string $fields 
     * 
     * @return array
     */
    public function paginate ($table, $page, $fields = null) 
    {
        $offset = $this->page_limit * ($page - 1);
        $res = $this->with_total_count()->get ($table, Array ($offset, $this->page_limit), $fields);
        $this->total_pages = ceil($this->total_count / $this->page_limit);

        return $res;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND WHERE statements for the join table on part of the SQL query.
     *
     * @param string $whereJoin  
     * @param string $whereProp  
     * @param mixed  $whereValue 
     *
     * @return dbWrapper
     */
    public function join_where($whereJoin, $whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        $this->join_and[$whereJoin][] = Array ($cond, $whereProp, $operator, $whereValue);
        return $this;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) OR WHERE statements for the join table on part of the SQL query.
     *
     * @uses $dbWrapper->joinWhere('user u', 'u.id', 7)->where('user u', 'u.title', 'MyTitle');
     *
     * @param string $whereJoin 
     * @param string $whereProp 
     * @param mixed  $whereValue
     *
     * @return dbWrapper
     */
    public function join_or_where($whereJoin, $whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        return $this->join_where($whereJoin, $whereProp, $whereValue, $operator, 'OR');
    }

    /**
     * Abstraction method that will build an JOIN part of the query
     */
    protected function build_join () 
    {
        if (empty ($this->join))
            return;

        foreach ($this->join as $data) 
        {
            list ($join_type,  $join_table, $join_condition) = $data;

            if (is_object ($join_table))
                $joinStr = $this->build_pair ("", $join_table);
            else
                $joinStr = $join_table;

            $this->query .= " " . $join_type. " JOIN " . $joinStr ." on " . $join_condition;

            # Add join and query
            if ( ! empty($this->join_and) && isset($this->join_and[$joinStr])) 
            {
                foreach($this->join_and[$joinStr] as $join_and_cond) 
                {
                    list ($concat, $varName, $operator, $val) = $join_and_cond;
                    $this->query .= " " . $concat ." " . $varName;
                    $this->condition_to_sql($operator, $val);
                }
            }
        }
    }

    /**
     * Convert a condition and value into the sql string
     * 
     * @param  String $operator 
     * @param  String $val    
     */
    private function condition_to_sql($operator, $val) 
    {
        switch (strtolower ($operator)) 
        {
            case 'not in':
            case 'in':
                $comparison = ' ' . $operator. ' (';
                if (is_object ($val)) 
                {
                    $comparison .= $this->build_pair ("", $val);
                } 
                else 
                {
                    foreach ($val as $v) 
                    {
                        $comparison .= ' ?,';
                        $this->bind_param ($v);
                    }
                }
                $this->query .= rtrim($comparison, ',').' ) ';
            break;

            case 'not between':
            case 'between':
                $this->query .= " $operator ? AND ? ";
                $this->bind_params ($val);
            break;

            case 'not exists':
            case 'exists':
                $this->query.= $operator . $this->build_pair ("", $val);
            break;

            default:
                if (is_array ($val))
                    $this->bind_params ($val);
                else if ($val === null)
                    $this->query .= $operator . " NULL";
                else if ($val != 'DBNULL' || $val == '0')
                    $this->query .= $this->build_pair($operator, $val);
        }
    }
}