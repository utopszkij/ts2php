<?php
// generated the ts2php 2018.03.11 12:32:45
/**
* users table fizikai adattárolási réteg
* mysql használatával
*/
include_once "../ts2php_core/tsphpx.php";

class User {
	public $id;
	public $userName; // nick-név
	public $name; // valódi név
	public $email; // email cím
	public $avatar; // kép URL
	public $groups; //  array of string VIRTUÁLIS adat !!!!
	public $params; // json object  ebben lehet az ADA.id, ADA assurances,  admin=true
	public $block; // 0:nem blokkolt, 1:blokkolt
	public $key; // egyszer használatos kulcs a loginlink használja (normál esetben üres)
	public $error_count; // integer hibás bejeltkezési számláló, sikeres belépéskor nullázódik
	public $lastlogin; // date-time utolsó bejelentkezés időpontja
	public $password; // jelszó hash
}

class Users {
	public $total;
	public $items;
}

class TableUsers {
	private $errorMsg = '';
	/**
	* egy rekord elérése id alapján
	* @params integer
	* @return mixed (users record vagy false)
	*/
	public function getById($id) {
		$db = new Xdb();
		$db->setQuery('select * 
		from #__users 
		where id = $id ');
		$db->setParam('id', $db->quote($id));
		$result = $db->loadObject();
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}

	/**
	* egy rekord elérése userName alapján
	* @params string
	* @return mixed (users record vagy false)
	*/
	public function getByUserName($userName) {
		$this->errorMsg = '';
		$db = new Xdb();
		$db->setQuery('select * from `#__users`	where `username`=' . $db->quote($userName));
		$result = $db->loadObject();
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}

	/**
	* egy rekord elérése email alapján
	* @params string
	* @return mixed (users record vagy false)
	*/
	public function getByEmail($email) {
		$this->errorMsg = '';
		$db = new Xdb();
		$db->setQuery('select * 
		from #__users 
		where email= $email ');
		$db->setParam('email', $db->quote($email));
		$result = $db->loadObject();
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}

	/**
	* egy rekord elérése key alapján
	* @params string
	* @return mixed (users record vagy false)
	*/
	public function getByKey($key) {
		$this->errorMsg = '';
		$db = new Xdb();
		$db->setQuery('select * 
		from #__users 
		where `key`= $key ');
		$db->setParam('key', $db->quote($key));
		$result = $db->loadObject();
		$this->errorMsg = $db->getErrorMsg();
		return $result;
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
	public function getItems($filter,
					$order ,
					$limitStart,
					$limit) {
		$this->errorMsg = '';
		$db = new Xdb();
		$result = new Users;
		$result->total = 0;
		$result->items = array();
		$result->errorMsg = '';
		$res1 = array();
		$res2 = JSON_decode('{'cc':0}');
		$db->setQuery('select *
		from #__users
		where $filter
		order by $order
		limit $limitStart,$limit ');
		$db->setParam('filter',$filter);
		$db->setParam('order',$order);
		$db->setParam('limitStart',' ' . $limitStart);
		$db->setParam('limit',' ' . $limit);
		$res1 = $db->loadObjectList();
		$i = 0;
		if (Xarray($res1)->count() > 0) {
			$db->setQuery('select count(id) cc
			from #__users
			where  $filter ');
			$res2 = $db->loadObject(); 
			if ($db->getErrorMsg() == '') {
				$result->errorMsg = '';
				$result->total = $res2.$cc;
				$result->items = $res1;
			}
		} else {
			$result->total = 0;
		}
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}

	/**
	* get admins
	* @return array of User record (params string formában)
	*/
	public function getAdmins() {
		$this->errorMsg = '';
		$db = new Xdb();
		$result = array()
		$db->setQuery('select *
		from #__users
		where params="{\"admin\":1}" or params="{\"admin\":\"1\"}" ');
		$result = $db->loadObjectList();
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}
	

	/**
	* rekord tárolás (ha $record->id > 0 update, ha $record->id == 0 akkor  insert)
	* @params users record
	* @return bool
	*/
	public function save($record) {
		$this->errorMsg = '';
		$db = new Xdb();
		$table = new Xtable($db, '#__users','id');
		$result = $table->save($record);
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}

	/**
	* egy rekor törlése
	* @params users record
	* @return bool
	*/
	public function remove($record) {
		$this->errorMsg = '';
		$db = new Xdb();
		$db->setQuery('delete from #__users where id=' . $db->quote($record->id));
		$result = $db->query();
		$this->errorMsg = $db->getErrorMsg();
		return $result;
	}

	public function getErrorMsg() {
		return $this->errorMsg;
	}
} 

?>
