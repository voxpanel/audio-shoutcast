<?php
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);


$xml = new XMLWriter;
$xml->openMemory();
$xml->startDocument('1.0','iso-8859-1');

$xml->startElement("pastas");

$local = '/home/streaming/'.$_GET["porta"].'';

$total_musicas_pasta_raiz = count(glob($local."/*.mp3"));
$total_musicas_pasta_raiz = $total_musicas_pasta_raiz+count(glob($local."/*.MP3"));

$xml->startElement("pasta");

$xml->writeElement("nome", "/");
$xml->writeElement("total", $total_musicas_pasta_raiz);

$xml->endElement();

$dir = new DirectoryIterator($local);

foreach($dir as $file) {
if(!$file->isDot() && $file->isDir()) {

$pasta = $file->getFilename();

$total_musicas_pasta1 = count(glob($local.'/'.$pasta."/*.mp3"));
$total_musicas_pasta2 = count(glob($local.'/'.$pasta."/*.MP3"));

$xml->startElement("pasta");

$xml->writeElement("nome", utf8_encode($pasta));
$xml->writeElement("total", $total_musicas_pasta1+$total_musicas_pasta2);

$xml->endElement();

}
}

$xml->endElement();

header('Content-type: text/xml');

print $xml->outputMemory(true);
?>
