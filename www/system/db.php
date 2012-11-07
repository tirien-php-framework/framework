<?php
class DB {
    private static $link;
    private static $dbType = 'sqlite';
    private static $globalDebug = false;
    private static $printOutput = false;
    private static $isInitiated = false;
    private static $databaseName = '';
    const prefix = 'w__';
    private static function init() {
		global $_config;
		global $_debug;
		
		$db_config = $_config['database'];
        self::$isInitiated = true;
        self::$globalDebug = $_debug;
		
		switch(self::$dbType){
		
			case 'sqlite':
				$connectionString = self::$dbType.":application/databases/default.db";
				break;
			
			case 'mysql':
				$db_config = $_config['database'];
				$connectionString = 
					self::$dbType.':'
					.'host='.$db_config['host'].';'
					.'dbname='.$db_config['name'];
				break;
			
		}
		
        
        
        try {
            self::$link = self::$dbType=='mysql' ? new PDO($connectionString, $db_config['user'], $db_config['pass']) : new PDO($connectionString);
            if (self::$globalDebug) {
                self::$link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

        } catch (PDOException $e) {

            if (self::$globalDebug) {
                print "Error: " . $e -> getMessage();
            } else {
                print "Database connection error!";
            }
            die();
        }
        
		if( self::$dbType == 'mysql' ){
			self::$link -> exec("SET NAMES utf8");
			self::$link -> exec("SET CHARACTER SET utf8");
			self::$link -> exec("SET COLLATION_CONNECTION='utf8_general_ci'");
		}
		
    }

    private static function addPrefix($array, $prefix) {
        $newArray = array();
        foreach ($array as $key => $value) {
            $newArray[$key] = $prefix . $value;
        }
        return $newArray;
    }

    public static function query($prepared, $values = array()) {
        if (!self::$isInitiated) self::init();
        if (self::$globalDebug && self::$printOutput) {
            $stmt = self::prepare($prepared, $values);
            print_r($stmt);
            print_r($values);
            print_r($stmt -> errorInfo());
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        } else {
            $prepared_statement = self::prepare($prepared, $values);
            $prepared_statement -> execute();
            return $prepared_statement -> fetchAll(PDO::FETCH_ASSOC);
        }

    }

    public static function insert($table, $values) {
        if (!self::$isInitiated) self::init();
        $columns = array();
        $keys = array_keys($values);
        foreach ($keys as $value) {
            $columns[] = "`$value`";
        }

        $statement = sprintf('INSERT INTO %s`%s` (%s) VALUES (%s);', self::$databaseName, $table, implode(',', $columns), implode(',', self::addPrefix(array_keys($values), ':')));
        if (self::$globalDebug && self::$printOutput) {
            $stmt = self::prepare($statement, $values);
            print_r($stmt);
            print_r($values);
            $stmt -> execute();
            print_r($stmt -> errorInfo());
        } else {
            $prepared_statement = self::prepare($statement, $values);
            if ($prepared_statement -> execute())
                return self::$link -> lastInsertId();
            else
                return FALSE;
            ;
        }

    }

    public static function update($table, $set, $where = false) {
        if (!self::$isInitiated) self::init();
        $params = array();
        $set_params = array();
        $where_params = array();
        $set_part;
        $where_part;

        if (is_array($set)) {
            foreach ($set as $key => $value) {
                $params[$key] = $value;
                $set_params[] = "`$key`=:$key";
            }
        } else {
            $p = split('=', $set);
            $params[$p[0]] = $p[1];
            $set_params[] = "`{$p[0]}`=:{$p[0]}";
        }
        $set_part = implode(',', $set_params);

        if (false === $where) {
            $where_part = '';
        } else {

            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    $params[DB::prefix . $key] = $value;
                    $where_params[] = "`$key`=:" . DB::prefix . $key;
                }
                $where_part = 'WHERE ' . implode(' AND ', $where_params);
            } else {
                $where_part = 'WHERE ' . $where;
            }

        }
        $statement = sprintf('UPDATE %s`%s` SET %s %s;', self::$databaseName, $table, $set_part, $where_part);

        if (self::$globalDebug && self::$printOutput) {
            $stmt = self::prepare($statement, $params);
            print_r($stmt);
            print_r($params);
            $stmt -> execute();
            print_r($stmt -> errorInfo());
        } else {
            return self::prepare($statement, $params) -> execute();
        }

    }

    public static function delete($table, $where) {
        if (!self::$isInitiated) self::init();
        $where_part;
        if (is_array($where)) {
            $condition = array();
            foreach ($where as $key => $value) {
                $condition[] = "`$key`=:$key";
            }
            $where_part = ' ' . implode(' AND ', $condition) . ' ';
        } else {
            $where_part = "$where";
        }

        $statement = sprintf('DELETE FROM %s`%s` WHERE %s;', self::$databaseName, $table, $where_part);

        if (self::$globalDebug && self::$printOutput) {
            $stmt = self::prepare($statement, $where);
            print_r($stmt);
            print_r($where);
            $stmt -> execute();
            print_r($stmt -> errorInfo());
        } else {
            return self::prepare($statement, $where) -> execute();
        }

    }

    private static function prepare($statement, $values = array()) {
        $prepared_stmt = self::$link->prepare($statement);
        if (is_array($values)) {
            foreach ($values as $key => $value) {

                if (is_int($value))
                    $param = PDO::PARAM_INT;
                elseif (is_bool($value))
                    $param = PDO::PARAM_BOOL;
                elseif (is_null($value))
                    $param = PDO::PARAM_NULL;
                elseif (is_string($value))
                    $param = PDO::PARAM_STR;

                $prepared_stmt -> bindValue(":$key", $value, $param);
            }
        } else {
            $prepared_stmt -> bindValue(1, $values);
        }
        return $prepared_stmt;
    }
    function getObject(){
        if (!self::$isInitiated) self::init();
        return self::link;
    }
    public static function printOutput($bool){
        self::$printOutput = $bool;
    }
}

//
?>