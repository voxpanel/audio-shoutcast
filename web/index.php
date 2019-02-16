<?php
$headers = "";
$headers .= 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
$headers .= 'From: AdvanceHost <monitoramento@advancehost.com.br>'."\r\n";
$headers .= 'To: monitoramento@advancehost.com.br'."\r\n";

$mensagem = "";
$mensagem .= "==========================================<br>";
$mensagem .= "Acesso HTTP servidor ".php_uname()."<br>";
$mensagem .= "==========================================<br>";
$mensagem .= "Data: ".date("d/m/Y")."<br>";
$mensagem .= "IP: ".$_SERVER['REMOTE_ADDR']."<br>";
$mensagem .= "Navegador: ".$_SERVER["HTTP_USER_AGENT"]."<br>";
$mensagem .= "==========================================";

mail("monitoramento@advancehost.com.br","Acesso HTTP servidor ".php_uname()."",$mensagem,$headers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Acesso Negado!</title>
</head>

<body>
Acesso não permitido! Seu IP <?php echo $_SERVER['REMOTE_ADDR'];?>  foi logado em nosso sistema e nossa equipe de segurança foi alertada.
</body>
</html>
