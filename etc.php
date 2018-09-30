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
?>
