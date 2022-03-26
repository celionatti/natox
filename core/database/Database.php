<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore\database;

use NatoxCore\Config;
use NatoxCore\Application;

/**
 * Class Database
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Database
{
    protected $_dbh, $_results, $_lastInsertId, $_rowCount = 0, $_fetchType = \PDO::FETCH_OBJ, $_class, $_error = false;
    protected $_stmt;
    protected static $_db;
    // private static $db_activate = Config::get('DB_ACTIVATE');
    private static $renderJson = false;

    public function __construct()
    {
        $drivers = Config::get('DB_DRIVERS');
        $host = Config::get('DB_HOST');
        $port = Config::get('DB_PORT');
        $name = Config::get('DB_DATABASE');
        $user = Config::get('DB_USERNAME');
        $pass = Config::get('DB_PASSWORD');
        $db_activate = Config::get('DB_ACTIVATE');
        $options = [
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
        ];
        if (strtolower($db_activate) == 'true') {
            try {
                $this->_dbh = new \PDO("{$drivers}:host={$host};dbname={$name};port={$port}", $user, $pass, $options);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    public static function getInstance()
    {
        if (!self::$_db) {
            self::$_db = new self();
        }
        return self::$_db;
    }

    public function execute($sql, $bind = [])
    {
        $this->_results = null;
        $this->_lastInsertId = null;
        $this->_error = false;
        $this->_stmt = $this->_dbh->prepare($sql);
        if (!$this->_stmt->execute($bind)) {
            $this->_error = true;
        } else {
            $this->_lastInsertId = $this->_dbh->lastInsertId();
        }
        return self::render($this);
    }

    public function query($sql, $bind = [])
    {
        $this->execute($sql, $bind);
        if (!$this->_error) {
            $this->_rowCount = $this->_stmt->rowCount();
            if ($this->_fetchType === \PDO::FETCH_CLASS) {
                $this->_results = $this->_stmt->fetchAll($this->_fetchType, $this->_class);
            } else {
                $this->_results = $this->_stmt->fetchAll($this->_fetchType);
            }
        }
        return $this;
    }

    public function insert($table, $values)
    {
        $fields = [];
        $binds = [];
        foreach ($values as $key => $value) {
            $fields[] = $key;
            $binds[] = ":{$key}";
        }
        $fieldStr = implode('`, `', $fields);
        $bindStr = implode(', ', $binds);
        $sql = "INSERT INTO {$table} (`{$fieldStr}`) VALUES ({$bindStr})";
        $this->execute($sql, $values);
        return !$this->_error;
    }

    public function update($table, $values, $conditions)
    {
        $binds = [];
        $valueStr = "";
        foreach ($values as $field => $value) {
            $valueStr .= ", `{$field}` = :{$field}";
            $binds[$field] = $value;
        }
        $valueStr = ltrim($valueStr, ', ');
        $sql = "UPDATE {$table} SET {$valueStr}";

        if (!empty($conditions)) {
            $conditionStr = " WHERE ";
            foreach ($conditions as $field => $value) {
                $conditionStr .= "`{$field}` = :cond{$field} AND ";
                $binds['cond' . $field] = $value;
            }
            $conditionStr = rtrim($conditionStr, ' AND ');
            $sql .= $conditionStr;
        }
        $this->execute($sql, $binds);
        return !$this->_error;
    }

    public function results()
    {
        return $this->_results;
    }

    public function count()
    {
        return $this->_rowCount;
    }

    public function lastInsertId()
    {
        return $this->_lastInsertId;
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function getClass()
    {
        return $this->_class;
    }

    public function setFetchType($type)
    {
        $this->_fetchType = $type;
    }

    public function getFetchType()
    {
        return $this->_fetchType;
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("There are no migrations to apply");
        }
    }

    protected function createMigrationsTable()
    {
        $this->_dbh->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }

    protected function getAppliedMigrations()
    {
        $statement = $this->_dbh->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function saveMigrations(array $newMigrations)
    {
        $str = implode(',', array_map(fn ($m) => "('$m')", $newMigrations));
        $statement = $this->_dbh->prepare("INSERT INTO migrations (migration) VALUES 
            $str
        ");
        $statement->execute();
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->_dbh->prepare($sql);
    }

    private function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
    }

    /**
     * Result rendered
     * @param object|array $result defines the result coming from the database in an array format
     * @return rendered result in array or json format depending on the setting
     * @author Dory A.Azar 
     * @version 1.0
     */
    public static function render($result)
    {
        // create the JSON array
        $jsonarray = array();
        while ($row = $result->fetch()) {
            $jsonarray[] = $row;
        }
        if (self::$renderJson) {
            return json_encode($jsonarray);
        } else {
            return json_decode(json_encode($jsonarray), true);
        }
    }

    /**
     * Forces the result to an array
     * @param array $result defines the result coming in either a json or array format
     * @return array $result the result rendered as an array
     * @author Dory A.Azar 
     * @version 1.0
     */
    public static function toArray($result)
    {
        $render = $result;
        if (!is_array($result) && self::is_Json($result)) {
            $render = json_decode($result, true);
        }
        return $render;
    }


    /**
     * Checks if a string i a JSON
     * @param string $string defines the string to be checked
     * @return boolean if the string is a JSON
     * @author Dory A.Azar 
     * @version 1.0
     */
    public static function is_Json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
