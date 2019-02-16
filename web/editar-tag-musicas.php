<?php
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

require_once("getid3/getid3.php");
require_once("getid3/write.php");

function change_mp3tag($filename, $title, $artist) {

    $getID3 = new getID3;
    $getID3->setOption(array('encoding' => 'UTF-8'));

    $tagwriter = new getid3_writetags;
	$tagwriter->filename = $filename; 
	$tagwriter->tagformats = array('id3v2.3'); 
    $tagwriter->overwrite_tags = true;
    $tagwriter->tag_encoding = 'UTF-8';
    $tagwriter->remove_other_tags = true;
	
	$TagData = array( 
    'title'         => array($title), 
    'artist'        => array($artist), 
	); 

    $tagwriter->tag_data = $TagData;

    if ($tagwriter->WriteTags()) {
        return 'OK';
    } else {
        return $tagwriter->errors[0];
    }
}

if(!empty($_GET["porta"]) && !empty($_GET["pasta"]) && !empty($_GET["titulo"]) && !empty($_GET["artista"])) {

$local = '/home/streaming/'.$_GET["porta"].'/'.$_GET["pasta"].'';

$dir = new DirectoryIterator($local);

foreach($dir as $file) {

if($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) == "mp3") {

$resultado = change_mp3tag(''.$local.'/'.$file->getFilename().'', $_GET["titulo"], $_GET["artista"]);

if($resultado == 'OK') {
echo "[OK] ".$file->getFilename()."<br>";
} else {
echo "<span style='color: #FF0000'>".$file->getFilename()." [".$resultado."]</span><br>";
}

}

}

}
?>