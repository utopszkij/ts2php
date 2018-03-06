# ts2php usable syntax

## include

>**import** { list of class names, function names, var names } **from** "fileName";

>fileName not include ".ts"

## export

>**expor** name, name,....;

## constans

>'characters double quotation marks are allowed'	

>"characters apostroph, \\", \\r, \\n, \\t, \\\\, are allowed"	

>\/\*' multiline string constant double quotation marks are allowed '\*\/

>1234

>\-12345

>12.13

>\-12.13

## variable names

>small and large letters, digits and underscore, begins with the letter	

## data types

- **number**
- **string**
- **any**
- **number[]**
- **string[]**
- **any[]**
- **void**
- defined class names

## declare variable and set value

>**var** variableName: dataType = expression;

## set value into variable

>variableName = expression;

>or 

>variableName += expression;    (string concatenation)

## exression
>items:

- variableName
- constans
- objectName.property
- objectName.method(list of values)
- functionName(list of value)
- '+'	If there is space on the two sides of space string concatenation othervise numerical addition
- '-'	
- '/'	
- '*'	
- '**'	
- '&'	
- '&&'
- '|'	
- '||'
- '!'
- '++'
- '--'	

>**The methods of the javascript standard string object can not be used!**

>**The methods of the javascript standard array object can not be used!**

>see below Xstr and Xarray prdefined classess!


## functions
### declare function

>**function** functionName(list of params): dataType { code }

### use function in exression

>functionName(list of values)

### list of params
>name: dataType, ...

>or

>name?: dataType, ....

### list of value

>constans or expression or variableName, ....

## branching

>**if** (logical expression) { code }

>or

>**if** (logical expression) { code } **else** { code }

## lopps

- **for** (name = expression; logical_expression;  code) { code}
- **for** (**let** itemName **in** arrayName) { code }
- **while** (logical_expression) { code }

## eror handing

>**try** { code } **catch(e)** { code }

>generate error: **throw new Error**('error msg');

## Mathematical functions; Predefined Math object instance

### methods

- **abx**(x)
- **acos**(x)
- **atan**(x)
- **asin**(x)
- **cos**(x)
- **flour**(x) 
- **min**(x1,x2)
- **max**(x1,x2)
- **pow**(x) 
- **round**(x)
- **random**() 
- **sin**(x)
- **sqrt**(x)
- **tan**(x)

## Date time handing; Predefined Date class
### constructor

>name = **new Date**(str or number);

### public methods

- **getDate**() 	Returns the day of the month (from 1-31)
- **getDay**() 	Returns the day of the week (from 0-6)
- **getFullYear**() 	Returns the year
- **getHours**() 	Returns the hour (from 0-23)
- **getMinutes**() 	Returns the minutes (from 0-59)
- **getMonth**() 	Returns the month (from 0-11)
- **getSeconds**() 	Returns the seconds (from 0-59)
- **getTime**() 	Returns the number of milliseconds since midnight Jan 1 1970, and a specified date
- **now**() 	Returns the number of milliseconds since midnight Jan 1, 1970
- **parse**() 	Parses a date string and returns the number of milliseconds since January 1, 1970
- **setDate**() 	Sets the day of the month of a date object
- **setFullYear**() 	Sets the year of a date object
- **setHours**() 	Sets the hour of a date object
- **setMinutes**() 	Set the minutes of a date object
- **setMonth**() 	Sets the month of a date object
- **setSeconds**() 	Sets the seconds of a date object
- **setTime**() 	Sets a date to a specified number of milliseconds after/before January 1, 1970
- **setYear**() 	Deprecated. Use the setFullYear() method instead
- **toLocaleDateString**() 	Returns the date portion of a Date object as a string, using locale conventions
- **toLocaleTimeString**() 	Returns the time portion of a Date object as a string, using locale conventions
- **toLocaleString**() 	Converts a Date object to a string, using locale conventions

## predefined global functions

- **decodeURI**(s: string): string 	Decodes a URI
- **encodeURI**(s: string): string 	Encodes a URI
- **isFinite**(s: any): boolean 	Determines whether a value is a finite, legal number
- **isNaN**(s: any): boolean 	Determines whether a value is an illegal number
- **Number**(s: any): number 	Converts an object's value to a number
- **parseFloat**(s: string): number 	Parses a string and returns a floating point number
- **parseInt**(s: string): number 	Parses a string and returns an integer
- **String**(x: any): string 	Converts an object's value to a string

## predefined global vars for php compatibility
- **\_GET**: any[]
- **\_POST**: any[]
- **\_SERVER**: any[]
- **\_CHOCKIE**: any[]
- **\_SESSION**: any[]

## predefined global functions for php compatibility
- **echo**(str: string): void
- **exit**(x: number): void
- **isset**(x:any): boolean
- **session_start**(): void
- **session_id**(x?: number): number
- **base64_encode**(s: string): string
- **base64_decode**(s: string): string

## classes, objects
### declare class
```
	class name extends parentClass {
	  constructor(list of params) { code }
	  body of class
	}
```
>or
```
	class name  {
	 constructor(list of params) { code }
	  body of class
	}
```
### body of class

- **public|protected|private** name: dataType;
- **public|protected|private** name(list of params): dataType { code };

### create an object instance
>name = **new** className(list of params);

>or

>name = { json object string not include function }

### use object public property in expression

>objectName.propertyName

### use self property

>**this**.propertyName

### use public metods in expression

>objektName.methodName(value list)

### use self methods

>**this**.methodName(value list)



## String handing;  predefined Xstr objects for php compability
### constructor
>**var** variableName = **new Xstrs**(strExpression);

>or

>**var** variableName = **XSTR**(strExpression);

### public property
- **value**: string

### public methods

- **get**(): string 
- **set**(value: string): void 
- **length**(): number
- **indexOf**(pattern: string, start?: number): number  (if not found return: -1)
- **lastIndexOf**(pattern: string): number   (if not found return: -1
- **search**(pattern: string): number   (if not found return: -1
- **match**(pattern: string): string[]
- **replace**(pattern: string ,newValue: string): Xstr 
- **substr**(start: number, length: number): Xstr 
- **trim**(): Xstr 
- **toLowerCase**(): Xstr 
- **toUpperCase**(): Xstr
- **split**(terminator: string): Xarray 
- **charAt**(i: number): string 
- **charCodeAt**(i: number): number 

## Array handing; Predefined Xarray object for php compatibility
### constructor
>**var** variableName = **new Xarray**(array);

>or

>**var** variableName = **XARRAY**(array);

### public property
- **values**: any[]

### public methods

- **toString**(): Xstr 
- **loadFromJson**(s: string): void 
- **clear**(): void
- **get**(i: number): any 
- **set**(i: number, value: any): void 
- **count**(): number 
- **length**(): number 
- **slice**(startIndex: number, terminalIndex: number): Xarray 
- **concat**(value: any): void 
- **indexOf**(value: any): number 
- **stringify**(): string 
- **sort**(): void 
- **push**(value: any): void 
- **pop**(): any 
- **splice**(start: number, count: number, value?: any) 
- **first**(): any 
- **next**(): any 
- **previos**(): any 
- **last**(): any 
- **eof**(): boolean 
- **remove**(): void 
- **insert**(value:any): void 
- **goto**(i: number): any 

## Database handing; Predefindex Xdb class for php compatibility

### construction

>name = **new Xdb**();

### public methods

- **setQuery**(str: string)
- **getQuery**(): string
- **setParam**(paramName: string, value: string): void
- **getErrorMsg**(): string
- **getErrorNum**(): number
- **query**(): boolean
- **loadObject**(): record_object
- **loadObjectList**(): any[]
- **quote**(str): string

## database table handing predefined Xtable class

### construction

>name = **new Xtable**(db: Xdb, tableName: string, keyName?: string);

### public methods

- **insert**(record: object): boolean
- **update**(record: object): boolean
- **save**(record: object): boolean
- **remove**(key: string): boolean
- **getErrorNum**(): number
- **getErrorMsg**(): string

## File handing; Predefined Xfile class for php compatibility
### construction

>name = **new Xfile**();

### public methods

- **readdir**(path: string): any[]
- **load**(fileName: string): string
- **save**(fileName: string, data_ string): boolean
- **rm**(fileName: string): boolean
- **rename**(oldFileName: string, newFileName: string): boolean
- **rmdir**(fileName: string): boolean
- **mkdir**(path: string, mod: string): boolean      mod example: '0777'
- **chmod**(fileName: string, mod: string): boolean  mod example: '0777'

## embed HTML code

>**\/\*html**  html code **html\*\/**

## embed PHP code 

>**\/\*php** php code **php\*\/**


