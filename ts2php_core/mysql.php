<?php 
/** mysql interface (joomla style)
*   use mysqli php extension
*
* requed Defines: MYSQL_HOST, MYSQL_USER, MYSQL_PSW, MYSQL_DB, DEBUGMODE
*/
class Xdb {
	private $_sql = '';
	private $_errorNum = 0;
	private $_errorMsg = '';
	private $_mysqli = false;
	private $_params = array();
	
	public $dbPre = 'np_'; // tableName prefix (#__ replace)
	
	/**
	* constructor
	* @param string host
	* @param string user
	* @param string psw
	* @param string dbname
	* @return object;
	*/
	function __construct($host = MYSQL_HOST, $user = MYSQL_USER, $psw = MYSQL_PSW, $dbName = MYSQL_DB) {
		$this->dbPre = MYSQL_DBPRE;
		$this->_mysqli = new mysqli($host, $user, $psw, $dbName);
		$this->_errorNum = $this->_mysqli->connect_errno;
		$this->_errorMsg = $this->_mysqli->connect_error;
		if ($this->_errorNum == 0) {
			$this->setQuery('set names utf8');
			$this->query();
		}
	}
	
	/**
	* set query string
	* @param string sql string '$paramName' allowed
	* @return void
	*/
	public function setQuery($sql) {
		$this->_sql = str_replace('#__',$this->dbPre,$sql);
		return;	
	}

	/**
	* define params
	* @param string param name without '$'
	* @param string
	*/
	public function setParam($paramName, $value) {
		$this->_params[$paramName] = $value;	
	}

	/**
	* prepare params to sql
	$ @return string
	*/
	private function prepare() {
		$result = $this->_sql;
		foreach ($this->_params as $fn => $fv) {
			$result = $str_replace('$'.$fn, $fv, $result);
		}
		return $result;
	}
	
	/**
	* get query string
	* @return string
	*/
	public function getQuery() {
		return $this->prepare();
	}

	private function debugFun($result = 'none') {
		if (DEBUGMODE) {
			if (file_exists('mysqldebug.txt'))
				$fp = fopen('mysqldebug.txt','a+');
			else
				$fp = fopen('mysqldebug.txt','w+');
			fwrite($fp,"\n".date('Y-m-d H:i:s')."\n");
			fwrite($fp,$this->getQuery()."\n".'errorMsg:'.$this->getErrorMsg()."\n");
			fwrite($fp,'result:'.JSON_encode($result)."\n");
			fclose($fp);
		}
	}
	
	/**
	* load record set (bafter use setQuery)
	* @return array of objects
	*/
	public function loadObjectList() {
		$result = array();
		$res = $this->_mysqli->query($this->prepare());
		$this->_errorNum = $this->_mysqli->errno;
		$this->_errorMsg = $this->_mysqli->error;
		if ($res) {
			while ($obj = $res->fetch_object()) {
				$result[] = $obj;
			}	
			$res->close();
		}
		$this->debugFun($result);
		return $result;
	}
	
	/**
	* get record (after use setQuery)
	* @return object or false
	*/
	public function loadObject() {
		$res = $this->loadObjectList();
		if (count($res) > 0)
			$result = $res[0];
		else
			$result = false;
		$this->debugFun($result);
		return $result;
	}
	
	/**
	* execute a query (after use setQuery)
	* @return true or false
	*/
	public function query() {
		$this->_mysqli->query($this->prepare());
		$this->_errorNum = $this->_mysqli->errno;
		$this->_errorMsg = $this->_mysqli->error;
		$this->debugFun(($this->_errorNum == 0));
		return ($this->_errorNum == 0);
	}
	
	/**
	* get error number
	* @return integer
	*/
	public function getErrorNum() {
		return $this->_errorNum;
	}
	
	/**
	* get roor message
	* @return string
	*/
	public function getErrorMsg() {
		return $this->_errorMsg;
	}
	
	/**
	* adjust string value for sql value
	* @param string
	* @return string
	*/
	public function quote($str) {
		if (is_numeric($str)) {
			return '"'.$str.'"';
		} else {
			$str = str_replace('\"','"',$str);
			$str = str_replace("\\",'/',$str);
			$str = str_replace("\n",'\n',$str);
			$str = str_replace("\r",'',$str);
			$str = str_replace("\r",'',$str);
			$str = str_replace('"','\"',$str);
			return '"'.$str.'"';
		}	
	}
	
	/**
	* disconnect
	* @return void
	*/
	public function disconnect() {
		$this->_mysqli->close();
	}

	/**
	* start tranzakció
	* @return bool 
	*/
	public function startTransaction() {
		$this->setQuery('START TRANSACTION');
		return $this->query();	
	}

	/**
	* commit tranzakció
	* @return bool 
	*/
	public function commit() {
		$this->setQuery('COMMIT');
		return $this->query();	
	}

	/**
	* rollback tranzakció
	* @return bool 
	*/
	public function rollBack() {
		$this->setQuery('ROLLBACK');
		return $this->query();	
	}

	/**
	* lock tables
	* @param array tábla nevek
	* @return bool 
	*/
	public function lockTables($tableNames) {
		$s = 'LOCK TABLES ';
		foreach ($tableNames as $w) {
			$s .= $w.' WRITE,';
		}
		$s .= '#__users WRITE';
		$this->setQuery($s);
		return $this->query();	
	}

	/**
	* unlock tables
	* @return bool 
	*/
	public function unlockTables() {
		$this->setQuery('UNLOCK TABLES');
		return $this->query();	
	}
	/**
	* adat manipuláló sql sorozat végrehajtása "....;..." nem lehet benne
	* @param string
	* @return bool - hiba esetén errorMsg beállítva
	*/
	public function sql($str) {
		$result = true;
		$i = 0;
		$items = explode(';',$str);
		if (count($items) < 2) {
			$this->setQuery($str);
			$result = $this->query();			
		} else {
			$i = 0;
			while (($i < count($items)) & ($result == true)) {
				if (trim($items[$i]) != '') {
					$this->setQuery($items[$i]);
					$result = $this->query();			
				}
				$i++;
			}
		}
		return $result;
	}
}

class XTable {
	private $db = false;
	private $tableName = '';
	private $keyName = '';

	/**
	* @param object DBO
	* @param string tableName
	* @param string keyFieldName	
	* @return object Table
	*/
	function __construct($db, $tableName, $keyName = 'id') {
		$this->db = $db;
		$this->tableName = str_replace('#__',$this->db->dbPre,$tableName);
		$this->keyName = $keyName;
	}

	/**
	* insert a new record  -  set the KEYFIELD in $record)
	* @param object record
	* @return boolean
	*/
	public function insert(&$record) {
		$fieldNames = array();
		$values = array();
		foreach ($record as $fn => $fv) {
			$fieldNames[] = '`'.strtolower($fn).'`';
			$values[] = $this->db->quote($fv);
		}
		$this->db->setQuery('LOCK TABLES '.$this->tableName.' WRITE');
		$this->db->query();
		$sql = 'INSERT INTO `'.$this->tableName.'` 
		('.implode(',',$fieldNames).')
		VALUES
		('.implode(',',$values).')';
		$this->db->setQuery($sql);
		$result = $this->db->query();
		if ($result) {
			$keyName = $this->keyName;
			$this->db->setQuery('select max('.$keyName.') ID from '.$this->tableName);
			$res = $this->db->loadObject();
			if ($res) $record->$keyName = $res->ID;
		}

		$this->db->setQuery('UNLOCK TABLES');
		$this->db->query();
		return $result;
	}

	/**
	* update a  record
	* @param object record
	* @return boolean
	*/
	public function update(& $record) {
		$keyName = $this->keyName;
		$s = array();
		foreach ($record as $fn => $fv) {
			$s[] = '`'.strtolower($fn).'` = '.$this->db->quote($fv);
		}
		$sql = 'UPDATE `'.$this->tableName.'` SET 
		'.implode(',',$s).'
		WHERE '.$this->keyName.' = '.$this->db->quote($record->$keyName);
		$this->db->setQuery($sql);
		return $this->db->query();
	}

	/**
	* insert or update a record - if insert then set the KEYFIELD in $record)
	* @param object record
	* @return boolean
	*/
	public function save(&$record) {
		$keyName = $this->keyName;
		if (($record->$keyName == 0) | ($record->$keyName == ''))
			$result = $this->insert($record);
		else
			$result = $this->update($record);
		return $result;
	}
	
	/**
	* delete one record
	* @param integer id
	* @return boolean
	*/
	public function remove($id) {
		$keyName = $this->keyName;
		$sql = 'DELETE FROM `'.$this->tableName.'`  
		WHERE '.$this->keyName.' = '.$this->db->quote($id);
		$this->db->setQuery($sql);
		return $this->db->query();
	}
	
	/**
	* get last error number
	* @return integer
	*/
	public function getErrorNum() {
		return $this->db->getErrorNum();
	}
	
	/**
	* get last error message
	* @return integer
	*/
	public function getErrorMsg() {
		return $this->db->getErrorMsg();
	}
}


?>
