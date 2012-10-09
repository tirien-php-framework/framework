<?php
class DB {
	private $link;
	public $debug = false;
	private $global_debug=false;
	const prefix = 'w__';
	function __construct() {
		global $_config;
		global $_debug;
		
		$this->global_debug = $_debug;
		
		$db_host = $_config['database']['host'];
		$db_user = $_config['database']['user'];
		$db_pass = $_config['database']['pass'];
		$db_name = $_config['database']['name'];
		try {
			$this->link = new PDO('mysql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pass);
		} catch (PDOException $e) {

			if ($this->global_debug && $this->debug) {
				print "Error: " . $e->getMessage();
			} else {
				print "Database connection error!";
			}
			die();
		}
		$this->link->exec("SET NAMES utf8");
		$this->link->exec("SET CHARACTER SET utf8");
		$this->link->exec("SET COLLATION_CONNECTION='utf8_general_ci'");
	}

	function addPrefix($array, $prefix) {
		$newArray = array();
		foreach ($array as $key => $value) {
			$newArray[$key] = $prefix . $value;
		}
		return $newArray;
	}

	function query($prepared, $values = array()) {

		
		if ($this->global_debug && $this->debug){
			$stmt = $this->prepare($prepared, $values);
			vd($stmt);
			vd($values);
			vd($stmt->errorInfo());
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
						
		}
		else{
			$prepared_statement = $this->prepare($prepared, $values);
			$prepared_statement->execute();			
			return $prepared_statement->fetchAll(PDO::FETCH_ASSOC);
		}		

	}

	function insert($table, $values) {		
		global $_config;		
		$columns = array();
		$keys = array_keys($values);
		foreach($keys as $value)
		{
			$columns[] = "`$value`";
		}
		
		$statement = sprintf('INSERT INTO `%s`.`%s` (%s) VALUES (%s);',
			$_config['database']['name'],
			$table,
			implode(',', $columns),
			implode(',', $this->addPrefix(array_keys($values),':'))
		);
		

		if ($this->global_debug && $this->debug){
			$stmt = $this->prepare($statement, $values);
			vd($stmt);
			vd($values);
			$stmt->execute();
			vd($stmt->errorInfo());
		}
		else{
			$prepared_statement = $this->prepare($statement, $values);	
			if ($prepared_statement->execute())
				return $this->link->lastInsertId();
			else
				return FALSE;
			;
		}
		
	}
	
	function update($table, $set, $where = false) {
		global $_config;

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
			$p = explode('=', $set);
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
				$where_part = 'WHERE '. $where;
			}
			
			
		}
		$statement = sprintf('UPDATE `%s`.`%s` SET %s %s;',
			$_config['database']['name'],
			$table,
			$set_part,
			$where_part
		);
		
		if ($this->global_debug && $this->debug){		
			$stmt = $this->prepare($statement, $params);
			vd($stmt);
			vd($params);
			$stmt->execute();
			vd($stmt->errorInfo());		
		}
		else{
			return $this->prepare($statement, $params)->execute();
		}

	}

	function delete($table, $where) {
		global $_config;

		$where_part;
		if (is_array($where)) {
			$condition = array();
			foreach ($where as $key => $value) {				
				$condition[] = "`$key`=:$key";
			}
			$where_part = ' ' . implode(' AND ', $condition) . ' ';
		} else {
			$p = explode('=', $where);
			$where_part  = "$where";
		}

		$statement = sprintf('DELETE FROM `%s`.`%s` WHERE %s;', $_config['database']['name'], $table, $where_part);
	
		if ($this->global_debug && $this->debug){
			$stmt = $this->prepare($statement, $where);
			vd($stmt);
			vd($where);
			$stmt->execute();
			vd($stmt->errorInfo());				
		}
		else{
			return $this->prepare($statement,$where)->execute();
		}

	}

	function prepare($statement, $values = array()) {
		$prepared_stmt = $this->link->prepare($statement);
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				
				if(is_int($value))
                    $param = PDO::PARAM_INT;
                elseif(is_bool($value))
                    $param = PDO::PARAM_BOOL;
                elseif(is_null($value))
                    $param = PDO::PARAM_NULL;
                elseif(is_string($value))
                    $param = PDO::PARAM_STR;
				
				if(is_int($key)){
					$prepared_stmt->bindValue($key+1, $value,$param);
				}
				else{
					$prepared_stmt->bindValue(":$key", $value,$param);
				}
					
			}
		} else {
			$prepared_stmt->bindValue(1, $values);
		}
		return $prepared_stmt;
	}

	function __destruct() {
		unset($this->link);
	}

}

//
?>