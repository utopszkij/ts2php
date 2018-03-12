<?php
// generated the ts2php 2018.03.11 20:09:18

/** 
* példa ts file 
* 2018
*/

include_once "../ts2php_core/tsphpx.php";

$mytomb = XARRAY();
$szam = 120;

switch ($szam) {
	case 0:
		echo('nulla');
		break;	
	case 1:
		echo('egy');
		break;	
	default:
		echo('más');
		break;	
}
	
function Config($name) {
	$result;
	if ($name == 'MYSQL_HOST') $result = '127.0.0.1';
	else if ($name == 'MYSQL_USER') $result = 'root';
	else if ($name == 'MYSQL_PSW') $result = '13Marika';
	else if ($name == 'MYSQL_DB') $result = 'netpolgar';
	else if ($name == 'MYSQL_DBPRE') $result = 'np_';
	else if ($name == 'DEBUGMODE') $result = 'false';
	return $result;
}

class Szemely {
	protected $_firstName;
	protected $_lastName;
	function __construct($firstName, $lastName) {
		$this->_firstName = $firstName;
		$this->_lastName = $lastName;
		$_GET['x'] = 'y';
	}
	public function getFullName() {
		return $this->_firstName . ' ' . $this->_lastName;
	}
}

/*
interface Pofa extends Szemely {
   getFullName(): string
}
*/


$szemely = new Szemely('Teszt','Elek'); // sorvégi komment
echo($szemely->getFullName());

class Ember extends Szemely  {
	private $_age;
	public $tomb;
	protected $x;
	function __construct($firstName, $lastName, $age) {
		parent::__construct($firstName, $lastName);
		$this->_age = $age;
		$this->tomb = array();
		$this->tomb[0] = 'aaa';
		$this->tomb[1] = 'bbb';
		$this->tomb['c'] = 'cc';
	}
	public function getData() {
		return $this->getFullName() . ' ' . $this->_age;
	}
	public function dump($aray) {
		foreach ($this->tomb as $key => $value) {
			echo($key . ' => ' . $aray[$key] . "\n");
		}
	}

}
function _ember($fn,$ln,$a) {
	return new Ember($fn,$ln,$a);
}

echo(_ember('A','B',12)->getData() . "\n");

$szoveg = "ez \"macsakkörömben van\"";
$szoveg .= ' 123';
$tomb = array(1, 2, 'aaa', JSON_decode('{"nev":"FT", "tel":array(1,2,3)}'));
$obj = JSON_decode('{"a":"AAA", "B":123}');
$ember = new Ember('Teszt','Elek',22);
echo($ember->getFullName() . "\n");
echo($ember->getData() . "\n");

function proba($obj)  {
	return $obj->getFullName();
}

$i = 12.5;
for ($i = 0; $i < 10; $i++) {
	echo($i . "\n");
}

$szam = 12;
echo(proba($ember) . "\n");
$ember->dump($ember->tomb);

$db = new Xdb();
$db->setQuery(' 
select *
from #__tableName
where title <> "emty"
order by created_time
');
$records = $db->loadObjectList();

$subSelect = new Xselect();
$subSelect->setFieldList('*')
->setFrom('x', 'table_x');

$select = new Xselect();
$select->setFieldList('a.*, b.title')
->setFrom('a', 'table_a')
->addJoin('LEFT OUTER', 'b', 'tabla_b', 'b.id = a.id')
->addSubselectJoin('RIGHT OUTER', 'c', $subSelect, 'c.id = a.id')
->setWhere('a.id > 0')
->setOrder('a.title');
$records = $db->loadSelect($select, 10, 100); 

echo($db->getQuery());

exit(2);

?>
