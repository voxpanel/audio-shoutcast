<?php
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

$ftpquota = @file_get_contents("/home/streaming/".$_GET["porta"]."/.ftpquota");

if(!$ftpquota) {
$ftpquota = @readfile("/home/streaming/".$_GET["porta"]."/.ftpquota");
}

list($arquivos, $espaco_usado_bytes) = explode(" ",$ftpquota);

$espaco_usado = round($espaco_usado_bytes/1024/1024);

if($espaco_usado_bytes > 0) {
echo $espaco_usado;
}
?>
