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

	protected static function getEqualBind($field) {
		$fieldName = self::getFieldName($field);
		$bindName = self::getBindName($field);
		return "{$fieldName} = {$bindName}";
	}

	protected static function getPropertyName($prop) {
		return substr($prop, strlen(self::getModelName()) + 1);
	}

	public static function get($id) {
		return self::getBy(array('id' => $id));
	}

	public static function getAll() {
		return self::getAllBy();
	}

	protected static function getBy(array $where = null) {
		$sth = self::getExecute($where);
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			$modelName = self::getModelName();
			return new $modelName($data);
		}
		return null;
	}

	protected static function getAllBy(array $where = null) {
		$sth = self::getExecute($where);
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

	private static function getExecute(array $where = null) {
		$table = self::getTableName();
		$q = "SELECT * FROM {$table}";
		if ($where) {
			$q .= ' WHERE';
			foreach ($where as $field => $value) {
				$q .= ' ' . self::getEqualBind($field);
			}
		}
		$sth = self::getPDO()->prepare($q);
		if ($where) {
			foreach ($where as $field => $value) {
				$sth->bindParam(self::getBindName($field), $value);
			}
		}
		$sth->execute();
		return $sth;
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
		$table = self::getTableName();
		if ($this->fields['id']['value'] != null) {
			foreach ($this->fields as $field => $f) {
				if ($field != 'id' && $f['value'] != null) {
					$sets[] = self::getEqualBind($field);
				}
			}
			$set = implode(', ', $sets);
			$where = self::getEqualBind('id');
			$q = "UPDATE {$table} SET {$set} WHERE {$where}";
		} else {
			foreach ($this->fields as $field => $f) {
				if ($field != 'id' && $f['value'] != null) {
					$cols[] = self::getFieldName($field);
					$binds[] = self::getBindName($field);
				}
			}
			$columns = implode(', ', $cols);
			$bindings = implode(', ', $binds);
			$q = "INSERT INTO {$table} ({$columns}) VALUES ({$bindings})";
		}
		$sth = ModelPDO::getPDO()->prepare($q);
		foreach ($this->fields as $field => $f) {
			$value = $f['value'];
			if ($f['value'] != null) {
				$sth->bindValue(self::getBindName($field), $f['value'], $f['type']); 
			}
		}
		return $sth->execute();
	}

	public function delete() {
		$id = $this->fields['id']['value'];
		if ($id == null) {
			return;
		}
		$table = self::getTableName();
		$where = self::getEqualBind('id');
		$q = "DELETE FROM {$table} WHERE {$where}";
		$sth = ModelPDO::getPDO()->prepare($q);
		$sth->bindValue(self::bindName('id'), $id, $this->fields['id']['type']);
		$result = $sth->execute();
		if ($result) {
			foreach ($this->fields as $field => $f) {
				unset($f['value']);
			}
		}
		return $result;
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

	public function __isset($name) {
		if (array_key_exists($name, $this->fields)) {
			return isset($this->fields[$name]['value']);
		}
	}

	public function __unset($name) {
		if (array_key_exists($name, $this->fields)) {
			unset($this->fields[$name]['value']);
		}
	}

	protected function getJSONData() {
		$fs = array();
		foreach ($this->fields as $field => $f) {
			if ($f['value'] != null) {
				$fs[$field] = $f['value'];
			}
		}
		return $fs;
	}

	public function encodeJSON() {
		return json_encode($this->getJSONData());
	}

}

?>
