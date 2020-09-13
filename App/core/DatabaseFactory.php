<?php
namespace App\Core;

/**
 * Initialized Class
 * @return Init
 */
use App\Core\DatabaseConnection;

class DatabaseFactory extends DatabaseConnection
{
    protected static $connection;

    /**
     * Destruct method
     * Check connection condition
     * 
     * @return destroy connection
     */
    // public function __destruct()
    // {
    //     if(self::$connection != null)
    //     {
    //         $this->closeConnection();
    //     }
    // }

    public static function __getInstance()
    {
        if(self::$connection == null)
        {
            $host = DBHOST;
            $user = DBUSER;
            $password = DBPASS;
            $dbname = DBNAME;

            self::$connection = new DatabaseConnection($host,$user,$password,$dbname);
            self::$connection = self::$connection->createConnection();
        }
        return self::$connection;
    }

    /**
     * Escape String
     * prevent SQL Injection
     * real_escape_string
     * 
     * @param String
     * @return String
     */
    public static function escapeString($params)
    {
        $result = self::$connection;
        $result = $result->real_escape_string($params);
        return $result;
    }
}
