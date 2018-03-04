
/**
* routines, objects for php compatibility
*
* exports: Xarray, XARRAY, Xstr, XSTR, Xdate, XDATE,
* Xdb, Xfile, 
* _GET, _POST, _SERVER, _CHOCKIE
* echo, exit, isset, file_get_contents, session_start, session_id,
* base64_decode, base64_encode
*/

export var _GET = [];
export var _POST = [];
export var _SERVER = [];
export var _CHOCKIE = [];
export var _SESSION = [];

/**
* extended array
*/
export class Xarray {
	public values: any[] = [];
	private i: number = 0; 
	constructor(a: any[]) {
			this.values = a;
	}

	// convert items to coma separeted list
	public toString(): Xstr {
		return XSTR(this.values.toString());	
	}

	// load items from json string
	public loadFromJson(s: string): void {
		this.values = JSON.parse(s);
		this.i = 0;
		return;
	}

	// clear all items
	public clear(): void{
		this.values = [];
		this.i = 0;
		return;	
	}

	// item getter
	public get(i: number): any {
		return this.values[i];	
	}
	
	// item set
	public set(i: number, value: any): void {
		this.values[i] = value;
		return;
	}

	// count os items
	public count(): number {
		return this.values.length;
	}

	// count of items
	public length(): number {
		return this.count();
	}

	// result a subsequence from items (terminalIndex not include)
	public slice(startIndex: number, terminalIndex: number): Xarray {
		return new Xarray(this.values.slice(startIndex, terminalIndex));
	}

	// add value or array to end of items 
	public concat(value: any): void {
		this.values.concat(value);
		return;
	}

	// find value in items. return index or -1
	public indexOf(value: any): number {
		return this.values.indexOf(value);
	}

	// return items in json string format
	public stringify(): string {
		return JSON.stringify(this.values);
	}

	// sort items low --> hight
	public sort(): void {
		this.values.sort();
		return;	
	}

	// add valu to end of items
	public push(value: any): void {
		this.values.push(value);
		return;
	}

	// return last item and remove it from items
	public pop(): any {
		return this.values.pop();	
	}

	// delete item(s) from items, and insert value or array
	public splice(start: number, count: number, value?: any) {
		this.values.splice(start, count, value);
		return;	
	}

	// ---- use list style -----
	
	// return first item
	public first(): any {
		var result: any; 
		this.i = 0;
		if ((this.i >= 0) && (this.i < this.values.length)) {
			result = this.values[this.i];
		} else {
			this.i = 0;
			result = undefined;
		}
		return result;	
	}

	// return next item
	public next(): any {
		var result: any; 
		this.i++;
		if ((this.i >= 0) && (this.i < this.values.length)) {
			result = this.values[this.i];
		} else {
			this.i = 0;
			result = undefined;
		}
		return result;
	}

	// return previos item
	public previos(): any {
		var result: any; 
		this.i--;
		if (this.i >= 0) {
			result = this.values[this.i];
		} else {
			this.i = 0;
			return undefined;
		}
		return result;
	}

	// return last item
	public last(): any {
		var result: any; 
		this.i = this.values.length - 1;
		if (this.i >= 0) {
			result = this.values[this.i];
		} else {
			this.i = 0;
			return undefined;
		}
		return result;		
	}

	// check eof
	public eof(): boolean {
		return (this.i < this.values.length);
	}

	// remove actual item, 
	public remove(): void {
		this.values.splice(this.i,1);
		return;
	}

	// insert new item inti actual position
	public insert(value:any): void {
		this.values.splice(this.i,0, value);
		return;
	}

	// goto position
	public goto(i: number): any {
		var result: any; 
		this.i = i;
		if ((this.i >= 0) && (this.i < this.values.length)) {
			result = this.values[this.i];
		} else {
			this.i = 0;
			result = undefined;
		}
		return result;
	}
}

// convert new Xarray to function
export function XARRAY(a?: any[]):Xarray {
	if (a == undefined) {
		a = [];	
	}
	return new Xarray(a);
}

/**
* extended string object
*/
export class Xstr {
	public value: string;
	public $str = '';
	constructor(str: string) {
		this.value = str;
	}
	
	// item getter
	public get(): string {
		return this.value;	
	}
	
	// item set
	public set(value: string): void {
		this.value = value;
		return;
	}

	// return string length
	public length(): number {
		return this.value.length;
	}

	// searc substr return position or -1
	public indexOf(pattern: string, start?: number): number {
		return this.value.indexOf(pattern);	
	}

	// searc substr return position or -1
	public lastIndexOf(pattern: string): number {
		return this.value.lastIndexOf(pattern);	
	}

	// search by regexp return position or -1
	public search(pattern: string): number {
		return this.search(pattern);	
	}
	public match(pattern: string): string[] {
		return this.match(pattern);	
	}
	public replace(pattern: string ,newValue: string): Xstr {
		return new Xstr(this.value.replace(pattern, newValue));
	}
	public substr(start: number, length: number): Xstr {
		return new Xstr(this.value.substr(start, length));
	}
	public trim(): Xstr {
		return new Xstr(this.value.trim());
	}
 	public toLowerCase(): Xstr {
		return new Xstr(this.value.toLowerCase());
	}

	public toUpperCase(): Xstr {
		return new Xstr(this.value.toUpperCase());
	}
	public split(terminator: string): Xarray {
		return new Xarray(this.value.split(terminator));
	}
	public charAt(i: number): string {
		return this.value.charAt(i);
	}
	public charCodeAt(i: number): number {
		return this.value.charCodeAt(i);
	}
}

// convert new Xstr to function
export function XSTR(s?: string) {
	if (s == undefined) {
		s = '';
	}
	return new Xstr(s);
}


/**
* sql database object (implemented only in php)
*/
export class Xdb {
	private sql: string = '';
	// set query string
	setQuery(sql: string): void {
		this.sql = sql;
		return;
	}

	// get query string
	getQuery(): string {
		return this.sql;
	}

	// exec sql
	query(): boolean {
		return true;
	}

	// load one record  setted sql: "select ...... limit 1"
	loadObject():any {
		return {};
	}

	// load record set  setted sql: "select ........."
	loadObjectList(): any[] {
		return [];
	}

	// get last sql process error message (OK = '')
	getErrorMsg(): string {
		return '';
	}

	// get last sql process error number (OK = 0)
	getErrorNum(): number {
		return 0;
	}

	// adjust value for sql value format
	quote(s: any): string {
		return '"'+s+'"';
	}
}

/**
* file processing  implemented only in php
*/
export class Xfile {
	public readdir(path: string): string[] {
		return [];
	}
	public load(fileName: string):string {
		return '';
	}
	public save(fileName: string, data: string): boolean {
		return true;
	}
	public rm(fileName: string) : boolean {
		return true;
	}
	public rename(oldFileName: string, newFileName: string): boolean {
		return true;
	}
	public rmdir(fileName: string): boolean {
		return true;
	}
	public mkdir(path: string, mod: string): boolean {
		return true;
	}
	public chmod(fileName: string, mod: string): boolean {
		return true;
	}
	public chown(fileName: string, own: string): boolean {
		return true;
	}
}

// echo to stdout
export function echo(s: any): void {
	console.log(s);
}

// exit program 
export function exit(i?: number): void {
	return;
}

// variable exists?
export function isset(x?: any): boolean {
	var result: boolean;
	if (x == undefined) {
		result = false;
	} else {
		result = true;
	}
	return result;
}

// php file_get implemented only php
export function file_get_contents(s: string): string {
	return '';
}

// php session handing implemented only php
export function session_start(): void {}
export function session_id(x?: number): number {return 1;}

// base64  decode/encode implemented only php
export function base64_encode(s: string): string {
	return s;
}
export function base64_decode(s: string): string {
	return s;
}


/* Can use Javascript default 
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
	Date
		
*/

