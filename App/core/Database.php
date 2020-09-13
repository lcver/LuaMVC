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
        // Fetch values
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
        
        // Fetch all column
        $column = array_keys($data);
        $column = implode(',',$column);

        self::$querySQL['insert'] = "insert into ".self::$table."($column) values ($val)";
        
        $c = Factory::$connection;
        $result = $c->query(self::$querySQL['insert']);

        return $result;
    }

    public function where()
    {
        $this->querySQL['where'] = "key = value";
        $this->querySQL['where'] = "key = value and key = value";
    }

        
    public function get()
    {
        self::$querySQL = "select * form table where 1 = 1";
    }
    
}
