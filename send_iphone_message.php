<?php
function send2wall($message, $attachments, $token){
	if(!$message){
		echo "{'error':'Не заполнено поле сообщения!', 'error_code':3}";
		exit;
	}
	if(!$token){
		echo "{'error':'Не передан Access token', 'error_code':4}";
		exit;
	}
	if($attachments){
		$request = "https://api.vk.com/method/wall.post?message=".urlencode($message)."&attachments=".urlencode($attachments)."&access_token=".$token;
	} else {
		$request = "https://api.vk.com/method/wall.post?message=".urlencode($message)."&access_token=".$token;
	}
	$response = file_get_contents($request);
	$res = json_decode($response);
	if($res->{"error"}){
		setcookie("access_token", null);	
	}
	echo $response;
}
if($_POST["user"] && $_POST["pass"]){
	$response = file_get_contents("https://oauth.vk.com/token?grant_type=password&scope=wall&client_id=3140623&client_secret=VeWdmVclDCtn6ihuP1nt&username=".$_POST["user"]."&password=".$_POST["pass"]);	
	if(!$response){
		setcookie("access_token", null);
		echo "{'error':'Невозможно авторизоваться!','error_code':1}";
		exit;
	}
	$response = json_decode($response);
	$access_token = $response->{"access_token"};
	setcookie("access_token", $access_token, time()+3600);	
} elseif($_POST["access_token"]){
	$access_token = $_POST["access_token"];
	setcookie("access_token", $access_token, time()+3600);
} elseif($_COOKIE["access_token"]){ 
	$access_token = $_COOKIE["access_token"];
} else {
	echo "{'error':'Нету данных для авторизации!', 'error_code':2}";
	exit;
}
send2wall($_POST["message"], $_POST["attachments"], $access_token);

?>
