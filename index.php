<?php
	include("phpx.php");
?>

<HTML>
	<HEAD>
		<?php
			$css=array(
				"style.css"
			);
			phpx_head("Home", "Malice13", "Testpage for phpx framework", $css, NULL);
		?>
	</HEAD>
	<BODY>
		<center><h1 class='phpx_blue'><?php phpx_print(0); ?></h1></center>
		<p>
			<?php
				echo("Current LANG: [".$_SESSION[$_SESSION['phpx']['project'].'current_lang']."]</br>");
				echo("Test: [".htmlspecialchars(phpx_formatToHTML("HELLO\nWorld"))."]</br>");
				echo("Connected to DB[".phpx_dbid($_SESSION[$_SESSION['phpx']['project'].'database'][0]['name'])."]</br>");
				echo("PHPX DB Host: [".$_SESSION[$_SESSION['phpx']['project'].'database'][0]['host']."]</br>");
				echo("PHPX DB Name: [".$_SESSION[$_SESSION['phpx']['project'].'database'][0]['name']."]</br>");
				echo("PHPX DB username: [".$_SESSION[$_SESSION['phpx']['project'].'database'][0]['username']."]</br>");
				echo("PHPX DB password: [".$_SESSION[$_SESSION['phpx']['project'].'database'][0]['password']."]</br>");
				phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "UPDATE phpx SET maintenance=0 WHERE id=1;");
				$data=phpx_dbGet("phpx_".$_SESSION['phpx']['project'], "phpx");
				foreach($data as $v)
					echo("Maintenance mode: [".$v['maintenance']."]\n");
			?>
		</p>
	</BODY>
</HTML>