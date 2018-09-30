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
				$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['db']=NULL;
				try {
					if(strlen($_SESSION[$_SESSION['phpx']['project'].'database'][$id]['password'])>0)
						$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['db']=new PDO("mysql:host=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['host'].";dbname=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['name'], $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['username'], $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['password']);
					else
						$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['db']=new PDO("mysql:host=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['host'].";dbname=".$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['name'], $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['username'], "");
					if($_SESSION[$_SESSION['phpx']['project'].'database'][$id]['db']!=NULL)
						$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['connected']=true;
				}
				catch(Exception $e) {
					return false;
				}
				return true;
			}
		}
		return false;
	}

	//Checks if connected to database[name], returns its ID, else return -1
	function phpx_dbConnected($name) {
		$nb_db=count($_SESSION[$_SESSION['phpx']['project'].'database']);
		if(isset($name) && $nb_db>0) {
			$ct=0;
			$id=-1;
			foreach($_SESSION[$_SESSION['phpx']['project'].'database'] as $v) {
				if($v['name']==$name)
					$id=$ct;
				$ct++;
			}
			if(isset($_SESSION[$_SESSION['phpx']['project'].'database'][$id]['connected']) && $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['connected']>=0 && $_SESSION[$_SESSION['phpx']['project'].'database'][$id]['connected']<$nb_db)
				return $id;
		}
		return -1;
	}


	//Get a certain value from database
	function phpx_dbGet($name, $table, $field) {
		$ret=NULL;
		if(isset($table) && isset($field) && phpx_dbConnected($name)) {
			$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['db']->query("SET NAMES utf8");
			$req=$_SESSION[$_SESSION['phpx']['project'].'database'][$id]['db']->query("SELECT * from ".$table);			
			if(isset($req)) {
				while($data=$req->fetch()) {
					if(isset($data))
						$ret=$data[$field];
					break;
				}
				$req->closeCursor();
			}
		}
		return $ret;
	}
?>
