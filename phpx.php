<?php
	/**
		MAIN PHPX FILE
	**/
	session_start();

	//Load external files
	$_SESSION['phpx']=include("defines.php");
	include("database.php");
	include("etc.php");

	//Prints head content
	function phpx_head($title) {
		echo("<meta charset='utf-8'/>");
		if(isset($title) && strlen($title)>0) echo("<title>".$title."</title>");
		else echo("<title>Title not set</title>");
	}


	//Init database array
	$_SESSION[$_SESSION['phpx']['project'].'database']=array();

	//Set current language of not already set, or if not in lang list
	if(!isset($_SESSION[$_SESSION['phpx']['project'].'current_lang']) || phpx_inList($_SESSION['phpx']['lang_list'], $_SESSION[$_SESSION['phpx']['project'].'current_lang'])<0)
		$_SESSION[$_SESSION['phpx']['project'].'current_lang']=$_SESSION['phpx']['lang_list'][0];
	//Get locale file nb lines
	$_SESSION[$_SESSION['phpx']['project'].'locale_lines']=count($_SESSION['phpx']['locale']);

	//Load PHPX specific database
	phpx_dbNew($_SESSION['phpx']['database']['host'], "phpx_".$_SESSION['phpx']['project'], $_SESSION['phpx']['database']['username'], $_SESSION['phpx']['database']['password']);
	phpx_dbConnect("phpx_".$_SESSION['phpx']['project']);

?>
