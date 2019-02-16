<?php
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

require_once("getid3/getid3.php");

$getID3 = new getID3;

$xml = new XMLWriter;
$xml->openMemory();
$xml->startDocument('1.0','iso-8859-1');

$xml->startElement("musicas");

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

$xml->startElement("musica");

// Hash musica
$hash_musica_atual = md5_file($local.'/'.$musica);

// Verifica se a musica esta no cache
$lines = file('/home/streaming/'.$_GET["porta"].'/.cache_musicas');
foreach($lines as $line)
{
  if(strpos($line, $hash_musica_atual) !== false)
    list($hash_musica_cache, $duracao_segundos) = explode("|",$line);
}

if(empty($duracao_segundos)) {

$musica_info = $getID3->analyze($local.'/'.$musica);
$duracao_segundos = round($musica_info['playtime_seconds']);

// Inclui a musica no cache
if (file_exists('/home/streaming/'.$_GET["porta"].'/.cache_musicas')) {
$cache_file = fopen('/home/streaming/'.$_GET["porta"].'/.cache_musicas', "a");
} else {
$cache_file = fopen('/home/streaming/'.$_GET["porta"].'/.cache_musicas', "w");
}
fwrite($cache_file, "\n". "".$hash_musica_atual."|".$duracao_segundos."");
fclose($cache_file);
}

$xml->writeElement("nome", utf8_encode($musica));
$xml->writeElement("duracao", gmdate("H:i:s", $duracao_segundos));
$xml->writeElement("duracao_segundos", $duracao_segundos);

$xml->endElement();
unset($musica_info);
unset($duracao_segundos);

}

$xml->endElement();

header('Content-type: text/xml');

print $xml->outputMemory(true);
?>