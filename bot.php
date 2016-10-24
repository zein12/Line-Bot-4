<?php
$access_token = 'UjY+N6xBQrgchpjZB7IU0ck2aypFc37LrIEozsryFd9WZTdI0fNj8xFyi5RCMzJ9e1N/c3JQt8w0XBBw1I6x4ev2Gwkyl8SHPibsqTS1hzdK6MoPza9/lwcQgttNrZWXGJYNsGJi2/ZgQ5TFflEG9wdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data

function get_content( $tag , $content )
{
	preg_match("/<".$tag."[^>]*>(.*?)<\/$tag>/si", $content, $matches);
	return $matches[1];
}
function shortenURL($url) {
	$data = [
		'longUrl' => $url
	];				
	$post = json_encode($data);
	$headers = array('Content-Type: application/json');
	$ch = curl_init("https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyD3kvBC7mMhyYC2nB9-SF6lMmgwKtQgPt8");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result, TRUE);
	return $data['id'];
}
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && ($event['message']['type'] == 'text' || $event['message']['type'] == 'sticker')) {
			// Get text sent
			$text = $event['message']['text'];
			$uid = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			
			$cmd = explode(" ",trim($text));
			$cmd[0] = strtolower($cmd[0]);
			if(strtolower($text) == "help") {
				$messages = [
					'type' => 'text',
					'text' => 'Gain Bot commands:
===============
1. search ... page(optional) 1,2,3 -> to search for websites
2. img ... page(optional) 1,2,3 -> to search images
3. th ... -> to translate a word/sentence to Thai
4. en ... -> to translate a word/sentence to English
5. shorten [url] -> to create Google short link
6. rand [min-max](optional) -> to random number from .. to ..
7. help -> to see all the commands
===============
Hope you enjoy :)'
				];						
			}
			else if($cmd[0] == "rand") {
				$text = substr(strstr($text," "), 1);
				if (strpos($text, "-") !== FALSE) { 
					$r = explode("-",$text);
					$text = rand($r[0],$r[1]);
				}
				else $text = rand(0,1) == true ? 'True' : 'False';
				$messages = [
					'type' => 'text',
					'text' => $text
				];			
			}			
			else if($cmd[0] == "shorten") {
				$text = substr(strstr($text," "), 1);
				$messages = [
					'type' => 'text',
					'text' => shortenURL($text)
				];			
			}
			else if($cmd[0] == "img") {
				$text = substr(strstr($text," "), 1);
				$key = '071e93df3d824296a6b86c0e2b85944b';
				if(strpos($text, "count") !== FALSE || strpos($text, "page") !== FALSE) {
					if(strpos($text, "count") > strpos($text, "page")) {
						if (strpos($text, "count") !== FALSE) { 
							preg_match('/count\s*(\d+)/', $text, $matches);
							$count = ($matches[1]-1)*$count;
							$text = substr($text, 0, strpos($text, "count"));
						}
						if (strpos($text, "page") !== FALSE) { 
							preg_match('/page\s*(\d+)/', $text, $matches);
							$offset = ($matches[1]-1)*$count;
							$text = substr($text, 0, strpos($text, "page"));
						}								
					}
					else {
						if (strpos($text, "page") !== FALSE) { 
							preg_match('/page\s*(\d+)/', $text, $matches);
							$offset = ($matches[1]-1)*$count;
							$text = substr($text, 0, strpos($text, "page"));
						}	
						if (strpos($text, "count") !== FALSE) { 
							preg_match('/count\s*(\d+)/', $text, $matches);
							$count = ($matches[1]-1)*$count;
							$text = substr($text, 0, strpos($text, "count"));
						}											
					}
				}
				else $offset = 0;
				$q = urlencode($text);				
				$url = 'https://api.cognitive.microsoft.com/bing/v5.0/images/search/?q='.$q.'&count='.$count."&offset=".$offset;
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
				//$contenturl = $data['value'][0]['hostPageUrl'];
				$websearch = $data['webSearchUrl'];
				$messages = [
					[
						'type' => 'text',
						'text' => 'Image Search: '.$text			
					],					
					[
						"type" => "image",
						"originalContentUrl" => $imglink,
						"previewImageUrl" => $previewlink
					],
					[
						'type' => 'text',
						'text' => $picname//."\n".$contenturl			
					],
					[
						'type' => 'text',
						'text' => "See more: ".shortenURL($websearch)					
					]
				];				
			}
			else if($cmd[0] == "search") {
				$text = substr(strstr($text," "), 1);
				$key = '071e93df3d824296a6b86c0e2b85944b';	
				$count = 3;
				if (strpos($text, "page") !== FALSE) { 
					preg_match('/page\s*(\d+)/', $text, $matches);
					$offset = ($matches[1]-1)*$count;
					$text = substr($text, 0, strpos($text, "page"));
				}
				else $offset = 0;
				$q = urlencode($text);				
				$url = 'https://api.cognitive.microsoft.com/bing/v5.0/search/?q='.$q.'&count='.$count."&offset=".$offset;
				$headers = array('Ocp-Apim-Subscription-Key: ' . $key);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, '3');
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($result, TRUE);
				$websearch = $data['webPages']['webSearchUrl'];
				if($data['webPages']['totalEstimatedMatches'] < $count) {
					$count = $data['webPages']['totalEstimatedMatches'];
				}
				$messages = [
					[
						'type' => 'text',
						'text' => 'Web Search: '.$text			
					],
					[
						'type' => 'text',
						'text' => "See more: ".shortenURL($websearch)					
					]
				];
				$i = $count;
				foreach (array_reverse($data['webPages']['value']) as $value) {
					if($i == 0) break;
					$name = $value['name'];
					$contenturl = $value['url'];
					$snippet = $value['snippet'];
					$a = [
						'type' => 'text',
						'text' => $i.".) ".$name."\n".$snippet."\n".shortenURL($contenturl)			
					];
					array_push($messages,$a);	
					$i--;
				}
			}
			else if($cmd[0] == "uid") {
				$ans = $uid;
				$messages = [
					'type' => 'text',
					'text' => $ans
				];
			}
			else if($cmd[0] == "th") {
				$text = substr(strstr($text," "), 1);
				$api = "trnsl.1.1.20161018T173244Z.c4506152f104a6d9.f6957b9addfa1b596f1808aaffd0749e656a7c22";
				$url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=".$api."&text=".$text."&lang=th";
				$ans = file_get_contents($url);
				$json_a = json_decode($ans,true);
				$ans = $json_a['text'][0];
				$messages = [
					'type' => 'text',
					'text' => $ans
				];				
			}
			else if($cmd[0] == "en") {
				$text = substr(strstr($text," "), 1);
				$api = "trnsl.1.1.20161018T173244Z.c4506152f104a6d9.f6957b9addfa1b596f1808aaffd0749e656a7c22";
				$url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=".$api."&text=".$text."&lang=en";
				$ans = file_get_contents($url);
				$json_a = json_decode($ans,true);
				$ans = $json_a['text'][0];
				$messages = [
					'type' => 'text',
					'text' => $ans
				];				
			}	
			else if ($event['message']['type'] == 'sticker') {
				$messages = [
					"type" => "sticker",
					"packageId" => "1",
					"stickerId" => rand(1,10)
				];			
			}
			else if($event['source']['type'] == 'user'){
				$input = str_replace("\\","", $text);
				$input = urlencode($input);
				$url = "http://www.pandorabots.com/pandora/talk-xml?botid=dd39ca224e3476a6&custid=".$uid."&input=".$input;
				$ans = get_content("that",file_get_contents($url));
				$ans = strip_tags(html_entity_decode($ans));
				$messages = [
					'type' => 'text',
					'text' => $ans
				];
			}
			else exit();
		
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			if($cmd[0] == "img" || $cmd[0] == "search") {			
				$data = [
					'replyToken' => $replyToken,
					'messages' => $messages,
				];
			}
			else {
				$data = [
					'replyToken' => $replyToken,
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
		}
	}
}
echo "OK";
?>
