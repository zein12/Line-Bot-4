<?php
$access_token = '3/cEBpOR0mjAMUtnHKrSrx3N6FnMVNPYfXBIwMO6HNGaljxuxTxZz2fGrmZYFwqfV3dvAWMa7FEGrmOONfbZ7or1wxYgpjbtFMS0Mkk+RftjvYSrUpThxAHGiivf2M662z2zM5P8BSKby0dJiBG3GQdB04t89/1O/w1cDnyilFU=';

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
