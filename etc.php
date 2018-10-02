<?php
	/**
		CONTAINS VARIOUS UNSORTED FUNCTIONS
	**/

	//Checks if value is in the list, returns ID of found value
	function phpx_inList($list, $value) {
		$id=0;
		if(isset($list) && isset($value)) {
			foreach($list as $v) {
				if($v==$value) return $id;
				$id++;
			}
		}
		$id=-1;
		return $id;
	}

	//Prints text in current language
	function phpx_print($line) {
		if($line>=0 && $line<$_SESSION[$_SESSION['phpx']['project'].'locale_lines']) {
			//Print locale[line][lang]
			$lang=phpx_inList($_SESSION['phpx']['lang_list'], $_SESSION[$_SESSION['phpx']['project'].'current_lang']);
			echo($_SESSION['phpx']['locale'][$line][$lang]);
		}
	}

	///Replaces next lines by html </br>
	function phpx_formatToHTML($str)
	{
		return str_replace("\n", "</br>", $str);
	}

	//Redirection
	function phpx_redirect($url) {
		if(isset($url) && strlen($url)>0) {
			header('Location: '.$url);
	   		die();
	   	}
	}

	//Load javascript files from an array of given filepath
	function phpx_loadJS($js_array) {
		if(isset($js_array)) {
			$nb_js=0;
			$nb_js=count($js_array);
			if($nb_js>0) {
				foreach($js_array as $js)
					echo("\t\t<script src='".$js."'></script>\n");
			}
		}
	}

	//Load css files from an array of given filepath
	function phpx_loadCSS($css_array) {
		if(isset($css_array)) {
			$nb_css=0;
			$nb_css=count($css_array);
			if($nb_css>0) {
				foreach($css_array as $css)
					echo("\t\t<link rel='stylesheet' href='".$css."'>\n");
			}
		}
	}

	//Returns client current IP
	function phpx_getIP() {
		if(isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}
		return "Unknown";
	}

	//Downloads a file
	function phpx_download($file)
	{
		if(isset($file))
		{
			$f="file/".$file;
			if(file_exists($f))
			{
				header("Content-Description: File Transfer");
				header("Content-Type: text/plain");
				header("Content-Disposition: attachment; filename=".basename($f));
				header("Expires: 0");
				header("Cache-Control: must-revalidate");
				header("Pragma: public");
				header("Content-Length: ". filesize($f));
				readfile($f);
				exit();
				return true;
			}
		}
		return false;
	}

	//Creates a directory
	function phpx_mkdir($path)
	{
		if(isset($path)&& !file_exists($path))
		{
			mkdir($path);
			return true;
		}
		return false;
	}

	//Creates a file with given content
	function phpx_mkfile($path, $content)
	{
		if(isset($title) && isset($content))
		{
			$f=fopen($path, "w+");
			if($f)
			{
				fwrite($f, $content);
				fclose($f);
				return true;
			}
		}
		return false;
	}

?>
