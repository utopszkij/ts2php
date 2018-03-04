<?php
/**
* routines, objects for ts php compatibility
* this file implemented the tsphp.ts 
* WARNING! this file can not compiled from tspsp.ts
*
*implement: Xarray, XARRAY, Xstr, XSTR, Xdbo, XDBO, Date, Xfile 
*/

class Xarray {
	public $values: array();
	private $i = 0; 
	function __construct($a) {
			$this->values = $a;
	}

	// convert items to coma separeted list
	public function toString() {
		return XSTR($this->values.toString());	
	}

	// load items from json string
	public function loadFromJson(s: string): void {
		$this->values = JSON.parse(s);
		$this->i = 0;
		return;
	}

	// clear all items
	public function clear(): void{
		$this->values = [];
		$this->i = 0;
		return;	
	}

	// item getter
	public function get(i: number): any {
		return $this->values[i];	
	}
	
	// item set
	public function set(i: number, value: any): void {
		$this->values[i] = value;
		return;
	}

	// count os items
	public function count(): number {
		return $this->values.length;
	}

	// count of items
	public function length(): number {
		return $this->count();
	}

	// result a subsequence from items (terminalIndex not include)
	public function slice(startIndex: number, terminalIndex: number): Xarray {
		return new Xarray($this->values.slice(startIndex, terminalIndex));
	}

	// add value or array to end of items 
	public function concat(value: any): void {
		$this->values.concat(value);
		return;
	}

	// find value in items. return index or -1
	public function indexOf(value: any): number {
		return $this->values.indexOf(value);
	}

	// return items in json string format
	public function stringify(): string {
		return JSON.stringify($this->values);
	}

	// sort items low --> hight
	public function sort(): void {
		$this->values.sort();
		return;	
	}

	// add valu to end of items
	public function push(value: any): void {
		$this->values.push(value);
		return;
	}

	// return last item and remove it from items
	public function pop(): any {
		return $this->values.pop();	
	}

	// delete item(s) from items, and insert value or array
	public function splice(start: number, count: number, value?: any) {
		$this->values.splice(start, count, value);
		return;	
	}

	// ---- use list style -----
	
	// return first item
	public function first(): any {
		var result: any; 
		$this->i = 0;
		if (($this->i >= 0) && ($this->i < $this->values.length)) {
			result = $this->values[$this->i];
		} else {
			$this->i = 0;
			result = undefined;
		}
		return result;	
	}

	// return next item
	public function next(): any {
		var result: any; 
		$this->i++;
		if (($this->i >= 0) && ($this->i < $this->values.length)) {
			result = $this->values[$this->i];
		} else {
			$this->i = 0;
			result = undefined;
		}
		return result;
	}

	// return previos item
	public function previos(): any {
		var result: any; 
		$this->i--;
		if ($this->i >= 0) {
			result = $this->values[$this->i];
		} else {
			$this->i = 0;
			return undefined;
		}
		return result;
	}

	// return last item
	public function last(): any {
		var result: any; 
		$this->i = $this->values.length - 1;
		if ($this->i >= 0) {
			result = $this->values[$this->i];
		} else {
			$this->i = 0;
			return undefined;
		}
		return result;		
	}

	// check eof
	public function eof(): boolean {
		return ($this->i < $this->values.length);
	}

	// remove actual item, 
	public function remove(): void {
		$this->values.splice($this->i,1);
		return;
	}

	// insert new item inti actual position
	public function insert(value:any): void {
		$this->values.splice($this->i,0, value);
		return;
	}

	// goto position
	public function goto(i: number): any {
		var result: any; 
		$this->i = i;
		if (($this->i >= 0) && ($this->i < $this->values.length)) {
			result = $this->values[$this->i];
		} else {
			$this->i = 0;
			result = undefined;
		}
		return result;
	}
}

// convert new Xarray to function
function XARRAY(a?: any[]):Xarray {
	if (a == undefined) {
		a = [];	
	}
	return new Xarray(a);
}

/**
* extended string object
*/
class Xstr {
	public value: string;
	public $str = '';
	__construct(str: string) {
		$this->value = str;
	}
	
	// item getter
	public function get(): string {
		return $this->value;	
	}
	
	// item set
	public function set(value: string): void {
		$this->value = value;
		return;
	}

	// return string length
	public function length(): number {
		return $this->value.length;
	}

	// searc substr return position or -1
	public function indexOf(pattern: string, start?: number): number {
		return $this->value.indexOf(pattern);	
	}

	// searc substr return position or -1
	public function lastIndexOf(pattern: string): number {
		return $this->value.lastIndexOf(pattern);	
	}

	// search by regexp return position or -1
	public function search(pattern: string): number {
		return $this->search(pattern);	
	}
	public function match(pattern: string): string[] {
		return $this->match(pattern);	
	}
	public function replace(pattern: string ,newValue: string): Xstr {
		return new Xstr($this->value.replace(pattern, newValue));
	}
	public function substr(start: number, length: number): Xstr {
		return new Xstr($this->value.substr(start, length));
	}
	public function trim(): Xstr {
		return new Xstr($this->value.trim());
	}
 	public function toLowerCase(): Xstr {
		return new Xstr($this->value.toLowerCase());
	}

	public function toUpperCase(): Xstr {
		return new Xstr($this->value.toUpperCase());
	}
	public function split(terminator: string): Xarray {
		return new Xarray($this->value.split(terminator));
	}
	public function charAt(i: number): string {
		return $this->value.charAt(i);
	}
	public function charCodeAt(i: number): number {
		return $this->value.charCodeAt(i);
	}
}

// convert new Xstr to function
function XSTR(s?: string) {
	if (s == undefined) {
		s = '';
	}
	return new Xstr(s);
}

/**
* sql database object (implemented only in php)
*/
class Xdb {
	private sql: string = '';
	// set query string
	public function setQuery(sql: string): void {
		$this->sql = sql;
		return;
	}

	// get query string
	public function getQuery(): string {
		return $this->sql;
	}

	// exec sql
	public function query(): boolean {
		return true;
	}

	// load one record  setted sql: "select ...... limit 1"
	public function loadObject():any {
		return {};
	}

	// load record set  setted sql: "select ........."
	public function loadObjectList(): any[] {
		return [];
	}

	// get last sql process error message (OK = '')
	public function getErrorMsg(): string {
		return '';
	}

	// get last sql process error number (OK = 0)
	public function getErrorNum(): number {
		return 0;
	}

	// adjust value for sql value format
	public function quote(s: any): string {
		return '"'+s+'"';
	}
}

// convert new Xdbo to function	
function XDBO(): Xdbo {
	return new Xdbo();
}


/*
Math..... in php  MATH()........
		abx(x)
		acos(x)
		atan(x)
		asin(x)
		cos(x)
		flour(x) // lefelé csonkit egészre
		min(x1,x2)
		max(x1,x2)
		pow(x) // felfelé kerekit egészre
		round(x)
		random() // retorn 0......0.999999
		sin(x)
		sqrt(x)
		tan(x)

Xfile
	.readdir(path):array
	.load(fileName):string
	.save(fileName, data)
	.rm(filename)
	.rmdir(filename)
	.mkdir(path, mod)
	.chmod(filename, mod)
	.chown(fileName, own)

		
*/

