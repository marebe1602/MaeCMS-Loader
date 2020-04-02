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
			html, body {width: 100%;height: 100%;margin: 0}
			body {font-family: Courier, monospace;font-size: 17px;color: #ccc;background-color: #000;padding: 0 25px;line-height: 1.2em}
			strong {font-weight: bold}
			p {margin: 0 0 10px 0}
			b {color: orangered;font-weight: normal}
			i {color: forestgreen;font-style: normal}
			a {text-decoration: underline;color: #fff;}
		</style>
	</head>
	<body>
		<p><br><strong>MaeCMS Loader 1.1</strong></p>
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

        <?php if(!isset($_GET['action']) || $_GET['action'] != 'install') { ?>
        <p><br><a href="?action=install">MaeCMS installieren</a></p>
        <?php } else { ?>

            <p>ZIP Archiv herunterladen...</p>
	        <?php
	        $f = file_put_contents( $zipFileName, fopen( $archiveUrl, 'r' ), LOCK_EX );
	        if ( false === $f ) {
		        die( '<b>download fehlgeschlagen, URL: ' . $archiveUrl . '</b>' );
	        }
	        echo '<p>ZIP Archiv entpacken...</p>';
	        $fileCnt = 0;
	        $zip     = new ZipArchive;
	        $res     = $zip->open( $zipFileName );
	        if ( $res === true ) {
		        for ( $i = 0; $i < $zip->numFiles; $i ++ ) {
			        if ( $i == 0 ) {
				        continue;
			        } // unnecessary root folder
			        $name  = $zip->getNameIndex( $i );
			        $parts = explode( '/', $name );
			        if ( count( $parts ) > 1 ) {
				        array_shift( $parts );
			        }
			        $dest  = implode( '/', $parts );
			        $dir   = dirname( $dest );
			        $isDir = substr( $dest, - 1, 1 ) == '/';
			        if ( $dir != '.' && ! file_exists( $dir ) ) {
				        mkdir( $dir, 0777, true );
			        }
			        if ( ! $isDir ) {
				        $fpr = $zip->getStream( $name );
				        $fpw = fopen( $dest, 'w' );
				        while ( $data = fread( $fpr, 1024 ) ) {
					        fwrite( $fpw, $data );
				        }
				        fclose( $fpr );
				        fclose( $fpw );
				        $fileCnt ++;
			        }
			        echo 'extrahiere: ' . $dest . '<br>';
		        }
		        $zip->close();
		        echo $fileCnt . ' Dateien extrahiert.<br>';
		        echo '<br><p>Sie können nun das Installationsprogramm ausführen:<br><a href="install/index.php">Zum Instalationsprogramm</a><br><br></p>';
		        @unlink( $zipFileName );
	        } else {
		        die( '<b>Entpacken der Datei "' . $zipFileName . '" fehlgeschlagen.</b>' );
	        }
        } // if action == install
		?>
    <script>window.scrollTo(0,document.body.scrollHeight);</script>
	</body>
</html>