<?php
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

require_once("getid3/getid3.php");

$getID3 = new getID3;

$local = '/home/streaming/'.$_GET["porta"].'/'.$_GET["pasta"].'';

$dir = new DirectoryIterator($local);

foreach($dir as $file) {
	if($file->isFile()) {
		if(pathinfo($file->getFilename(), PATHINFO_EXTENSION) == "mp3" || pathinfo($file->getFilename(), PATHINFO_EXTENSION) == "MP3") {
			$array_musicas[] = $file->getFilename();
		}
	}

}

if($_GET["ordenar"] == "sim") {
asort($array_musicas);
}

foreach($array_musicas as $musica) {

$musica_info = $getID3->analyze($local.'/'.$musica);
$duracao_segundos = round($musica_info['playtime_seconds']);

echo "".utf8_encode($musica)."|".gmdate("H:i:s", $duracao_segundos)."|".$duracao_segundo.";";


}
?>