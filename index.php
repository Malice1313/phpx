<?php
	include("phpx.php");
	phpx_start("index.php");
?>

<HTML>
	<HEAD>
		<?php
			phpx_head("Home", "Malice13", "Testpage for phpx framework");
			//Load js and css
			phpx_loadCSS(array(
				"style.css"
			));
			//phpx_loadJS($js_array);
		?>
	</HEAD>
	<BODY>
		<center><h1><?php phpx_print(0); ?></h1></center>
	</BODY>
</HTML>