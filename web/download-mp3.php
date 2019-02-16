<?php
header('Access-Control-Allow-Origin: *');
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

// Função para formatar texto retirando acentos e caracteres especiais
function formatar_texto($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', 'Ð'=>'Dj','Z'=>'Z', 'z'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'f'=>'f', '¹'=> '', '²'=> '', '&'=> 'e',
        '³'=> '', '£'=> '', '$'=> '', '%'=> '', '¨'=> '', '§'=> '', 'º'=> '', 'ª'=> '', '©'=> '', 'Ã£'=> '',
        '('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', ' '=> '_',
        '['=> '', ']'=> '', '"'=> '', '.'=> '', '+'=> '', '/'=> '-'
);

return strtr($texto, $characteres);

}

function liveExecuteCommand($cmd)
{

    while (@ ob_end_flush());

    $proc = popen("$cmd 2>&1", 'r');

    $live_output     = "";
    $complete_output = "";

    while (!feof($proc))
    {
        $live_output     = fread($proc, 4096);
        $complete_output = $complete_output . $live_output;
        echo str_replace("/home/streaming/".$_GET["porta"]."/","",$live_output)."<br>";
        @ flush();
    }

    pclose($proc);

}

if($_GET["tipo"] == "mp3") { 

if(empty($_GET["nome"])) { 
die("Error!<br>Português: Você não informou um nome para o arquivo.<br>English: You did not enter a name for the file..<br>Español: No ha introducido un nombre para el archivo.");
}

$arquivo = formatar_texto(str_replace(".mp3","",$_GET["nome"])).".mp3";

$path_musica = "/home/streaming/".$_GET["porta"]."/".$_GET["pasta"]."/".$arquivo."";

liveExecuteCommand("/usr/bin/wget --no-check-certificate --tries=2 --timeout=30 --output-document='".$path_musica."' '".$_GET["url"]."'");

@shell_exec("/bin/chown streaming.streaming ".$path_musica."");

// Verifica se é um arquivo MP3 valido
if(!preg_match('/audio/i',mime_content_type("".$path_musica.""))) {

@unlink($path_musica);

die("Error!<br>Português: Tipo de arquivo inválido, somente MP3 é permitido.<br>English: Invalid file type, only MP3 is allowed.<br>Español: Tipo de archivo no válido, sólo se permite en MP3.");
}

echo "<span style=\"color: #009900;font-family: Geneva, Arial, Helvetica, sans-serif;font-size:11px;font-weight:normal;\">OK!<br>".$_GET["pasta"]."/".$arquivo."<br><br></span>";

} elseif($_GET["tipo"] == "rar") {

$path_stm_pasta = "/home/streaming/".$_GET["porta"]."/".str_replace("%20"," ",$_GET["pasta"])."/";
$path_rar = "/home/streaming/".$_GET["porta"]."/".str_replace("%20"," ",$_GET["pasta"])."/".$_GET["porta"]."_tmp_extract.rar";

liveExecuteCommand("/usr/bin/wget --no-check-certificate --tries=2 --timeout=30 --output-document='".$path_rar."' '".$_GET["url"]."'");

liveExecuteCommand("/usr/bin/unrar e '".$path_rar."' '*.mp3' '".$path_stm_pasta."'");

@shell_exec("/bin/chown -Rf streaming.streaming ".$path_stm_pasta."/*");

@unlink($path_rar);

echo "<span style=\"color: #009900;font-family: Geneva, Arial, Helvetica, sans-serif;font-size:11px;font-weight:normal;\">OK!</span>";

} else {

$path_stm_pasta = "/home/streaming/".$_GET["porta"]."/".str_replace("%20"," ",$_GET["pasta"])."/";
$path_zip = "/home/streaming/".$_GET["porta"]."/".str_replace("%20"," ",$_GET["pasta"])."/".$_GET["porta"]."_tmp_extract.zip";

liveExecuteCommand("/usr/bin/wget --no-check-certificate --tries=2 --timeout=30 --output-document='".$path_zip."' '".$_GET["url"]."'");

liveExecuteCommand("/usr/bin/unzip -o -d '".$path_stm_pasta."' '".$path_zip."' '*.mp3'");

@shell_exec("/bin/chown -Rf streaming.streaming ".$path_stm_pasta."/*");

@unlink($path_zip);

echo "<span style=\"color: #009900;font-family: Geneva, Arial, Helvetica, sans-serif;font-size:11px;font-weight:normal;\">OK!</span>";

}

?>