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
	function phpx_head($title, $author, $description, $css_array, $js_array) {
		echo("<meta charset='utf-8'/>\n");
		//Set title
		if(isset($title) && strlen($title)>0)
			echo("\t\t<title>".$title."</title>\n");
		else
			echo("\t\t<title>Title not set</title>\n");
		//Set page author and description
		if(!isset($author)) $author="";
		if(!isset($description)) $description="";
		echo("\t\t<meta name='author' content='".$author."'>\n");
		echo("\t\t<meta name='description' content='".$description."'>\n");
		//Mobile specific metas
		echo("\t\t<meta name='viewport' content='width=device-width, initial-scale=1'>\n");
		//Website icons
		echo("\t\t<link rel='shortcut icon' href='logo.png' type='image/x-icon'>\n");
		echo("\t\t<link rel='icon' href='logo.png' type='image/x-icon'>\n");
		//Load js and css
		phpx_loadCSS($css_array);
		phpx_loadJS($js_array);
	}




	//Print in the source code the PHPX mark!
	echo("<!--\n\tCreated with the PHPX framework\n\thttps://github.com/Malice1313/phpx/\n-->");

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
