<?php

abstract class ModelPDO {

	private static $pdo;

	protected static function getPDO() {
		if (!isset(self::$pdo)) {
			self::$pdo = new PDO(
				'mysql:dbname=pkb08164;host=devweb2011.cis.strath.ac.uk',
				'pkb08164',
				'vagangst'
			);
			
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		}
		return self::$pdo;
	}

	protected static function getModelName() {
		return strtolower(get_called_class());
	}

	protected static function getTableName() {
		return self::getModelName() . 's';
	}

	protected static function getFieldName($field) {
		return self::getModelName() . '_' . $field;
	}

	protected static function getBindName($field) {
		return ":{$field}";
	}

	protected static function getPropertyName($prop) {
		return substr($prop, strlen(self::getModelName()) + 1);
	}

	public static function get($id) {
		return self::getBy('id', $id);
	}

	protected static function getBy($field, $value) {
		$tableName = self::getTableName();
		$fieldName = self::getFieldName($field);
		$bindName = self::getBindName($field);
		$q = "SELECT * FROM {$tableName} ";
		$q .= "WHERE {$fieldName} = {$bindName}";
		$sth = self::getPDO()->prepare($q);
		$sth->bindParam($bindName, $value);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			$modelName = self::getModelName();
			return new $modelName($data);
		}
		return null;
	}

	public static function getAll() {
		$tableName = self::getTableName();
		$q = "SELECT * FROM {$tableName} ";
		$sth = self::getPDO()->prepare($q);
		$sth->execute();
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		if ($data) {
			$models = array();
			foreach ($data as $d) {
				$modelName = self::getModelName();
				$models[] = new $modelName($d);
			}
			return $models;
		}
		return null;
	}

	protected static function getAllBy($field, $value) {
		$tableName = self::getTableName();
		$fieldName = self::getFieldName($field);
		$bindName = self::getBindName($field);
		$q = "SELECT * FROM {$tableName} ";
		$q .= "WHERE {$fieldName} = {$bindName}";
		$sth = self::getPDO()->prepare($q);
		$sth->bindValue($bindName, $value);
		$sth->execute();
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		if ($data) {
			$models = array();
			foreach ($data as $d) {
				$modelName = self::getModelName();
				$models[] = new $modelName($d);
			}
			return $models;
		}
		return null;
	}


	private $fields = array();

	public function __construct($schema, $data = false) {
		$this->fields['id'] = array('value' => null, 'type' => PDO::PARAM_INT);
		foreach ($schema as $name => $type) {
			$this->fields[$name] = array('value' => null, 'type' => $type);
		}
		if ($data) {
			foreach ($data as $column => $value) {
				$prop = self::getPropertyName($column);
				$this->fields[$prop]['value'] = $value;
			}
		}
	}

	public function save() {
		$tableName = self::getTableName();
		if ($this->fields['id']['value'] != null) {
			foreach ($this->fields as $field => $f) {
				if ($field != 'id' && $f['value'] != null) {
					$fieldName = self::getFieldName($field); 
					$bindName = self::getBindName($field);
					$fields[] = "{$fieldName} = {$bindName}";
				}
			}
			$fieldName = self::getFieldName('id');
			$bindName = self::getBindName('id');
			$set = implode(', ', $fields);
			$q = "UPDATE {$tableName} ";
			$q .= "SET {$set} ";
			$q .= "WHERE {$fieldName} = {$bindName}";
		} else {
			foreach ($this->fields as $field => $f) {
				if ($field != 'id' && $f['value'] != null) {
					$cols[] = self::getFieldName($field);
					$binds[] = self::getBindName($field);
				}
			}
			$columns = implode(', ', $cols);
			$bindings = implode(', ', $binds);
			$q = "INSERT INTO {$tableName} ";
			$q .= "({$columns}) VALUES ({$bindings})";
		}
		$sth = ModelPDO::getPDO()->prepare($q);
		foreach ($this->fields as $field => $f) {
			$value = $f['value'];
			if ($f['value'] != null) {
				//echo self::getBindName($field) . " => {$f['value']}\n";
				$sth->bindValue(self::getBindName($field), $f['value'], $f['type']); 
			}
		}
		//echo "{$sth->queryString}\n";
		return $sth->execute();
	}
 
	public function __set($name, $value) {
		if (array_key_exists($name, $this->fields)) {
			$this->fields[$name]['value'] = $value;
		}
	}

	public function __get($name) {
		if (array_key_exists($name, $this->fields)) {
			return $this->fields[$name]['value'];
		}
	}

}

?>
