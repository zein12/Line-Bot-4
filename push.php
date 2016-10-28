<?php
$access_token = 'UjY+N6xBQrgchpjZB7IU0ck2aypFc37LrIEozsryFd9WZTdI0fNj8xFyi5RCMzJ9e1N/c3JQt8w0XBBw1I6x4ev2Gwkyl8SHPibsqTS1hzdK6MoPza9/lwcQgttNrZWXGJYNsGJi2/ZgQ5TFflEG9wdB04t89/1O/w1cDnyilFU=';

//			Get user profile but can't search userId
// 			$userId = 'Udc964c94321d2db87bc8f17041ae37ea';
// 			$url = 'https://api.line.me/v2/bot/profile/';
// 			$headers = array('Authorization: Bearer ' . $access_token);
// 			$ch = curl_init();
// 			curl_setopt($ch, CURLOPT_URL, $url.$userId);
// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
// 			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// 			$result = curl_exec($ch);
// 			curl_close($ch);
// 			print_r($result);

			if($_GET['food'] == 1) {
			 	if(rand(1,3) != 1) exit();
				// Bing connitive image search
				$key = '071e93df3d824296a6b86c0e2b85944b';
				$offset = rand(0,937);
				$q = "dessert";
				$url = 'https://api.cognitive.microsoft.com/bing/v5.0/images/search/?q='.$q.'&count=1&offset='.$offset;
				$headers = array('Ocp-Apim-Subscription-Key: ' . $key);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, '3');
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
	//			print_r($result);
				$data = json_decode($result, TRUE);
				$previewlink = str_replace('http:','https:',$data['value'][0]['thumbnailUrl']);
				$imglink = str_replace('http:','https:',$data['value'][0]['contentUrl']);
				$picname = $data['value'][0]['name'];
				$messages = [
					[
						"type" => "image",
						"originalContentUrl" => $imglink,
						"previewImageUrl" => $previewlink
					],
					[
						'type' => 'text',
						'text' => $picname			
					]
				];
			}
//			// Google custom search
// 			$q = "dessert";
// 			$startIndex = rand(1,7);
// 			$item = rand(1,5);
// 			$cx = '005020953349098262084:2vfju_au0ay';
// 			$json_url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyCAXPAkw9W7CH2p8LphNp9m9_lTQnDWaqw&cx=".$cx."&q=".$q."&start=".$startIndex."&searchType=image&alt=json";
// 			$json = file_get_contents($json_url);
// 			$data = json_decode($json, TRUE);
// 			//print_r($data);
// 			$imglink = $data['items'][$item]['link'];
// 			$imglink = str_replace('http:','https:',$imglink);
// 			$previewlink = $data['items'][$item]['image']['thumbnailLink'];
// 			$imglink = str_replace('http:','https:',$previewlink);
//  			$messages = [
// 				"type" => "image",
// 				"originalContentUrl" => $imglink,
// 				"previewImageUrl" => $previewlink
// 			];

			else {
			// Quote 
				$json_url = "http://api.forismatic.com/api/1.0/?method=getQuote&key=457653&format=json&lang=en";
				$json = file_get_contents($json_url);
				$data = json_decode($json, TRUE);	
				$ans = $data['quoteText'];
				$messages = [
					'type' => 'text',
					'text' => $ans
				];
			}
			// gain Udc964c94321d2db87bc8f17041ae37ea ,poptamonwan U21cd5ea03af3455f9eea4582c0ce6212
			$uid = "U21cd5ea03af3455f9eea4582c0ce6212";
			// Make a POST Request to Messaging API to push to user
			$url = 'https://api.line.me/v2/bot/message/push';
			if($_GET['food'] == 1) { 
				$data = [
					'to' => $uid,
					'messages' => $messages, 
				];
			} else {
				$data = [
					'to' => $uid,
					'messages' => [$messages], 
				];			
			}
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";

echo "OK";
?>
