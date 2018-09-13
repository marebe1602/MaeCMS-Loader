<?php
/* created 2018-09-13 09:46 ME
    Mae CMS Loader
*/
$versionOk          = !version_compare(phpversion(), '7.0.0', '<');
$pdoOk              = defined('PDO::ATTR_DRIVER_NAME');
$archiveUrl         = "https://martin-eberhardt.com/MaeCMS-latest.zip";
$zipFileName        = "MaeCMS-latest.zip";
$rootPath           = dirname(__FILE__);
$alreadyInstalled   = file_exists($rootPath . '/system');
while (@ob_end_flush());
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<title>MaeCMS - Loader</title>
		<meta charset="utf-8">
		<style>
			html, body {width: 100%;height: 100%}
			body {font-family: Courier, monospace;font-size: 17px;color: #ccc;background-color: #000;padding: 0 25px;line-height: 1.2em}
			strong {font-weight: bold}
			p {margin: 0 0 10px 0}
			b {color: orangered;font-weight: normal}
			i {color: forestgreen;font-style: normal}
			a {text-decoration: underline;color: blue}
		</style>
	</head>
	<body>
		<p><strong>MaeCMS Loader 1.0</strong></p>
		<?php if($alreadyInstalled) die('Es befindet sich bereits ein installiertes System auf dem Server.'); ?>
		<p><?php
		if($versionOk) {
			echo 'PHP Version: ' . phpversion() . ' <i>OK</i>';
		}
		else {
			die('<b>PHP 7.x wird benötigt (derzeit: ' . phpversion() . ')</b>');
		}
		?></p>
		<p>PDO Erweiterung für Datenbankzugriff
		<?php if($pdoOk) {echo '<i>OK</i>';} else {die('<b>Nicht installiert</b>');} ?>
		</p>

		<p>ZIP Archiv herunterladen...</p>
		<?php
			$f = file_put_contents($zipFileName, fopen($archiveUrl, 'r'), LOCK_EX);
			if(FALSE === $f)
				die('<b>download fehlgeschlagen, URL: ' . $archiveUrl . '</b>');
			echo '<p>ZIP Archiv entpacken...</p>';
			$zip = new ZipArchive;
			$res = $zip->open($zipFileName);
			if ($res === TRUE) {
				for($i=0; $i < $zip->numFiles; $i++) {
					if($i == 0) {continue;} // unnecessary root folder
					$name   = $zip->getNameIndex( $i );
					$parts  = explode('/', $name);
					if(count($parts) > 1) {
						array_shift($parts);
					}
					$dest   = implode('/', $parts);
					$dir    = dirname($dest);
					$isDir  = substr($dest, -1, 1) == '/';
					if($dir != '.' && !file_exists($dir)) {
						mkdir( $dir, 0777, true );
					}
					if(!$isDir) {
						$fpr = $zip->getStream($name);
						$fpw = fopen($dest, 'w');
						while($data = fread($fpr, 1024)) {
							fwrite($fpw, $data);
						}
						fclose($fpr);
						fclose($fpw);
					}
					echo 'extrahiere: ' . $dest . '<br>';
				}
				$zip->close();
				echo '<br><p>Sie können nun das Installationsprogramm ausführen:<br><a href="install/index.php">Zum Instalationsprogramm</a></p>';
				@unlink($zipFileName);
			} else {
				die('<b>Entpacken der Datei "' . $zipFileName . '" fehlgeschlagen.</b>');
			}
		?>
	</body>
</html>