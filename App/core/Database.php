<?php 
namespace App\Core;
/**
 * Initialized Class
 * @return Init
 */
use App\Core\DatabaseFactory as Factory;

class Database extends DatabaseFactory #implements App\Core\Query\QueryInterface
{

    private static $table;
    private static $querySQL = array();
    protected $conn = null;


    public function __construct()
    {
        $this->conn = DatabaseFactory::__getInstance();
    }

    public static function table($table)
    {
        self::$table = null;
        self::$table = $table;

        // var_dump(self::$table);
        return new self;
    }

    public function insert(Array $data)
    {
        // Fetch values
        $values = [];
        foreach ($data as $key => $value) {
            if(is_string($value))
                {$values[] = "'".$value."'";}
            elseif(is_numeric($value))
                {$values[] = $value;}
        }
        $values = implode(',', $values);

        // Fetch all column
        $column = array_keys($data);
        $column = implode(',',$column);

        self::$querySQL['insert'] = "insert into ".self::$table."($column) values ($values)";
        echo self::$querySQL['insert'];
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
