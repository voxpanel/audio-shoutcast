<?php
header('Access-Control-Allow-Origin: *');
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

// Funзгo para formatar texto retirando acentos e caracteres especiais
function formatar_texto($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', 'Р'=>'Dj', 'Z'=>'Z', 'z'=>'z', 'А'=>'A', 'Б'=>'A', 'В'=>'A', 'Г'=>'A', 'Д'=>'A',
    'Е'=>'A', 'Ж'=>'A', 'З'=>'C', 'И'=>'E', 'Й'=>'E', 'К'=>'E', 'Л'=>'E', 'М'=>'I', 'Н'=>'I', 'О'=>'I',
    'П'=>'I', 'С'=>'N', 'Т'=>'O', 'У'=>'O', 'Ф'=>'O', 'Х'=>'O', 'Ц'=>'O', 'Ш'=>'O', 'Щ'=>'U', 'Ъ'=>'U',
    'Ы'=>'U', 'Ь'=>'U', 'Э'=>'Y', 'Ю'=>'B', 'Я'=>'Ss', 'а'=>'a', 'б'=>'a', 'в'=>'a', 'г'=>'a', 'д'=>'a',
    'е'=>'a', 'ж'=>'a', 'з'=>'c', 'и'=>'e', 'й'=>'e', 'к'=>'e', 'л'=>'e', 'м'=>'i', 'н'=>'i', 'о'=>'i',
    'п'=>'i', 'р'=>'o', 'с'=>'n', 'т'=>'o', 'у'=>'o', 'ф'=>'o', 'х'=>'o', 'ц'=>'o', 'ш'=>'o', 'щ'=>'u',
    'ъ'=>'u', 'ы'=>'u', 'э'=>'y', 'э'=>'y', 'ю'=>'b', 'я'=>'y', 'f'=>'f', '№'=> '', 'І'=> '', '&'=> 'e',
    'і'=> '', 'Ј'=> '', '$'=> '', '%'=> '', 'Ё'=> '', '§'=> '', 'є'=> '', 'Є'=> '', '©'=> '', 'ГЈ'=> '',
    '('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', ' '=> '_',
    '['=> '', ']'=> '', '"'=> '', '+'=> '', '/'=> '-', ','=> ''
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
        echo str_replace("/home/streaming/".$_GET["porta"]."/","",$live_output)."<br><script>window.scrollBy(0,100);</script>";
        @ flush();
    }

    pclose($proc);

}
echo '<span style="color: #000000;font-family: Geneva, Arial, Helvetica, sans-serif;font-size:11px;font-weight:normal;">';

$arquivo = shell_exec("/usr/bin/python3 /usr/local/bin/youtube-dl --restrict-filenames --get-filename -o '%(title)s.%(ext)s' ".$_GET["url"]."");
$arquivo = formatar_texto(str_replace("\n","",$arquivo));

liveExecuteCommand("/usr/bin/python3 /usr/local/bin/youtube-dl --restrict-filenames --no-cache-dir --output '/home/streaming/".$_GET["porta"]."/".$_GET["pasta"]."/".$arquivo."' ".$_GET["url"]."");

@shell_exec("/bin/chown streaming.streaming /home/streaming/".$_GET["porta"]."/".$_GET["pasta"]."/".$arquivo."");

if(file_exists("/home/streaming/".$_GET["porta"]."/".$_GET["pasta"]."/".$arquivo."")) {
echo "<span style=\"color: #009900;font-family: Geneva, Arial, Helvetica, sans-serif;font-size:11px;font-weight:normal;\">OK!<br>".$_GET["pasta"]."/".$arquivo."<br></span>";
} else {
echo "<span style=\"color: #FF0000;font-family: Geneva, Arial, Helvetica, sans-serif;font-size:11px;font-weight:normal;\">Error! Por favor tente novamente. Please try again.<br></span>";
}

echo '</span><script>window.scrollBy(0,100);</script>';
?>