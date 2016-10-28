<?php
$access_token = 'UjY+N6xBQrgchpjZB7IU0ck2aypFc37LrIEozsryFd9WZTdI0fNj8xFyi5RCMzJ9e1N/c3JQt8w0XBBw1I6x4ev2Gwkyl8SHPibsqTS1hzdK6MoPza9/lwcQgttNrZWXGJYNsGJi2/ZgQ5TFflEG9wdB04t89/1O/w1cDnyilFU=';
			$uid=5;
			$url = "http://www.osk130.com/gain/linebot.php?uid=".$uid."&text=test";
			//$log = file_get_contents($url);
			//echo $log;
	    $curlSession = curl_init();
	    curl_setopt($curlSession, CURLOPT_URL, $url);
	    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	    //$jsonData = json_decode(curl_exec($curlSession));
	    curl_close($curlSession);			
			// //Get user profile but can't search userId
			// //$userId = 'Udc964c94321d2db87bc8f17041ae37ea';
			// $userId = $_GET['uid'];
			// $url = 'https://api.line.me/v2/bot/profile/';
			// $headers = array('Authorization: Bearer ' . $access_token);
			// $ch = curl_init();
			// curl_setopt($ch, CURLOPT_URL, $url.$userId);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			// //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			// $result = curl_exec($ch);
			// curl_close($ch);
			// print_r($result);

?>