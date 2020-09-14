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
