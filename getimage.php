<?php
	if (isset($_GET['load'])) {
		$attachDir = "images";
		$dir = dir($attachDir);
		while ($file = $dir->read()) {
			if($file == "0-5.gif") {
				$theFile = fopen($attachDir."/".$file,"r");
				while (!feof($theFile))
					echo(fread($theFile,4096));
				fclose($theFile);
			}
		}
	}
	else {
		echo ("immagine!");
		?>
			<img src="getimage.php?load=1">
		<?
	}
?>
