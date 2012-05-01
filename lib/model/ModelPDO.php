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
		$modelName = self::getModelName();
		return "{$modelName}s";
	}

	protected static function getFieldName($field) {
		$modelName = self::getModelName();
		return  "{$modelName}_{$field}";
	}

	protected static function getBindName($field) {
		return ":{$field}";
	}

	protected static function getEqualBind($field) {
		$fieldName = self::getFieldName($field);
		$bindName = self::getBindName($field);
		return "{$fieldName} = {$bindName}";
	}

	protected static function isFieldName($name) {
		$modelName = self::getModelName();
		return substr($name, 0, strlen($modelName) + 1) === "{$modelName}_";
	}

	protected static function getPropertyName($name) {
		return substr($name, strlen(self::getModelName()) + 1);
	}

	public static function encodeAllJSON(array $models, $depth = 0) {
		$data = array();
		foreach ($models as $model) {
			$data[] = $model->getData($depth);
		}
		return json_encode($data);
	}

	public static function get($id) {
		return self::getBy(array('id' => $id));
	}

	public static function getAll() {
		return self::getAllBy();
	}

	protected static function getBy(array $where = NULL) {
		$sth = self::getExecute($where);
		$data = $sth->fetch();
		$sth->closeCursor();
		return $data;
	}

	protected static function getAllBy(array $where = NULL) {
		$sth = self::getExecute($where);
		$data = $sth->fetchAll();
		$sth->closeCursor();
		return $data;
	}

	private static function getExecute(array $where = NULL) {
		$table = self::getTableName();
		$q = "SELECT * FROM {$table}";
		$sth = self::prepareQuery($q, $where ? array(self::getModelName() => $where) : NULL);
		$sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, self::getModelName());
		$sth->execute();
		return $sth;
	}

	public static function countAll() {
		return self::countBy();
	}

	protected static function countBy(array $where = NULL) {
		$table = self::getTableName();
		$q = "SELECT COUNT(*) FROM {$table}";
		$sth = self::prepareQuery($q, $where);
		$sth->execute();
		return $sth->fetchColumn();
	}

	private static function prepareQuery($q, array $where = NULL) {
		if ($where) {
			$modelName = self::getModelName();
			foreach ($where as $model => $value) {
				if ($modelName != $model) {
					$q .= ", {$model}s";
				}
			}
			$q .= ' WHERE ';
			$wheres = array();
			foreach ($where as $model => $field) {
				if (is_array($field)) {
					foreach ($field as $f => $value) {
						$bindName = self::getBindName($f);
						$wheres[] = "{$model}_{$f} = {$bindName}";
					}
				} else {
					$wheres[] = "{$model}_{$field} = {$field}";
				}
			}
			$q .= implode(' AND ', $wheres);
		}
		$sth = self::getPDO()->prepare($q);
		if ($where) {
			foreach ($where as $table => $field) {
				if (is_array($field)) {
					foreach ($field as $f => $value) {
						$sth->bindParam(self::getBindName($f), $value);  
					}
				}
			}
		}
		return $sth;
	}


	private $fields = array();

	public function __construct(array $schema) {
		$schema = array('id' => PDO::PARAM_INT) + $schema;
		foreach ($schema as $name => $type) {
			$this->fields[$name] = array('value' => NULL, 'type' => $type);
		}
	}

	public function save() {
		$table = self::getTableName();
		if ($this->fields['id']['value'] != NULL) {
			foreach ($this->fields as $field => $f) {
				if ($field != 'id' && $f['value'] != NULL) {
					$sets[] = self::getEqualBind($field);
				}
			}
			$set = implode(', ', $sets);
			$where = self::getEqualBind('id');
			$q = "UPDATE {$table} SET {$set} WHERE {$where}";
		} else {
			foreach ($this->fields as $field => $f) {
				if ($field != 'id' && $f['value'] != NULL) {
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
			if ($f['value'] != NULL) {
				$sth->bindValue(self::getBindName($field), $f['value'], $f['type']); 
			}
		}
		$result = $sth->execute();
		if ($result && $this->fields['id']['value'] == NULL) {
			$this->fields['id']['value'] = self::getPDO()->lastInsertId();
		}
		$sth->closeCursor();
		return $result;
	}

	public function delete() {
		$id = $this->fields['id']['value'];
		if ($id == NULL) {
			return;
		}
		$table = self::getTableName();
		$q = "DELETE FROM {$table}";
		$sth = self::prepareQuery($q, array(self::getModelName() => array('id' => $id)));
		$result = $sth->execute();
		if ($result) {
			foreach ($this->fields as $field => $f) {
				unset($f['value']);
			}
		}
		$sth->closeCursor();
		return $result;
	}
 
	public function __set($name, $value) {
		if (self::isFieldName($name)) {
			$name = self::getPropertyName($name);
		}
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

	public function encodeJSON($depth = 0) {
		return json_encode($this->getData($depth));
	}

	protected function getData($depth = 0) {
		$data = array();
		foreach ($this->fields as $field => $f) {
			if ($f['value'] != NULL) {
				$data[$field] = $f['value'];
			}
		}
		if ($depth-- > 0) {
			$childJSON = $this->getChildData($depth);
			if ($childJSON) {
				$data += $childJSON;
			}
		}
		return $data;
	}

	protected function getChildData($depth) {
		return null;
	}

}

?>
