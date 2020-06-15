<?php
namespace App\Core;

class DatabaseConnection
{
    /**
     * Configuration database
     * @var String
     */
    protected $host;
    protected $user;
    protected $password;
    protected $dbname;
    protected static $connection = null;

    public function __construct($host,$user,$password,$dbname)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    /**
     * Destruct method
     * Check connection condition
     */
    // public function __destruct()
    // {
    //     if(self::$connection != null)
    //     {
    //         $this->closeConnection();
    //     }
    // }

    /**
     * Create Connection
     * @return Connection
     */
    public function createConnection()
    {
        self::$connection = new \mysqli($this->host,$this->user,$this->password,$this->dbname);
        if(self::$connection->connect_error){
            echo "Fail " . $thi->connection->connect_error;
        }
        return self::$connection;
    }

    /**
     * Close Connection
     */
    public function closeConnection()
    {
        if(self::$connection != null)
        {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
