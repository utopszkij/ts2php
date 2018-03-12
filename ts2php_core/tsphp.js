/**
* routines, objects for php compatibility
*
* exports: Xarray, XARRAY, Xstr, XSTR, Xdate, XDATE,
* Xdb, Xtable, Xfile,
* _GET, _POST, _SERVER, _CHOCKIE, _SESSION
* echo, exit, isset, file_get_contents, session_start, session_id,
* base64_decode, base64_encode
*/
exports._GET = [];
exports._POST = [];
exports._SERVER = [];
exports._CHOCKIE = [];
exports._SESSION = [];
/**
* extended array
*/
var Xarray = (function () {
    function Xarray(a) {
        this.values = [];
        this.i = 0;
        this.values = a;
    }
    // convert items to coma separeted list
    Xarray.prototype.toString = function () {
        return XSTR(this.values.toString());
    };
    // load items from json string
    Xarray.prototype.loadFromJson = function (s) {
        this.values = JSON.parse(s);
        this.i = 0;
        return;
    };
    // clear all items
    Xarray.prototype.clear = function () {
        this.values = [];
        this.i = 0;
        return;
    };
    // item getter
    Xarray.prototype.get = function (i) {
        return this.values[i];
    };
    // item set
    Xarray.prototype.set = function (i, value) {
        this.values[i] = value;
        return;
    };
    // count os items
    Xarray.prototype.count = function () {
        return this.values.length;
    };
    // count of items
    Xarray.prototype.length = function () {
        return this.count();
    };
    // result a subsequence from items (terminalIndex not include)
    Xarray.prototype.slice = function (startIndex, terminalIndex) {
        return new Xarray(this.values.slice(startIndex, terminalIndex));
    };
    // add value or array to end of items 
    Xarray.prototype.concat = function (value) {
        this.values.concat(value);
        return;
    };
    // find value in items. return index or -1
    Xarray.prototype.indexOf = function (value) {
        return this.values.indexOf(value);
    };
    // return items in json string format
    Xarray.prototype.stringify = function () {
        return JSON.stringify(this.values);
    };
    // sort items low --> hight
    Xarray.prototype.sort = function () {
        this.values.sort();
        return;
    };
    // add valu to end of items
    Xarray.prototype.push = function (value) {
        this.values.push(value);
        return;
    };
    // return last item and remove it from items
    Xarray.prototype.pop = function () {
        return this.values.pop();
    };
    // delete item(s) from items, and insert value or array
    Xarray.prototype.splice = function (start, count, value) {
        this.values.splice(start, count, value);
        return;
    };
    // ---- use list style -----
    // return first item
    Xarray.prototype.first = function () {
        var result;
        this.i = 0;
        if ((this.i >= 0) && (this.i < this.values.length)) {
            result = this.values[this.i];
        }
        else {
            this.i = 0;
            result = undefined;
        }
        return result;
    };
    // return next item
    Xarray.prototype.next = function () {
        var result;
        this.i++;
        if ((this.i >= 0) && (this.i < this.values.length)) {
            result = this.values[this.i];
        }
        else {
            this.i = 0;
            result = undefined;
        }
        return result;
    };
    // return previos item
    Xarray.prototype.previos = function () {
        var result;
        this.i--;
        if (this.i >= 0) {
            result = this.values[this.i];
        }
        else {
            this.i = 0;
            return undefined;
        }
        return result;
    };
    // return last item
    Xarray.prototype.last = function () {
        var result;
        this.i = this.values.length - 1;
        if (this.i >= 0) {
            result = this.values[this.i];
        }
        else {
            this.i = 0;
            return undefined;
        }
        return result;
    };
    // check eof
    Xarray.prototype.eof = function () {
        return (this.i < this.values.length);
    };
    // remove actual item, 
    Xarray.prototype.remove = function () {
        this.values.splice(this.i, 1);
        return;
    };
    // insert new item inti actual position
    Xarray.prototype.insert = function (value) {
        this.values.splice(this.i, 0, value);
        return;
    };
    // goto position
    Xarray.prototype.goto = function (i) {
        var result;
        this.i = i;
        if ((this.i >= 0) && (this.i < this.values.length)) {
            result = this.values[this.i];
        }
        else {
            this.i = 0;
            result = undefined;
        }
        return result;
    };
    return Xarray;
})();
exports.Xarray = Xarray;
// convert new Xarray to function
function XARRAY(a) {
    if (a == undefined) {
        a = [];
    }
    return new Xarray(a);
}
exports.XARRAY = XARRAY;
/**
* extended string object
*/
var Xstr = (function () {
    function Xstr(str) {
        this.$str = '';
        this.value = str;
    }
    // item getter
    Xstr.prototype.get = function () {
        return this.value;
    };
    // item set
    Xstr.prototype.set = function (value) {
        this.value = value;
        return;
    };
    // return string length
    Xstr.prototype.length = function () {
        return this.value.length;
    };
    // searc substr return position or -1
    Xstr.prototype.indexOf = function (pattern, start) {
        return this.value.indexOf(pattern);
    };
    // searc substr return position or -1
    Xstr.prototype.lastIndexOf = function (pattern) {
        return this.value.lastIndexOf(pattern);
    };
    // search by regexp return position or -1
    Xstr.prototype.search = function (pattern) {
        return this.search(pattern);
    };
    Xstr.prototype.match = function (pattern) {
        return this.match(pattern);
    };
    Xstr.prototype.replace = function (pattern, newValue) {
        return new Xstr(this.value.replace(pattern, newValue));
    };
    Xstr.prototype.substr = function (start, length) {
        return new Xstr(this.value.substr(start, length));
    };
    Xstr.prototype.trim = function () {
        return new Xstr(this.value.trim());
    };
    Xstr.prototype.toLowerCase = function () {
        return new Xstr(this.value.toLowerCase());
    };
    Xstr.prototype.toUpperCase = function () {
        return new Xstr(this.value.toUpperCase());
    };
    Xstr.prototype.split = function (terminator) {
        return new Xarray(this.value.split(terminator));
    };
    Xstr.prototype.charAt = function (i) {
        return this.value.charAt(i);
    };
    Xstr.prototype.charCodeAt = function (i) {
        return this.value.charCodeAt(i);
    };
    return Xstr;
})();
exports.Xstr = Xstr;
// convert new Xstr to function
function XSTR(s) {
    if (s == undefined) {
        s = '';
    }
    return new Xstr(s);
}
exports.XSTR = XSTR;
/**
* sql database object (implemented only in php)
*/
var Xdb = (function () {
    function Xdb() {
        this.sql = '';
    }
    // set query string '$paramName' allowed in sql string
    Xdb.prototype.setQuery = function (sql) {
        this.sql = sql;
        return;
    };
    // define param
    Xdb.prototype.setParam = function (paramName, value) {
        return;
    };
    // get query string
    Xdb.prototype.getQuery = function () {
        return this.sql;
    };
    // exec sql
    Xdb.prototype.query = function () {
        return true;
    };
    // load one record  setted sql: "select ...... limit 1"
    Xdb.prototype.loadObject = function () {
        return {};
    };
    // load record set  setted sql: "select ........."
    Xdb.prototype.loadObjectList = function () {
        return [];
    };
    // get last sql process error message (OK = '')
    Xdb.prototype.getErrorMsg = function () {
        return '';
    };
    // get last sql process error number (OK = 0)
    Xdb.prototype.getErrorNum = function () {
        return 0;
    };
    // adjust value for sql value format
    Xdb.prototype.quote = function (s) {
        return '"' + s + '"';
    };
    // load objectList by select object
    Xdb.prototype.loadSelect = function (select, limitStart, limit) {
        return [];
    };
    return Xdb;
})();
exports.Xdb = Xdb;
/**
* sql table handing  (impelemented only php)
*/
var Xtable = (function () {
    function Xtable(db, tableName, keyName) {
    }
    // insert nyew record param: record object
    Xtable.prototype.insert = function (record) { return true; };
    // update one record param: record object
    Xtable.prototype.update = function (ecord) { return true; };
    // insert or update one record  paraM. record object
    Xtable.prototype.save = function (record) { return true; };
    // remove one record  param: key balue
    Xtable.prototype.remove = function (id) { return true; };
    // get last error Number
    Xtable.prototype.getErrorNum = function () { return 0; };
    // get last error msg
    Xtable.prototype.getErrorMsg = function () { return ''; };
    return Xtable;
})();
exports.Xtable = Xtable;
/**
* file processing  implemented only in php
*/
var Xfile = (function () {
    function Xfile() {
    }
    // read filenames from one filder
    Xfile.prototype.readdir = function (path) {
        return [];
    };
    // load string from one file
    Xfile.prototype.load = function (fileName) {
        return '';
    };
    // save string to file
    Xfile.prototype.save = function (fileName, data) {
        return true;
    };
    // remove one file
    Xfile.prototype.rm = function (fileName) {
        return true;
    };
    // rename one file
    Xfile.prototype.rename = function (oldFileName, newFileName) {
        return true;
    };
    // remove one empty folder
    Xfile.prototype.rmdir = function (fileName) {
        return true;
    };
    // create new folder
    Xfile.prototype.mkdir = function (path, mod) {
        return true;
    };
    // change file permission
    Xfile.prototype.chmod = function (fileName, mod) {
        return true;
    };
    // change file owner
    Xfile.prototype.chown = function (fileName, own) {
        return true;
    };
    // check file exists?
    Xfile.prototype.file_exists = function (fileName) {
        return true;
    };
    // check folder exists?
    Xfile.prototype.dir_exists = function (dirName) {
        return true;
    };
    return Xfile;
})();
exports.Xfile = Xfile;
var Xselect = (function () {
    function Xselect() {
    }
    // set select column list exampla:  'a.title, b.*'
    Xselect.prototype.setFieldList = function (s) { return; };
    // set from table Name
    Xselect.prototype.setFrom = function (alias, tableName) { return; };
    // set subselect
    Xselect.prototype.setSubselect = function (alias, select) { return; };
    // add union
    Xselect.prototype.addJoin = function (joinType, alias, table, on) { return; };
    // add subselect join
    Xselect.prototype.addSubselectJoin = function (joinType, alias, select, on) { return; };
    // set group by sql string
    Xselect.prototype.setGroups = function (groups) { return; };
    // add Union
    Xselect.prototype.addUnion = function (select) { return; };
    // set where sql string
    Xselect.prototype.setWhere = function (where) { return; };
    // set having sql string
    Xselect.prototype.setHaving = function (having) { return; };
    // set order by string
    Xselect.prototype.setOrder = function (order) { return; };
    // generate sql string
    Xselect.prototype.toString = function () { return ''; };
    return Xselect;
})();
exports.Xselect = Xselect;
// echo to stdoutput
function echo(s) {
    console.log(s);
}
exports.echo = echo;
// exit program 
function exit(i) {
    return;
}
exports.exit = exit;
// variable exists?
function isset(x) {
    var result;
    if (x == undefined) {
        result = false;
    }
    else {
        result = true;
    }
    return result;
}
exports.isset = isset;
function unset(x) {
    x = undefined;
}
exports.unset = unset;
// php file_get_contests implemented only php
function file_get_contents(s) {
    return '';
}
exports.file_get_contents = file_get_contents;
// php session handing implemented only php
function session_start() { }
exports.session_start = session_start;
function session_id(x) { return 1; }
exports.session_id = session_id;
// base64  decode/encode implemented only php
function base64_encode(s) {
    return s;
}
exports.base64_encode = base64_encode;
function base64_decode(s) {
    return s;
}
exports.base64_decode = base64_decode;
function hash(algo, str) {
    return str;
}
exports.hash = hash;
