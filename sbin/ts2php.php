<?php
/**
* ts to php parser
* use: php ts2php tsFileName (exlude .ts)
*/

/**
* ts file process object
*/

global $exitCode;

class Ts {
	private $tsString; // typescript
	private $cp;       // char pointer for getc
	public $lno; // sorszám

	/**
	* construct, read ts file from disc, implode to one string
	* @param string fileName (exlude .ts)
	*/
	function __construct($tsFileName) {
		$lines = file($tsFileName.'.ts');
		$this->tsString = implode("",$lines);
		$lines = '';
		$this->tsString = str_replace("\r",'',$this->tsString);
		$this->cp = 0;
		$this->lno = 1;
	}

	/**
	* check ts eof
	* @return bool
	*/
	public function eof() {
		return ($this->cp >= strlen($this->tsString));
	}

	/**
	* get next character from tsString
	*
	* @return string
	*/
	// \x   /*  */  is one 'virtula char'
	public function getc() {
		if ($this->cp < strlen($this->tsString)) {
			$result = $this->tsString[$this->cp];
			$this->cp++;
			if (($result == "\\") && ($this->cp < strlen($this->tsString))) {
				$result .= $this->tsString[$this->cp];
				$this->cp++;
			}
			if (($result == "/") && ($this->cp < strlen($this->tsString))) {
				if ($this->tsString[$this->cp] == '*') {
					$result .= $this->tsString[$this->cp];
					$this->cp++;
				}
			}
			if (($result == "*") && ($this->cp < strlen($this->tsString))) {
				if ($this->tsString[$this->cp] == '/') {
					$result .= $this->tsString[$this->cp];
					$this->cp++;
				}
			}
		} else {
			$result = '';
		}
		return $result;
	}

	/**
	* back one character into tsString
	*/
	public function backc($c) {
		if ($this->cp > 0) {
			$this->cp = $this->cp - strlen($c);
		}
	}

	/**
	* get token
	*   tokens: word, number, literal, spaces, specchar
	* @return string
	*/
	public function getToken() {
		$result = '';
		if (!$this->eof()) {
			$c = $this->getc();
			if (($c >= '0') & ($c <= '9')) {
				$result .= $c;
				$c = $this->getc();
				while (($c >= '0') & ($c <= '9')) {
	 				$result .= $c;
					$c = $this->getc();
				}
				if ($c == '.') {
					$result .= $c;
					$c = $this->getc();
					while (($c >= '0') & ($c <= '9')) {
		 				$result .= $c;
						$c = $this->getc();
					}
				}
				$this->backc($c);
			} else if ((($c >= 'a') & ($c <= 'z')) | 
					   (($c >= 'A') & ($c <= 'Z')) |
					   ($c == '_')
					  ) {
				$result .= $c;
				$c = $this->getc();
				while ((($c >= 'a') & ($c <= 'z')) | 
					   (($c >= 'A') & ($c <= 'Z')) |
					   ($c == '_')
					  ) {
					$result .= $c;
					$c = $this->getc();
				}
				$this->backc($c);
			} else if ($c == "'") {
				$this->backc($c);
				$result = $this->getLiteral("'","'");
			} else if ($c == '"') {
				$this->backc($c);
				$result = $this->getLiteral('"','"');
			} else if ($c == '/') {
				$c1 = $this->getc();
				if ($c == '/') {
					$this->backc($c);
					$this->backc($c);
					$result = $this->getLiteral('//',"\n");				
				} else {
					$this->backc($c);
					$result = '/';
				}
			} else if ($c == ' ') {
				$result .= $c;
				$c = $this->getc();
				while ($c == ' ') {
					$result .= $c;
					$c = $this->getc();
				}
				$this->backc($c);
			} else {
				$result = $c;
			}
		}
		if ($result == "\n") $this->lno++;
		return $result;
	}

	/**
	* get spaces token, enable not include blanks 
	* @return string
	*/
	public function getBlankToken() {
		$result = $this->getToken();
		if ($result == "\n") $result = $this->getToken();
		if ($result == "\r") $result = $this->getToken();
		if ($result == "\t") $result = $this->getToken();
		if (trim($result) != '') {
			$this->backToken($result);
			$result = '';		
		}
		return $result;	
	}

	/**
	* back one token into tsString
	* @param string 
	*/
	public function backToken($t) {
		if ($t == "\n") $this->lno--;
		$this->cp = $this->cp - strlen($t);
		if ($this->cp < 0) {
			$this->cp = 0;
		}	
	}

	/**
	* get literal from tsString
	* @param string start:apostrop, doublequote or {
	* @param string stop: apostrop, doublequote or }
	* @return string
	*/
	public function getLiteral($start, $stop) {
		global $exitCode;
		$result = '';
		$counter = 0; // subliteral counter
		$i = 0; // loop counter
		$result = $this->getc();
		if ($result == $start) $counter++;
		$c = $this->getc();
		while (!$this->eof() && ($c != $stop) && ($i < 1000)) {
			$result .= $c;
			if ($c == $start) $counter++;
			$c = $this->getc();
			if ($c == $stop) {
				if ($counter > 1) {
					$result .= $c;
					$counter--;
					$c = $this->getc();
				}
			}
			$i++;
		}
		if ($c == $stop) {
			$result .= $c;
		} else {
			echo 'ts2php syntax error line='.$this->lno.' not balanced '.$start.$stop."\n"; 	
			$exitCode = 1;
		}
		// echo 'getLiteral '.$start.$stop.' result='.$result."\n";
		return $result;
	}
}

function mnemonic($s) {
	return (ctype_alnum(str_replace('_','',$s)));
}

function processClass(& $ts) {
	global $exitCode;
	$result = '';
	$t1 = $ts->getBlankToken(); // ' '
	$t2 = $ts->getToken(); // className
	if (($t2 == '') || (!mnemonic($t2))) {
		echo 'ts2php syntax error line='.$ts->lno.' not valid class name '.$t2."\n"; 	
		$exitCode = 1;
	}
	$t3 = $ts->getBlankToken(); // ' '
	$t4 = $ts->getToken(); // extends ?
	if ($t4 == 'extends') {
		$t5 = $ts->getBlankToken(); // ' '
		$t6 = $ts->getToken(); // extends name
		$result .=  'class '.$t2.' extends '.$t6.' ';
	} else {
		$ts->backToken($t4);
		$result .=  'class '.$t2.' ';
	}
	return $result;
}

// next public | protected | private 
function processDec(& $ts, $t) {
	global $exitCode;
	$result = '';
	$t1 = $ts->getBlankToken(); // ' ';
	$t2 = $ts->getToken(); // név
	if (!mnemonic($t2)) {
		echo 'ts2php syntax error line='.$ts->lno.' '.$t2.' not mnemonic '."\n"; 
		$exitCode = 1;
	}
	$t3 = $ts->getToken(); // : vagy ( ?
	if ($t3 == ':') {
		// property deklaráció class -ban
		$ts->backToken($t3);
		$result .=  $t.$t1.'$'.$t2;
	} else if ($t3 == '(') {
		// method declaráció class -ban
		$ts->backToken($t3);
		$result .=  $t.$t1.'function '.$t2;
	} else {
		echo 'ts2php syntax error line='.$ts->lno.' not expected token '.$t.$t1.$t2.$t3."\n"; 
		$exitCode = 1;
	}
	return $result;
}

function processTypeDef(& $ts, $t) {
	global $exitCode;
	$result = '';
	$t1 = $ts->getBlankToken(); // ' ';
	$t2 = $ts->getToken(); // tipusnév vagy [
	if ($t2 == '[') {
		$ts->backToken($t2);
		$t3 = $ts->getLiteral('[',']');
	} else {
		$t3 = $ts->getToken(); // [ ?
		if ($t3 == '[') {
			$t4 = $ts->getToken(); // ]
			if ($t4 != ']') {
				echo 'ts2php syntax error line='.$ts->lno.'not expected token '.$t.$t1.$t2.$t3.$t4."\n"; 
				$exitCode = 1;
			}
		} else {
			$ts->backToken($t3);
		}
	}
	return $result;
}

function processPoint(& $ts, $t) {
	$result = '';
	$result .=  '->';
	$t1 = $ts->getToken();
	if (mnemonic($t1)) {
		$result .=  $t1;
	} else {
		$ts->backToken($t1);
	}
	return $result;
}

function processPlus(& $ts, $t) {
	$result = '';
	$t1 = $ts->getToken();
	if ($t1 == '=') {
		$result .=  '.=';
	} else if ($t1 == '+') {
		$result .= '++';
	} else if ($t1 == ' ') {
		$result .=  '. ';
	} else {
		$ts->backToken($t1);
		$result .=  $t;
	}
	return $result;
}

// [.....]
function processBlock(& $ts, $t, $lastToken) {
	$result = '';
	if (($lastToken == '=') || ($lastToken == ',') || ($lastToken == '(') || 
		($lastToken == 'in') || ($lastToken == ':')
	   ) {
			$result .=  'array(';
			$result .= parser($ts,']',')');		
			$lastToken = ']';
	} else {
			$result .=  '[';
			$result .= parser($ts,']',']');
	}
	return $result;
}

// {....}
function processBlock2(& $ts, $t, $lastToken)  {
	$result = '';
	if (($lastToken == '=') || ($lastToken == ',') || ($lastToken == '(') || 
		($lastToken == 'in') || ($lastToken == ':')
	   ) {
			$result .=  "JSON_decode('{";
			$result .= parser($ts,'}',"}')");		
	} else {
			$result .=  '{';
			$result .= parser($ts,'}','}');
	}
	return $result;
}

function processSuper(& $ts, $t) {
	$result = '';
	$t1 = $ts->getToken(); // ( ?
	if ($t1 == '(') {
		$result .=  'parent::__construct(';
	} else {
		$ts->backToken($t1);
		$result .=  '$super';
	}
	return $result;
}

function processConstructor(& $ts, $t) {
	$result = '';
	$t1 = $ts->getToken(); // ( ?
	if ($t1 == '(') {
		$result .=  'function __construct(';
	} else {
		$ts->backToken($t1);
		$result .=  '$constructor';
	}
	return $result;
}

function processImport(& $ts, $t) {
	global $exitCode;
	$result = '';
	$t0 = $ts->getBlankToken();
	$t1 = $ts->getLiteral('{','}');
	$t2 = $ts->getBlankToken();
	$t3 = $ts->getToken(); // from
	if ($t3 != 'from') {
		echo 'ts2php syntax error line='.$ts->lno.' excepted "from" actual:"'.$t3.'"'."\n"; 
		$exitCode = 1;
	}
	$t4 = $ts->getBlankToken();
	$t5 = $ts->getLiteral('"','"').$ts->getToken(); // "....";
	$t5 = str_replace('";','.php";',$t5); 
	$t5 = str_replace('tsphp.php','tsphpx.php',$t5);
	$result .=  'include_once '.$t5;
	return $result;
}

function processFor(& $ts, $t) {
	$result = '';
	$t1 = $ts->getBlankToken(); // ' '
	$t2 = $ts->getToken(); // (
	$t3 = $ts->getToken(); // let ?
	if ($t3 == 'let') {
		$t4 = $ts->getBlankToken(); // ' '
		$t5 = $ts->getToken(); // item
		$t6 = $ts->getBlankToken(); // ' '
		$t7 = $ts->getToken(); // in
		$t8 = $ts->getBlankToken(); // ' '
		$t9 = $ts->getToken(); // array
		$tw = $ts->getToken(); // )
		while ($tw != ')') {
			if ($tw == '.')
				$t9 .= '->';
			else
				$t9 .= $tw;
			$tw = $ts->getToken();
		}
		$result .=  'foreach ($'.$t9.' as $'.$t5.' => $value)';
	} else {
		$ts->backToken($t3);
		$ts->backToken($t2);
		$ts->backToken($t1);
		$result .=  'for';
	}
	return $result;
}

function processMnemonic(& $ts, $t) {
	$result = '';
	$t1 = $ts->getToken();
	if ($t1 == '(') {
		$result .=  $t.$t1;
	} else {
		$ts->backToken($t1);
		$result .=  '$'.$t;
	}
	return $result;
}

function processComment(& $ts) {
	$w = $ts->getLiteral('/*','*/');
	$w = str_replace('/*html','?>',$w);
	$w = str_replace('html*/','<?php',$w);
	$w = str_replace('/*php','',$w);
	$w = str_replace('php*/','',$w);
	$w = str_replace("/*'","'",$w);
	$w = str_replace("'*/","'",$w);
	return $w;
}

/**
* parse tsString, $result .=  php string
* @param Ts
* @param string terminator: ] or }
* @param string terminator $result .=  string
* @return string
*/	
function parser(& $ts, $terminator, $terminatorResult) {
	global $exitCode;
	$result = '';
	$lastToken = '';
	$t = $ts->getToken();
	while ((!$ts->eof()) & ($t != $terminator)) {
		if ($t == '/*') {
			$ts->backToken($t);
			$result .= processComment($ts);
			$lastToken = $t;
		} else if ($t == 'class') {
			$result .= processClass($ts);
			$lastToken = 'classname';
		} else if (($t == 'if') | ($t == 'else') | ($t == 'while') | ($t == 'return')) {
			$result .=  $t;
			$lastToken = $t;
		} else if ($t == 'var') {
			$t1 = $ts->getBlankToken();
		} else if ($t == 'new') {
			$t1 = $ts->getBlankToken(); // ' '
			$t2 = $ts->getToken(); // className
			$result .=  'new '.$t2;
			$lastToken = $t;
		} else if (($t == 'public') | ($t == 'protected') | ($t == 'private')) {
			$result .= processDec($ts, $t);
			$lastToken = $t;
		} else if ($t == ':') {
			if ($terminatorResult == "}')") {
				$result .=  $t;
				$lastToken = $t;
			} else {
				$result .= processTypeDef($ts, $t);
				$lastToken = 'typeName';
			}
		} else if ($t == '.') {
			$result .= processPoint($ts, $t);
			$lastToken = $t;
		} else if ($t == '+') {
			$result .= processPlus($ts,$t);
			$lastToken = $t;
		} else if ($t == '=') {
			$result .=  $t;
			$lastToken = $t;
		} else if ($t == '?') {
			$lastToken = $t;
		} else if ($t == '[') {
			$result .= processBlock($ts, $t, $lastToken);
			$lastToken = $t;
		} else if ($t == '{') {
			$result .= processBlock2($ts, $t, $lastToken);
		    $lastToken = '}';
		} else if ($t == 'function') {
			$t1 = $ts->getBlankToken(); // ' '
			$t2 = $ts->getToken(); // funName
			$result .=  'function '.$t2;
			$lastToken = $t;
		} else if ($t == 'super') {
			$result .= processSuper($ts, $t);
			$lastToken = $t;
		} else if ($t == 'constructor') {
			$result .= processConstructor($ts, $t);
			$lastToken = $t;
		} else if ($t == 'interface') {
			$t1 = $ts->getLiteral('@','}');
			$result .=  "/*\n".$t.$t1."\n*/\n";
			$lastToken = $t;
		} else if ($t == 'import') {
			$result .= processImport($ts, $t);
			$lastToken = $t;
		} else if ($t == 'export') {
			$t1 = $ts->getLiteral('@',';');
			$lastToken = $t;
		} else if ($t == 'Math') {
			$result .=  'MATH()';
			$lastToken = $t;
		} else if ($t == 'for') {
			$result .= processFor($ts, $t);
			$lastToken = $t;
		} else if ($t == 'catch') {
			$t1 = $ts->getToken(); // (
			$t2 = $ts->getToken(); // e
			$t3 = $ts->getToken(); // )
			$result .=  'catch(Exception $'.$t2.')';
		} else if (is_numeric($t)) {
			$result .=  $t;
			$lastToken = $t;
		} else if (mnemonic($t)) {
			$result .= processMnemonic($ts, $t);
			$lastToken = $t;
		} else {
			$result .=  $t;
			if (trim($t) != '')
				$lastToken = $t;
		}
		$t = $ts->getToken();
	} // while
	$result .=  $terminatorResult;
	return $result;
}

// main
$exitCode = 0;
echo 'ts2php start '.date('H:i:s')."\n";
$ts = new Ts($argv[1]);
$result =  '<?php'."\n";
$result .=  '// generated the ts2php '.date('Y.m.d H:i:s')."\n";
$result .= parser($ts,'','');
$result .=  ''."\n".'?>'."\n";
$fp = fopen($argv[1].'.php','w+');
fwrite($fp, $result);
fclose($fp);
echo 'ts2php stop '.date('H:i:s')."\n";
exit($exitCode);
?>

