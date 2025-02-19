<?php
namespace Framework;

use PDO;
use Exception;
use PDOException;

class Database
{
    public $conn;
    /**
     * Database constructor.
     * @param array $config 
     */
    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];
        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
           
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    /**
     * Query the database
     * 
     * @param string $query
     * @return \PDOStatement
     * @throws PDOException
     * 
     */
    public function query($query , $params = [])
    {
        try{
            $statment = $this->conn->prepare($query);
            //bind the params
            foreach($params as $param => $value)
            {
                $statment->bindValue(':'.$param , $value);
            }
            $statment->execute();
            return $statment;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }
}