<?php
namespace System;

require_once 'Utility.php';
class Database
{
    private $host = null;
    private $user = null;
    private $password = null;
    private $dbname = null;
    private $dbSqlConnection = null;

    private $queryStatement;
    private $error;

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->port_sql = getenv('DB_PORT_SQL');
        $this->dbname = getenv('DB_DATABASE');
        $this->user = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');

        try {
            $this->dbSqlConnection = new \PDO("mysql:host=$this->host;port=$this->port_sql;charset=utf8mb4;dbname=$this->dbname", $this->user, $this->password);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            exit($this->error);
        }
    }

    public function getConnectionSql()
    {
        return $this->dbSqlConnection;
    }
    

    /**
     *
     * @param [type] $sql - query to prepare
     * @return void
     */
    public function loadQuery($sql)
    {
        $this->queryStatement = $this->dbSqlConnection->prepare($sql);
    }

    /**
     *
     * @param [type] $array - list of parameters
     * @return void
     */
    public function executeQuery($array = null)
    {
        return $this->queryStatement->execute($array);
    }

    public function resultSet() 
    {
        $this->queryStatement->execute();
        return $this->queryStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->queryStatement->execute();
        return $this->queryStatement->fetch(\PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->queryStatement->rowCount();
    }

    public function getLastID($name)
    {
        // SELECT ID FROM USERS ORDER BY ID DESC LIMIT 1;
        $this->loadQuery('SELECT ID FROM ' . $name . ' ORDER BY ID DESC LIMIT 1');
        $this->executeQuery();
        $ID = $this->single();
        $ID = $ID['ID'];
        return $ID;
    }

    public function startTransaction()
    {
        $this->dbSqlConnection->beginTransaction();
    }

    public function endTransaction($command)
    {
        if($command == 'COMMIT')
            $this->dbSqlConnection->commit();
        else if($command == 'ROLLBACK')
            $this->dbSqlConnection->rollBack();
    }



    

    public function bind($param, $value, $type = null)
    {
        if(is_null($type))
        {
            switch(true)
            {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        
        $this->queryStatement->bindValue($param, $value, $type);
    }
        
}
?>