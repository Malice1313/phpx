<?php
	/**
		DATABASE COMMUNICATION
	**/
	function phpx_dbNew($host, $name, $username, $password) {
		if(isset($name) && isset($host) && isset($username)) {
			$last=count($_SESSION[$_SESSION['phpx']['project'].'database']);
			$p=$password;
			if(!isset($password)) $p="";
			$_SESSION[$_SESSION['phpx']['project'].'database'][$last]=array(
				"host" => $host,
				"username" => $username,
				"name" => $name,
				"password" => $p
			);
			return true;
		}
		return false;
	}

	///Connects to database
	function phpx_dbConnect($name)
	{
		$nb_db=count($_SESSION[$_SESSION['phpx']['project'].'database']);
		if(isset($name) && $nb_db>0) {
			$ct=0;
			$id=-1;
			foreach($_SESSION[$_SESSION['phpx']['project'].'database'] as $v) {
				if($v['name']==$name)
					$id=$ct;
				$ct++;
			}
			if($id>=0) {
				$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['connected']=false;
				$db=NULL;
				try {
					if(strlen($_SESSION[$_SESSION['phpx']['project'].'database'][$id]['password'])>0)
						$db=new PDO("mysql:host=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['host'].";dbname=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['name'], $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['username'], $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['password']);
					else
						$db=new PDO("mysql:host=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['host'].";dbname=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['name'], $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['username'], "");
					if($db!=NULL)
						$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['connected']=true;
				}
				catch(Exception $e) {
					return NULL;
				}
				return $db;
			}
		}
		return NULL;
	}

	//Checks if connected to database[name], returns its ID, else return -1
	function phpx_dbID($name) {
		$id=-1;
		$nb_db=count($_SESSION[$_SESSION['phpx']['project'].'database']);
		if(isset($name) && $nb_db>0) {
			$ct=0;
			foreach($_SESSION[$_SESSION['phpx']['project'].'database'] as $v) {
				if($v['name']==$name) {
					$id=$ct;
					break;
				}
				$ct++;
			};
		}
		return $id;
	}


	//Get a certain value from database
	function phpx_dbGet($name, $request) {
		$ret=array();
		$db=phpx_dbConnect($name);
		$id=phpx_dbID($name);
		if(isset($request) && $id>=0) {
			$i=0;
			$db->query("SET NAMES utf8");
			$req=$db->query($request);
			if(isset($req) && $req!=false) {
				$data=array();
				while($data=$req->fetch()) {
					if(isset($data)) {
						$ret[$i]=$data;
						$i++;
					}
				}
				$req->closeCursor();
			}
		}
		return $ret;
	}

	//Request the database
	function phpx_dbRequest($name, $request) {
		$db=phpx_dbConnect($name);
		$id=phpx_dbID($name);
		if(isset($request) && $id>=0) {
			$db->query("SET NAMES utf8");
			$db->query($request);
		}
	}

?>
