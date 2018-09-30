<?php
	include("phpx.php");
?>

<HTML>
	<HEAD>
		<?php
			phpx_head("Home");
		?>
	</HEAD>
	<BODY>
		<center><h1><?php phpx_print(0); ?></h1></center>
		<p>
			<?php
				echo("Cuurent LANG: [".$_SESSION[$_SESSION['phpx']['project'].'current_lang']."]</br>");
			?>
		</p>
	</BODY>
</HTML>