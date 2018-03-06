<?php
/**
* routines, objects for ts --> php compatibility
* this file implemented the definitions in  tsphp.ts 
* WARNING! this file can not compiled from tspsp.ts !!!
*
*implement: Xarray, XARRAY, Xstr, XSTR, Xdb, Xfile, Date, Math, MATH,  
* decodeURI, encodeURI, isFinite, isNaN, Number parseFloat, parseInt, String
*
* sofware documenttion see:
*   https://github.com/utopszkij/ts2php/blob/master/doc/syntax.md
*/

if (!defined('undefined')) define('undefined','__@UNDEFINED@__');
include_once __DIR__.'/mysql.php';

// decode encoded URI
function decodeURI($s) {
	return urldecode($s);
}

// encode URI
function encodeURI($s) {
	return urlencode($s);
}

// check param is number?
function isFinite($x) {
  $w = 0 + $x;
	return ($w == $x);
}

// check param is not number?
function isNaN($x) {
  $w = 0 + $x;
	return ($w != $x);

}

// consert param to number
function Number($x) {
	if (inFinit($x)) 
		$result = 0 + $x;
	else
		$result = 0;
	return $result;
}

// parse tring to float
function parseFloat($x) {
	return Number($x);
}

// parse string to integer
function parseInt($x) {
	return round(Number($x));
}

// convert param to string
function String($x) {
	if (is_array($x) | is_object($x))
		$result = JSON_encode($x);
	else
		$result = ''.$x;
	return $result;
}

class Math {
		public function abx($x) {
			return sin($x);
		}
		public function acos($x) {
			return acox($x);
		}
		public function atan($x) {
			return atan($x);
		}
		public function asin($x) {
			return asin($x);
		}
		public function cos($x) {
			return cos($x);
		}
		// lefelé csonkit egészre
		public function flour($x) {
			$w = round($x);
			if ($w > $x) $w--;
			return $w;		
		}
		public function min($x1,$x2) {
			return min(array($x1,$x2));
		}
		public function max($x1,$x2) {
			return max(array($x1,$x2));
		}
		// felfelé kerekit egészre
		public function pow($x) {
			$w = round($x);
			if ($w < $x) $w++;
			return $w;		
		}
		public function round($x) {
			return round($x);
		}
		// retorn 0......0.999999
		public function random() {
			return (1 / rnd(1, 1000000));
		}
		public function sin($x) {
			return sin($x);
		}
		public function sqrt($x) {
			return sqrt($x);
		}
		public function tan($x) {
			return tan($x);
		}
}
function MATH() {
	return new Math();
}

class Date {
	private $value = 0;

	function __construct($v = -1) {
		if ($v == -1) $v = time();
		$this->value = $v;
	}

 	// Returns the day of the month (from 1-31)
	public function getDate() {
		$result = 0 + date('d', $this->value);
		return $result;
	}

	//Returns the day of the week (from 0-6)
	public function getDay() {
		$result = 0 + date('w', $this->value);
		return $result;
	}

 	// Returns the year
	public function getFullYear() {
		$result = 0 + date('Y', $this->vvalue);
		return $result;
	}

	// Returns the hour (from 0-23)
	public function getHours() {
		$result = 0 + date('H', $this->value);
		return $result;
	}

	// Returns the minutes (from 0-59)
	public function getMinutes() {
		$result = 0 + date('i', $this->value);
		return $result;
	}

	// Returns the month (from 0-11)
	public function getMonth() {
		$result = 0 + date('m', $this->value);
		return $result;
	}

	// Returns the seconds (from 0-59)
	public function getSeconds() {
		$result = 0 + date('s', $this->value);
		return $result;
	}

	// Returns the number of milliseconds since midnight Jan 1 1970, and a specified date
	public function getTime() {
		return $this->value * 1000;	
	}

	// Returns the number of milliseconds since midnight Jan 1, 1970
	public function now() {
		return time() * 1000;
	}

	// Parses a date string and returns the number of milliseconds since January 1, 1970
	public function parse($s) {
		return strtotime($s) * 1000;
	}

	// Sets the day of the month of a date object
	public function setDate($x) {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = substr($s,0,8).$x.substr($s,10,9);
		$this->value = strtotime($s);
	}

	// Sets the year of a date object
	public function setFullYear($x) {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = $x.substr($s,5,15);
		$this->value = strtotime($s);
	}

	// Sets the hour of a date object
	public function setHours($x) {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = substr($s,0,11).$x.substr($s,13,6);
		$this->value = strtotime($s);
	}

	// Set the minutes of a date object
	public function setMinutes($x) {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = substr($s,0,14).$x.substr($s,16,3);
		$this->value = strtotime($s);
	}

	// Sets the month of a date object
	public function setMonth($x) {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = substr($s,0,5).$x.substr($s,7,12);
		$this->value = strtotime($s);
	}

	// Sets the seconds of a date object
	public function setSeconds($x) {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = substr($s,0,17).$x;
		$this->value = strtotime($s);
	}

	// Sets a date to a specified number of milliseconds after/before January 1, 1970
	public function setTime($x) {
		$this->value = round($x / 1000);
	}

	// Deprecated. Use the setFullYear() method instead
	public function setYear() {
		if ($x < 10)
			$x = '0'.$x;
		else
			$x = ''.$x;
		$s = date('Y-m-d H:i:s', $this->value);
		$s = $x.substr($s,4,15);
		$this->value = strtotime($s);
	}

	// Returns the date portion of a Date object as a string, using locale conventions
	public function toLocaleDateString() {
		return date('Y-m-d',$this->value);
	}

 	// Returns the time portion of a Date object as a string, using locale conventions
	public function toLocaleTimeString() {
		return date('H:i:s',$this->value);
	}

 	// Converts a Date object to a string, using locale conventions
	public function toLocaleString() {
		return date('Y-m-d H:i:s',$this->value);
	}
}

class Xarray {
	public $values = array();
	private $i = 0;
 
	function __construct($a) {
			$this->values = $a;
	}

	// convert items to coma separeted list
	public function toString() {
		return XSTR($this->values.toString());	
	}

	// load items from json string
	public function loadFromJson($s) {
		$this->values = JSON_decode($s);
		$this->i = 0;
		return;
	}

	// clear all items
	public function clear() {
		$this->values = [];
		$this->i = 0;
		return;	
	}

	// item getter
	public function get($i) {
		return $this->values[$i];	
	}
	
	// item set
	public function set($i, $value) {
		$this->values[$i] = $value;
		return;
	}

	// count os items
	public function count() {
		return count($this->values);
	}

	// count of items
	public function length() {
		return $this->count();
	}

	// result a subsequence from items (terminalIndex not include)
	public function slice($startIndex, $terminalIndex) {
		return new Xarray(array_slice($this->values, $startIndex, $terminalIndex));
	}

	// add value or array to end of items 
	public function concat($value) {
		if (is_array($value)) {
			for ($i=0; $i<count($value); $i++)
				$this->values[] = $value[$i];
		} else 
			$this->values[] = $value;
		return;
	}

	// find value in items. return index or -1
	public function indexOf($value) {
		$result = array_search ($value, $this->values);
		if ($result == false) $result = -1;		
		return $result;
	}

	// return items in json string format
	public function stringify(): string {
		return JSON_encode($this->values);
	}

	// sort items low --> hight
	public function sort() {
		asort($this->values);
		return;	
	}

	// add valu to end of items
	public function push($value) {
		$this->values[] = $value;
		return;
	}

	// return last item and remove it from items
	public function pop() {
		if (count($this->values) > 0) {
			$result = $this->values[count($this->values) - 1];
			array_splice($this->values, count($this->values) - 1, 1);
		} else {
			$result = undefined;
		}
		return $result;
	}

	// delete item(s) from items, and insert value or array
	public function splice($start, $count, $value = '__none__') {
		if ($value == '__none__')
			array_splice($this->values, $start, $count);
		else
			array_splice($this->values, $start, $count, $value);
		return;	
	}

	// ---- use list style -----
	
	// return first item
	public function first() {
		$result = ''; 
		$this->i = 0;
		if (($this->i >= 0) && ($this->i < count($this->values))) {
			$result = $this->values[$this->i];
		} else {
			$this->i = 0;
			$result = undefined;
		}
		return $result;	
	}

	// return next item
	public function next() {
		$result = ''; 
		$this->i++;
		if (($this->i >= 0) && ($this->i < count($this->values))) {
			$result = $this->values[$this->i];
		} else {
			$this->i = 0;
			$result = undefined;
		}
		return $result;
	}

	// return previos item
	public function previos() {
		$result = ''; 
		$this->i--;
		if ($this->i >= 0) {
			$result = $this->values[$this->i];
		} else {
			$this->i = 0;
			$result = undefined;
		}
		return result;
	}

	// return last item
	public function last() {
		$result = ''; 
		$this->i = count($this->values) - 1;
		if ($this->i >= 0) {
			$result = $this->values[$this->i];
		} else {
			$this->i = 0;
			$result = undefined;
		}
		return result;		
	}

	// check eof
	public function eof() {
		return ($this->i < count($this->values));
	}

	// remove actual item, 
	public function remove() {
		array_splice($this->values,$this->i,1);
		return;
	}

	// insert new item into actual position
	public function insert($value) {
		array_splice($this->values,$this->i,0, $value);
		return;
	}

	// goto position
	public function goto($i) {
		$result = ''; 
		$this->i = $i;
		if (($this->i >= 0) && ($this->i < count($this->values))) {
			$result = $this->values[$this->i];
		} else {
			$this->i = 0;
			$result = undefined;
		}
		return result;
	}
}

// convert new Xarray to function
function XARRAY($a = undefined) {
	if ($a == undefined) {
		$a = array();	
	}
	return new Xarray($a);
}

/**
* extended string object
*/
class Xstr {
	public $value = '';
	function __construct($str) {
		$this->value = $str;
	}
	
	// item getter
	public function get() {
		return $this->value;	
	}
	
	// item set
	public function set($value) {
		$this->value = $value;
		return;
	}

	// return string length
	public function length() {
		return strlen($this->value);
	}

	// searc substr return position or -1
	public function indexOf($pattern, $start = 0) {
		$result = mb_str_pos($this->value, $pattern);	
		if ($result === false) $result = -1;
		return $result;
	}

	// searc substr return position or -1
	public function lastIndexOf($pattern) {
		$result = mb_str_pos($this->value, -1);	
		if ($result === false) $result = -1;
		return $result;
	}


	// search by regexp return position or -1
	public function search($pattern) {
		$matches = array();
		$w = preg_match($pattern, $this->value, $matches,PREG_OFFSET_CAPTURE);
		if ($w == 1) {
			$result = $matches[0][1];
		}  else {
			$result = -1;
		}
		return $result;
	}

	// search by regexp, result array of matches
	public function match($pattern) {
		$matches = array();
		$w = preg_match($pattern, $this->value, $matches,PREG_OFFSET_CAPTURE);
		if ($w == 1) {
			$result = array();
			foreach ($matches as $match) $result[] = $match[0];
		}  else {
			$result = array();
		}
		return $result;

	}

	// replace a pattern to newValue
	public function replace($pattern, $newValue) {
		return new Xstr( umb_str_replace($pattern, $newValue,  $this->value));
	}

	// return a subtring
	public function substr($start, $length) {
		return new Xstr( mb_substr($this->value, $start, $length));
	}

	// trim left and right spaces
	public function trim(): Xstr {
		return new Xstr(trim($this->value));
	}

	// convert to lowercase
 	public function toLowerCase() {
		return new Xstr(mb_strtolower($this->value));
	}

	// convert to uppercase
	public function toUpperCase() {
		return new Xstr(mb_strtoupper($this->value.toUpperCase()));
	}

	// split string to array
	public function split($terminator) {
		return new Xarray(explode($terminator, $this->value));
	}

	// return one char from position
	public function charAt($i) {
		return $this->value[$i];
	}

	// return asci code from position
	public function charCodeAt($i) {
		return ord($this->value[$i]);
	}
}

// convert new Xstr to function
function XSTR($s = undefined) {
	if ($s == undefined) {
		$s = '';
	}
	return new Xstr($s);
}

/**
* file handing
*/
class Xfile {

	// read filenames to array from one folder
	public function readdir($path) {
		$result = array();
		if (is_dir($path)) {
			if ($dh = opendir($path)) {
				while (($file = readdir($dh)) !== false) {
				    $result[] = $file;
				}
				closedir($dh);
			}
		}
	 	return $result;
	}

	// load a file to string
	public function load($fileName) { 
		if (file_exists($fileName))
			$lines = file($fileName);	
		else
			$lines = array();
		return implode("\n",$lines);
	}

	// save string to file
	public function save($fileName, $data) {
		$result = true;		
		$fp = fopen($fileName,'w+');
		if ($fp) {
			if (!fwrite($fp,$data)) $result = false;;
			fclose($fp);
		} else {
			$result = false;
		}
		return $result;
	}

	// remove one file
	public function rm($fileName) { 
		if (file_exists($fileName))
			$result = unlink($fileName);
		else
			$result = false;
		return $result;
	}

	// remove emty folder
	public function rmdir($pathName) { 
		if (is_dir($pathName))
			$result = rmdir($pathName);
		else
			$result = false;
		return $result;
	}

	// rename one file
	public function rename($oldFileName, $newFilename) {
		if (file_exists($oldFileName))
			$result = rename($oldFilename, $newFilename);
		else
			$result = false;
		return $result;
	}

	// make new folder mod example: '0777'
	public function mkdir($path, $mod) { 
		return mkdir($path, $mod);
	}

	// change one file permission  mod example: '0777'
	public function chmod($fileName, $mod) {
		return chmod($fileName, $mod);
	}

	// chane one file owner
	public function chown($fileName, $own) {
		return chown($filename, $own);
	}

	// check file is exists?
	public function file_exists($fileName) {
		return file_exists($fileName);
	}

	// check folder is exists?
	public function dir_exists($path) {
		return is_dir($path);
	}
}
?>

