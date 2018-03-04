<?php
// generated the ts2php 2018.03.04 09:00:19

/** 
* példa ts file 
* 2018
*/

include_once "../ts2php_core/tsphpx.php";

$mytomb = XARRAY();
$console->log('mytomb.count()='+$mytomb->count());

$_POST['abc'] = $_GET['abc'];

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
		foreach (this->tomb as $key => $value) {
			echo($key+' => '+$aray[$key]);
		}
	}

}
function _ember($$fn,$$ln,$$a) {
	return new Ember($$fn,$$ln,$$a);
}

echo(_ember('A','B',12)->getData());

$szoveg = "ez \"macsakkörömben van\"";
$szoveg .= ' 123';
$tomb = array(1, 2, 'aaa', JSON_decode('{"nev":"FT", "tel":array(1,2,3)}'));
$obj = JSON_decode('{"a":"AAA", "B":123}');
$ember = new Ember('Teszt','Elek',22);
echo($ember->getFullName());
echo($ember->getData());

function proba($obj)  {
	return $obj->getFullName();
}

$i = 12.5;
for ($i = 0; $i < 10; $i++) {
	echo($i);
}

$szam = 12;
echo(proba($ember));
$ember->dump($ember->tomb);

exit(2);

?>
