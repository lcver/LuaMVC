<?php
namespace App\Core;
/**
 * Initialized Class
 * @return Init
 */
use App\Core\DatabaseFactory as Factory;

class Database extends DatabaseFactory #implements \App\Core\Query\QueryInterface
{

    private static $table;
    private static $querySQL = [];
    private $conn = null;


    public function __construct()
    {
        if( is_null($this->conn) )
            $this->conn = Factory::__getInstance();
    }

    public static function table($table)
    {
        self::$table = null;
        self::$table = $table;

        // var_dump(self::$table);
        return new self;
    }

    // insert
    /**
     * Insert Query
     * @param Array|String|Integer
     */
    public function insert(Array $data)
    {
        // Get data value
        $values = [];
        foreach ($data as $key => $value) {
            if( is_string($value) )
                { $values[] = "'".$value."'"; }

            elseif( is_numeric($value) )
                { $values[] = $value; }

            elseif( is_null($value))
                { $value[] = "''"; }
        }
        $val = implode(',', $values);

        // Set to null when have string 0
        $val = preg_replace("/''/",'null',$val);
        
        // Get data column
        $column = array_keys($data);
        $column = implode(',',$column);

        self::$querySQL['insert'] = "insert into ".self::$table."($column) values ($val)";
        
        $c = Factory::$connection;
        $result = $c->query(self::$querySQL['insert']);

        return $result;
    }

    /**
     * Insert Batch
     * with array has value
     * 
     * @param Array|MultipleArray
     */
    public function insertBatch(){}

    /**
     * Insert Id
     * Get last Id when store data
     * 
     * @param Array|String|Integer
     * @return id
     */
    public function insertId(Array $data)
    {
        /**
         * Filter escape string
         * get data value
         */
        $values = [];
        foreach ($data as $key => $value) {
            $value = Factory::escapeString($value);
            $values[] = is_string($value) ? "'$value'" : $value;
        }
        $values = implode(',', $values);


        /**
         * Get data column
         */
        $column = array_keys($data);
        $column = implode(',', $column);


        self::$querySQL['insertId'] = "insert into ".self::$table."($column) values ($values)";

        $result = null;


        /**
         * Prepare Connection
         */
        $c = Factory::$connection;
        if($c->query(self::$querySQL['insertId']))
            $result = $c->insert_id;


            return $result;
    }
    

    // update
    /**
     * Update Query
     * @param Array|String|Integer
     */
    public function update(Array $data)
    {
        foreach ($data as $key => $value) {

            // Escape String
            $value = Factory::escapeString($value);
            
            if ( is_string($value) ):
                $datas[] = "$key='$value'";

            elseif ( is_numeric($value) ):
                $datas[] = $key."=".$value;
            elseif( is_null($value) ):
                $datas[] = $value;
            endif;

        }
        $data = implode(',', $datas);

        // Set to null when have string 0
        $data = preg_replace("/''/", 'null', $data);
        
        // Set query update
        self::$querySQL = "update ".self::$table." set $data ".self::$querySQL['condition'];

        // Execute query
        $c = Factory::$connection;
        $result = $c->query(self::$querySQL);

        return $result;
    }
    
    /**
     * Update or insert
     * but insert when data hasn't exist same primary
     * 
     * @param Array|String|Integer
     */
    public function updateOrInsert(){}

    /**
     * Update batch
     * with array has value
     * 
     * @param Array|MultipleArray
     */
    public function updateBatch(){}

    /**
     * increment & Decrement
     * update when want to increase or decrease values
     * 
     * @return Integer
     */
    public function increment(){}
    public function decrement(){}
    
    // delete
    /**
     * Delete Query
     * @param Array|String|Integer
     */
    public function delete()
    {
        $querySQL = "delete from ".self::$table." ".self::$querySQL['condition'];
        // var_dump($querySQL); return true;

        $c = Factory::$connection;
        $result = $c->query($querySQL);

        return $result;
    }

    /**
     * Empty table & truncate
     * this action will delete all data from the table
     * 
     * @param String
     */
    public function emptyTable(){}
    public function truncate(){}

    // Join
    public function join($table)
    {
        /**
         * Rewrite data table
         */
        if( preg_match('/join/', self::$table) ):
            self::$table .= self::$querySQL['condition']." join ".$table." ";
        else:
            self::$table = self::$table." join ".$table." ";
        endif;

        return new self;

    }

    /**
     * Condition Join
     * use on clause when add join query
     * 
     * @param String|Integer
     */
    public function on($condition1, $condition2=null)
    {
        if( is_array($condition1) )
        {
            foreach ($condition1 as $key => $value) {
                if( is_array($value) ):
                    $i=0;
                    for ($i=0; $i < count($value) ; $i++) { 
                        $keyVal = $value[$i]; $i++;
                        $condVal= $value[$i]; $i++;
                        $valVal = $value[$i];

                        $values[] = $keyVal.$condVal.$valVal;
                    }
                else:
                    $values[] = $key."=".$value;
                endif;
            }
            $values = implode(" and ", $values);

        } elseif ( is_string($condition1) )
        {
            $values = $condition."=".$condition2;
        }

        self::$querySQL['condition'] = 'on '.$values;

        return new self;
    }

    /**
     * LeftJoin & RightJoin Query
     * @param TableName
     */
    public function leftJoin(){}
    public function rightJoin(){}


    // mysql
    /**
     * Num rows || change to field count
     * return count data from rows table
     * 
     * @param Query
     */
    public function field_count(){}

    /**
     * Affected rows
     * get data by last action
     * 
     * @param Query
     */
    public function affected_rows()
    {
        $result = $this->conn->affected_rows;
        return $result;
    }

// -------------------------------------------------------------------------------------------------------
    // where clause
    /**
     * Where
     * 
     * key
     * @param String|Array|Int
     * condition
     * @param Null|String|Int|Operator
     * value
     * @param Null|String|Int
     * 
     */
    public function where($key, $cond=null, $value=null)
    {
        $temp_condition = null;

        /**
         * Const Operator
         * to comparing data
         */
        $operator = ['=','==','>','<>','<','like','!=','!==','%'];
        $countOperator = count($operator);

        /**
         * Filter if key is Array or String
         * example :
         *      Array = where(['condition1'=>'condition2']);
         *      String = where('condition1','condition2');
         * 
         */
        if( is_array($key) )
        {
            foreach ($key as $key => $value) {

                /**
                 * Filtering value
                 * if type data value is Array
                 * example :
                 *      where([
                 *          ['condition1'=>'condition2'],
                 *          ['condition3'=>'condition4']
                 *      ])
                 */
                if( is_array($value) ):

                    for ($i=0; $i < count($value) ; $i++) { 
                        $keyVal = $value[$i]; $i++;
                        $condVal= $value[$i]; $i++;
                        $valVal = $value[$i];

                        $values[] = $keyVal.$condVal.$valVal;
                    }

                else :
                    $values[] = $key."=".$value;
                endif;
                
            }
            $temp_condition = implode(' and ', $values);

        } elseif( is_string($key) )
        {
            /**
             * Filter operator if using operator 
             * example :
             *      where('condition1','!=','condition2');
             */
            $a=0;
            while( $countOperator > $a ):
                $case = $cond == $operator[$a];
                    if($case) break;

                $a++;
            endwhile;

            if( $case ) :
                $value = is_string($value) ? "'".$value."'" : $value;
                $temp_condition = "$key $cond $value";
            else :
                $value = is_string($cond) ? "'".$cond."'" : $cond;
                $temp_condition = $key.$operator[0].$value;
            endif;
        }

        self::$querySQL['condition'] = "where ".$temp_condition;

        return new self;
    }

    
    /**
     * Where in
     * condition when data was found similarity
     * 
     * Where not in
     * condition when data wasn't found similarity
     */
    public function whereIn(){}
    public function whereNotIn(){}

    /**
     * Where between
     * condition when data between the params
     * 
     * Where not between
     * condition data where data isn't not in between 
     */
    public function whereBetween(){}
    public function whereNotBetween(){}

    /**
     * Where null
     * condition when data or field is null
     * 
     * Where not null
     * condition data or field isn't null
     */
    public function whereNull(){}
    public function whereNotNull(){}

    /**
     * Where date
     * condition check by date
     */
    public function whereDate(){}
    
    /**
     * Where day
     * condition check by day
     */
    public function whereDay(){}

    /**
     * Where year
     * condition check by year
     */
    public function whereYear(){}
    
    /**
     * Where time
     * condition check by time
     */
    public function whereTime(){}

    /**
     * Where column
     * condition check by column
     */
    public function whereColumn(){}
    
    /**
     * Where Exist
     * condition check when data is exist
     */
    public function whereExist(){}

    // like & not like
    public function like(){}
    public function notLike(){}

    // having clause
    public function having(){}
    public function havingIn(){}
    public function havingNotIn(){}
    public function havingLike(){}
    public function notHavingLike(){}

    // aggregates
    public function min(){}
    public function max(){}
    public function average(){}
    public function sum(){}
    public function count(){}

    // ofset & limit
    public function limit(){}
    public function offset(){}
    public function latest(){}
    public function first(){}

    // order & group
    public function groupBy(){}
    public function orderBy(){}

    // free sql
    public function whereRaw(){}
    public function when(){}

    // select column
    public function select(Array $data)
    {
        self::$querySQL['column'] = implode(',', $data);

        return new self;
    }

    // multiple query
    public function union(){}

    // run query
    public function get()
    {
        /**
         * Clean previous data 
         */
        self::$querySQL['condition'] = empty(self::$querySQL['condition']) ? "" : self::$querySQL['condition'];
        self::$querySQL['column'] = empty(self::$querySQL['column']) ? "*" : self::$querySQL['column'];
        if(isset(self::$querySQL['column']))
        {
            $column = self::$querySQL['column'];
            unset(self::$querySQL['column']);
        }

        /**
         * Connect to database
         * set query to fetch data
         * 
         */
        $table = self::$table;
        $querySQL = "select ".$column." from $table ".self::$querySQL['condition'];

        /**
         * Fetch data
         * @return data
         */
        $res = $this->conn->query($querySQL);


        /**
         * Filter Data
         * 
         * if null return null
         * else return data
         * 
         * @return Null
         * or
         * @return Data
         */
        if( $res )
        {
            if( $res->num_rows<>1 ):

                for ($i=0; $i < $res->num_rows ; $i++) { 
                    $result[] = $res->fetch_assoc();
                }

            elseif ( $res->num_rows==1 ):
                $result = $res->fetch_assoc();
            else:
                $result = null;
            endif;
        } else {
            return $result = null;            
        }

        return isset($result) ? $result : null;
    }
    
}
