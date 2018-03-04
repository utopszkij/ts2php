
/** 
* példa ts file 
* 2018
*/

import {Xarray, XARRAY, Xstr, XSTR, Xdb,  
	_POST, _GET, _SERVER, _CHOCKIE, echo, exit, isset, file_get_contents, 
	session_start, session_id, base64_decode, base64_encode}
	from "../ts2php_core/tsphp";

var mytomb = XARRAY();
console.log('mytomb.count()='+mytomb.count());

_POST['abc'] = _GET['abc'];

class Szemely {
	protected _firstName: string;
	protected _lastName: string;
	constructor(firstName: string, lastName: string) {
		this._firstName = firstName;
		this._lastName = lastName;
		_GET['x'] = 'y';
	}
	public getFullName(): string {
		return this._firstName + ' ' + this._lastName;
	}
}

interface Pofa extends Szemely {
   getFullName(): string
}

var szemely = new Szemely('Teszt','Elek'); // sorvégi komment
echo(szemely.getFullName());

class Ember extends Szemely {
	private _age: number;
	public tomb: string[];
	protected x: string;
	constructor(firstName: string, lastName: string, age: number) {
		super(firstName, lastName);
		this._age = age;
		this.tomb = [];
		this.tomb[0] = 'aaa';
		this.tomb[1] = 'bbb';
		this.tomb['c'] = 'cc';
	}
	public getData(): string {
		return this.getFullName() + ' ' + this._age;
	}
	public dump(aray: string[]): void {
		for (let key in this.tomb) {
			echo(key+' => '+aray[key]);
		}
	}

}
function _ember($fn,$ln,$a) {
	return new Ember($fn,$ln,$a);
}

echo(_ember('A','B',12).getData());

var szoveg = "ez \"macsakkörömben van\"";
szoveg += ' 123';
var tomb = [1, 2, 'aaa', {"nev":"FT", "tel":[1,2,3]}];
var obj = {"a":"AAA", "B":123};
var ember = new Ember('Teszt','Elek',22);
echo(ember.getFullName());
echo(ember.getData());

function proba(obj: Pofa) : string {
	return obj.getFullName();
}

var i: number = 12.5;
for (i = 0; i < 10; i++) {
	echo(i);
}

var szam: number = 12;
echo(proba(ember));
ember.dump(ember.tomb);

exit(2);

