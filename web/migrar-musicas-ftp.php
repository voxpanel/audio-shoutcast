<?php
header('Access-Control-Allow-Origin: *');

ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

// Fun��o para remover acentos
function remover_acentos($texto) {
$a = array("/[�����]/"=>"A","/[�����]/"=>"a","/[����]/"=>"E","/[����]/"=>"e","/[����]/"=>"I","/[����]/"=>"i","/[�����]/"=>"O",	"/[�����]/"=>"o","/[����]/"=>"U","/[����]/"=>"u","/�/"=>"c","/�/"=> "C");

return preg_replace(array_keys($a), array_values($a), $texto);
}

function remover_caracteres_especiais($texto) {

$characteres = array(
    '�'=> '', '�'=> '', '&'=> 'e', '�'=> '', '�'=> '', '$'=> '', '%'=> '',
	'�'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', 'ã'=> 'a', '('=> '',
	')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '',
	'...'=> '', '^'=> '', '~'=> '', '|'=> '', ','=> '',
	'<'=> '', '>'=> '', '{'=> '', '}'=> '',	'�'=> '', '�'=> '', '+'=> ''
);

return strtr($texto, $characteres);

}

// Fun��o para listar todas as m�sicas do FTP remoto
function listar_musicas($conn, $path, $max_level = 0){

    if($max_level < 0) return $files;
    if($path !== '/' && $path[strlen($path) - 1] !== '/') $path .= '/';
    $files_list = ftp_nlist($conn, $path);
    
    foreach($files_list as $f) {
        if($f !== '.' && $f !== '..' && $f !== $path) {
            if(strpos($f, '.') === FALSE) {
                $files .= listar_musicas($conn, $f, $max_level-1)."|";
            } else {
				if(preg_match('/.*\.mp3/', $f)) {
					if($path == '/') {
        				$files .= $f."|";
					} else {
						$files .= $path.$f."|";
					}
				}
                
            }    
        }
    }
    
    return $files;
}

// Conecta ao FTP remoto
$conexao = ftp_connect($_GET["servidor"]) or die("N�o foi poss�vel conectar-se ao servidor de FTP.<br>Verifique se esta online e se o endere�o informado esta correto.");
ftp_login($conexao, $_GET["usuario"], $_GET["senha"]) or die("N�o foi poss�vel autenticar-se no servidor de FTP.<br>Verifique se o usu�rio e senha est�o corretos.");
ftp_pasv($conexao,true);

$array_musicas = explode("|",listar_musicas($conexao, '/', 2));
$array_musicas = array_filter($array_musicas);

ftp_close($conexao);

// Cria a pasta para onde ser�o enviadas as m�sicas
mkdir("/home/streaming/".$_GET["porta"]."/Musicas_Migradas");

echo "======================================<br>";
echo "Total: ".count($array_musicas)."<br>";
echo "======================================<br><br>";

foreach($array_musicas as $musica) {

$musica_formatada = remover_caracteres_especiais(remover_acentos(substr(strrchr($musica, "/"), 1)));

// Faz download da m�sica
$curl = curl_init();
$fh   = fopen("/home/streaming/".$_GET["porta"]."/Musicas_Migradas/".$musica_formatada."", 'w');
curl_setopt($curl, CURLOPT_URL, "ftp://".$_GET["usuario"].":".$_GET["senha"]."@".$_GET["servidor"]."/".$musica."");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
fwrite($fh, $result);
fclose($fh);
curl_close($curl);

echo "".$musica_formatada." ........ OK<br>";

}

echo "<br>======================================<br>";
echo "Conclu�do! Done!<br>";
echo "======================================";
?>