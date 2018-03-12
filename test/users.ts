/**
* users table fizikai adattárolási réteg
* mysql használatával
*/
import {Xdb, Xtable} from "../ts2php_core/tsphp";

class User {
	public id: number;
	public userName: string; // nick-név
	public name: string; // valódi név
	public email: string; // email cím
	public avatar: string; // kép URL
	public groups: string[]; //  array of string VIRTUÁLIS adat !!!!
	public params: string; // json object  ebben lehet az ADA.id, ADA assurances,  admin=true
	public block: number; // 0:nem blokkolt, 1:blokkolt
	public key: string; // egyszer használatos kulcs a loginlink használja (normál esetben üres)
	public error_count: number; // integer hibás bejeltkezési számláló, sikeres belépéskor nullázódik
	public lastlogin: string; // date-time utolsó bejelentkezés időpontja
	public password: string; // jelszó hash
}

class Users {
	public total: number;
	public items: User[];
}

class TableUsers {
	private errorMsg: string = '';
	/**
	* egy rekord elérése id alapján
	* @params integer
	* @return mixed (users record vagy false)
	*/
	public getById(id: number): User {
		var db = new Xdb();
		db.setQuery(/*'select * 
		from #__users 
		where id = $id '*/);
		db.setParam('id', db.quote(id));
		var result = db.loadObject();
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	/**
	* egy rekord elérése userName alapján
	* @params string
	* @return mixed (users record vagy false)
	*/
	public getByUserName(userName: string): User {
		this.errorMsg = '';
		var db = new Xdb();
		db.setQuery('select * from `#__users`	where `username`=' + db.quote(userName));
		var result = db.loadObject();
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	/**
	* egy rekord elérése email alapján
	* @params string
	* @return mixed (users record vagy false)
	*/
	public getByEmail(email: string): User {
		this.errorMsg = '';
		var db = new Xdb();
		db.setQuery(/*'select * 
		from #__users 
		where email= $email '*/);
		db.setParam('email', db.quote(email));
		var result = db.loadObject();
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	/**
	* egy rekord elérése key alapján
	* @params string
	* @return mixed (users record vagy false)
	*/
	public getByKey(key: string): User {
		this.errorMsg = '';
		var db = new Xdb();
		db.setQuery(/*'select * 
		from #__users 
		where `key`= $key '*/);
		db.setParam('key', db.quote(key));
		var result = db.loadObject();
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	/**
	* rekord sorozat elérése
	* @params string (sql where) 
	*    username='..' or name like '%..%' or email='...'
	* @params string (sql order by)
	* @params integer
	* @params integer
	* @return {'total':#, 'items:[], 'errorMsg':'')
	*     items array of {id, username, name, email, block,...params:{...}}
	*/
	public getItems(filter: string,
					order : string,
					limitStart: number,
					limit: number): Users {
		this.errorMsg = '';
		var db = new Xdb();
		var result = new Users;
		result.total = 0;
		result.items = [];
		result.errorMsg = '';
		var res1 = [];
		var res2 = {'cc':0};
		db.setQuery(/*'select *
		from #__users
		where $filter
		order by $order
		limit $limitStart,$limit '*/);
		db.setParam('filter',filter);
		db.setParam('order',order);
		db.setParam('limitStart',' ' + limitStart);
		db.setParam('limit',' ' + limit);
		res1 = db.loadObjectList();
		var i: number = 0;
		if (Xarray(res1).count() > 0) {
			db.setQuery(/*'select count(id) cc
			from #__users
			where  $filter '*/);
			res2 = db.loadObject(); 
			if (db.getErrorMsg() == '') {
				result.errorMsg = '';
				result.total = res2.cc;
				result.items = res1;
			}
		} else {
			result.total = 0;
		}
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	/**
	* get admins
	* @return array of User record (params string formában)
	*/
	public getAdmins(): User[] {
		this.errorMsg = '';
		var db = new Xdb();
		var result = []
		db.setQuery(/*'select *
		from #__users
		where params="{\"admin\":1}" or params="{\"admin\":\"1\"}" '*/);
		result = db.loadObjectList();
		this.errorMsg = db.getErrorMsg();
		return result;
	}
	

	/**
	* rekord tárolás (ha $record->id > 0 update, ha $record->id == 0 akkor  insert)
	* @params users record
	* @return bool
	*/
	public save(record: User): boolean {
		this.errorMsg = '';
		var db = new Xdb();
		var table = new Xtable(db, '#__users','id');
		var result = table.save(record);
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	/**
	* egy rekor törlése
	* @params users record
	* @return bool
	*/
	public remove(record: User): boolean {
		this.errorMsg = '';
		var db = new Xdb();
		db.setQuery('delete from #__users where id=' + db.quote(record.id));
		var result = db.query();
		this.errorMsg = db.getErrorMsg();
		return result;
	}

	public getErrorMsg(): string {
		return this.errorMsg;
	}
} 

