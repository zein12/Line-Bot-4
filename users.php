<?php
$access_token = 'o4hK4jsIHPLGkGsK7Wc2ea8AFmdfbHd2EXrLZw6t3CxIXOM6/k1OMcMfDrZMYHuve1N/c3JQt8w0XBBw1I6x4ev2Gwkyl8SHPibsqTS1hzfLZwsERRY6KO6OUYNHsWak6JeV6XwEifi/5lkjwxrW2QdB04t89/1O/w1cDnyilFU=';

			//Get user profile but can't search userId
			//$userId = 'Udc964c94321d2db87bc8f17041ae37ea';
			$userId = $_GET['uid'];
			$url = 'https://api.line.me/v2/bot/profile/';
			$headers = array('Authorization: Bearer ' . $access_token);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url.$userId);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			print_r($result);

?>