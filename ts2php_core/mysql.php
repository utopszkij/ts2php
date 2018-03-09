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
	function __construct() {
		$host = Config('MYSQL_HOST');
		$user = Config('MYSQL_USER'); 
		$psw = Config('MYSQL_PSW'); 
		$dbName = Config('MYSQL_DB');
		$this->dbPre = Config('MYSQL_DBPRE');
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
	* @return $this
	*/
	public function setQuery($sql) {
		$this->_sql = str_replace('#__',$this->dbPre,$sql);
		return $this;	
	}

	/**
	* define params
	* @param string param name without '$'
	* @param string
	* @return $this
	*/
	public function setParam($paramName, $value) {
		$this->_params[$paramName] = $value;	
		return $this;
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
		if (Config('DEBUGMODE')) {
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

	/**
	* load objectList by select object
	* @param object $select
	* @param integer limitStart
	* @param integer $limit
	* @return array of records
	*/
	public function loadSelect($select, $limitStart = 0, $limit = -1) {
		$s = "\n".'LIMIT '.$limitStart;
		if ($limit > 0) $s .= ','.$limit;
		if (($limitStart == 0) && ($limit == -1)) $s = '';
		$this->setQuery($select->toString().$s);
		return $this->loadObjectList();
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

/**
* object oriented sql select 
*/
class Xselect {
	private $fieldList = ''; // sql select string
	private $from = array(); // [0]:alias, [1]: tableName or select objrect
	private $joins = array(); // array [0]: join type
							  //       [1]: alias
							  //       [2]: alias.tableName or select object		 
							  //       [3]: ON string	
  	private $groups = '';     // sql group by string
	private $unions = array();  // array of select
	private $where = '';     // sql where string
	private $having = '';    // sql having string
	private $order = '';     // sql order by string
	
	/**
	* @param string
	* @return Xselect
	*/
	public function setFieldList($s) {
		$this->fieldList = $s;
		return $this;
	}

	/**
	* @param string alias
	* @param string tableName
	* @return Xselect
	*/
	public function setFrom($alias, $table) {
		$this->from = array($alias, $table);
		return $this;
	}

	/**
	* @param string alias
	* @param Select object 
	* @return Xselect
	*/
	public function setSubselect($alias, $select) {
		$this->from = array($alias, $select);
		return $this;
	}

	/**
	* @param string INNER|LEFT OUTER|RIGHT OUTER
	* @param string alias
	* @param string tableName
	* @param string ON
	* @return Xselect
	*/
	public function addJoin($joinType, $alias, $table, $on) {
		$this->joins[] = array($joinType, $alias, $table, $on);
		return $this;
	}

	/**
	* @param string INNER|LEFT OUTER|RIGHT OUTER
	* @param string alias
	* @param Select object
	* @param string ON
	* @return Xselect
	*/
	public function addSubselectJoin($joinType, $alias, $select, $on) {
		$this->joins[] = array($joinType, $alias, $select,$on);
		return $this;
	}

	/**
	* @param string
	* @return Xselect
	*/
	public function setGroups($groups) {
		$this->groups = $groups;
		return $this;
	}

	/**
	* @param Select object
	* @return Xselect
	*/
	public function addUnion($select) {
		$this->unions[] = $select;
		return $this;
	}

	/**
	* @param string
	* @return Xselect
	*/
	public function setWhere($where) {
		$this->where = $where;
		return $this;
	}

	/**
	* @param string
	* @return Xselect
	*/
	public function setHaving($having) {
		$this->where = $having;
		return $this;
	}

	/**
	* @param string
	* @return Xselect
	*/
	public function setOrder($order) {
		$this->order = $order;
		return $this;
	}

	/**
	* @return string sql
	*/
	public function toString() {
		$result = 'SELECT '.$this->fieldList."\n";
		if (is_object($this->from[1]))
			$result .= 'FROM ('.$this->from[1]->toString().') AS '.$this->from[0]."\n";
		else
			$result .= 'FROM '.$this->from[1].' AS '.$this->from[0]."\n";
		foreach ($this->joins as $join) {
			$result .= $join[0].' JOIN ';
			if (is_object($join[2]))
				$result .= ' ('.$join[2]->toString().') AS '.$join[1].' ON '.$join[3]."\n";
			else
				$result .= ' '.$join[2].' AS '.$join[1].' ON '.$join[3]."\n";
		}
		foreach ($this->unions as $union) {
			$result .= 'UNION ALL'."\n".$union->toString()."\n";	
		}
		if ($this->where != '')
			$result .= 'WHERE '.$this->where."\n";
		if ($this->groups != '')
			$result .= 'GROUP BY '.$this->groups."\n";
		if ($this->having != '')
			$result .= 'HAVING '.$this->having."\n";
		if ($this->order != '')
			$result .= 'ORDER BY '.$this->order."\n";
		return $result;
	}
}

?>
