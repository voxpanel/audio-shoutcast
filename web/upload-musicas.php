<?php
header('Access-Control-Allow-Origin: *');

ini_set("memory_limit", "1024M");
ini_set("max_execution_time", 1800);

$X_porta = $_POST["porta"];
$X_pasta = $_POST["pasta"];

// A list of permitted file extensions
$allowed = array('mp3');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['upl']['tmp_name'], '../'.$X_porta.'/'.$X_pasta.'/'.$_FILES['upl']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;

?>