<?php
// generated the ts2php 2018.03.06 07:34:20

/** 
* példa ts file 
* 2018
*/

include_once "../ts2php_core/tsphpx.php";

$mytomb = XARRAY();

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

exit(2);

?>
