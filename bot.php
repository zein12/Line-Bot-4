<?php
$access_token = '3/cEBpOR0mjAMUtnHKrSrx3N6FnMVNPYfXBIwMO6HNGaljxuxTxZz2fGrmZYFwqfV3dvAWMa7FEGrmOONfbZ7or1wxYgpjbtFMS0Mkk+RftjvYSrUpThxAHGiivf2M662z2zM5P8BSKby0dJiBG3GQdB04t89/1O/w1cDnyilFU=';

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
			$text = $event['message']['text'];// Get text sent
			$uid = $event['source']['userId'];
			$replyToken = $event['replyToken'];// Get replyToken
//			$text = $_GET['text'];
			$cmd = explode(" ",trim($text));
			$cmd[0] = strtolower($cmd[0]);
			if(strtolower($text) == "help") {
				$messages = [
					'type' => 'text',
					'text' => 'perintah Bot:
===============
1. cari [keyword] halaman [no.] -> Untuk mencari websites
2. img [keyword] count [no.] page [no.] -> untuk mencari gambar
3. id [keyword] -> untuk menterjemahkan kata/kalimat ke bahasa indonesia
4. en [keyword] -> untuk menterjemahkan kata/kalimat ke bahasa inggris
5. shorten [url] -> untuk membuat Google short link
6. rand [min-max](optional) -> untuk acak nomer dari .. ke ..
7. : [query] -> untuk mendapat jawaban pintar
8. kickbot -> untuk kick bot dari room
9. color -> untuk acak palette
10. help -> untuk melihat semua command
===============
Semoga menyenangkan :)'
				];						
			}
			else if($text[0] == ":" && strlen($text) > 3) {
				$appid = 'QGUKYG-K5W2LKL6T6';
				$text = substr($text, 1);
	 			$url = "https://api.wolframalpha.com/v2/query?appid=".$appid."&input=".urlencode(trim($text));
				$result = file_get_contents($url);
				$result = simplexml_load_string($result);
				$i = 0;
				foreach ($result->pod as $value) {
					if($i > 0){
						$result = trim(str_replace("\n", ' - ', $value->subpod->plaintext));
						if($i == 1) $ans = $result;
						else $ans = $ans."\n".$result;
					}
					$i++;
				}
				if(empty($ans)) $ans = 'No result.';
				$messages = [
					'type' => 'text',
					'text' => str_replace('\:0e3f','฿',$ans)
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
				$count = 1;
				$offset = 0;
				if(substr($text, -1) == 'x') {
					$safeSearch = "&safeSearch=Off";
					$text = substr($text, 0, -1);
				} 
				else $safeSearch = '';
				if (strpos($text, "page") !== FALSE) { 
					preg_match('/page\s*(\d+)/', $text, $matches);
					$page = $matches[1];
					$offset = ($matches[1]-1)*$count;
					$text = substr($text, 0, strpos($text, "page"));
					$haspage = 1;
				}				
				if (strpos($text, "count") !== FALSE) { 
					preg_match('/count\s*(\d+)/', $text, $matches);
					$count = $matches[1];
					if($count > 4) $count = 4; // line msg max at 5
					if($haspage == 1) {
						$offset = ($page-1)*$count;
					}					
					$text = substr($text, 0, strpos($text, "count"));	
				}
				$q = urlencode($text);				
				$url = 'https://api.cognitive.microsoft.com/bing/v5.0/images/search/?q='.$q.'&count='.$count."&offset=".$offset.$safeSearch;
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
				echo $result;
				//$contenturl = $data['value'][0]['hostPageUrl'];
				$websearch = $data['webSearchUrl'];	
				$messages = [
					[
						'type' => 'text',
						'text' => 'Image Search: '.$text."\nSee more: ".shortenURL($websearch)
					]
				];
				$foundresult = 0;
				foreach ($data['value'] as $value) {
					$foundresult = 1;
					$previewlink = str_replace('http:','https:',$value['thumbnailUrl']);
					$imglink = str_replace('http:','https:',$value['contentUrl']);
					$picname = $value['name'];
					$a = [
						"type" => "image",
						"originalContentUrl" => $imglink,
						"previewImageUrl" => $previewlink
					];
					array_push($messages,$a);
					if($count == 1) {
						$b = [
							'type' => 'text',
							'text' => $picname//."\n".$contenturl			
						];
						array_push($messages,$b);
					}
				}
				if($foundresult == 0) {
					$b = [
						'type' => 'text',
						'text' => 'Result: Not found.'			
					];
					array_push($messages,$b);
				}
			}
			else if($cmd[0] == "search") {
				$text = substr(strstr($text," "), 1);
				$key = '071e93df3d824296a6b86c0e2b85944b';	
				$count = 3; // line msg size maximum at 5
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
				$foundresult = 0;
				foreach (array_reverse($data['webPages']['value']) as $value) {
					$foundresult = 1;
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
				if($foundresult == 0) {
					$b = [
						'type' => 'text',
						'text' => 'Result: Not found.'			
					];
					array_push($messages,$b);
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
				$url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=".$api."&text=".$text."&lang=id";
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
			else if($cmd[0] == "color") {
				$url = 'http://www.colourlovers.com/api/palettes/random';
				$result = file_get_contents($url);
				$result = simplexml_load_string($result);	
				$img = $result->palette->imageUrl;
				$img = str_replace('http:','https:',$img);	
				$messages = [
					[
						"type" => "image",
						"originalContentUrl" => $img,
						"previewImageUrl" => $img
					]
				];
				$text = $result->palette->url;
				foreach ($result->palette->colors->hex as $hex) {
					// $hex = "#ff9900";
					list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
					// echo "$hex -> $r $g $b";						
					$text = $text."\nHEX: ".$hex." RGB: ".$r.",".$g.",".$b;
				}
				$a = [
					'type' => 'text',
					'text' => $text
				];		
				array_push($messages,$a);				
			}	
			else if($event['source']['type'] == 'user'){
				if ($event['message']['type'] == 'sticker') {
					$messages = [
						"type" => "sticker",
						"packageId" => "1",
						"stickerId" => rand(1,10)
					];			
				}
				else if($cmd[0] == "tweet" && $uid == 'Udc964c94321d2db87bc8f17041ae37ea') {
					$text = substr(strstr($text," "), 1);
					$url = "http://jordrot.azurewebsites.net/tweet/?msg=".urlencode($text);
					$ans = file_get_contents($url);
					$messages = [
						'type' => 'text',
						'text' => $ans
					];
				}
				else {
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
			}
			else if($event['source']['type'] == 'room' && strtolower($text) == 'kickbot') {
				$roomId = $event['source']['roomId'];
				$url = 'https://api.line.me/v2/bot/room/'.$roomId.'/leave';
				$headers = array('Authorization: Bearer ' . $access_token);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "");
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
				exit("Leave room: ".$roomId);
			}
			else exit();
		
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			if($cmd[0] == "img" || $cmd[0] == "search" || $cmd[0] == "color") {			
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

			#log
			function send_remote_syslog($message, $component = "web", $program = "next_big_thing") {
			  $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			  foreach(explode("\n", $message) as $line) {
			    $syslog_message = "<22>" . date('M d H:i:s ') . $program . ' ' . $component . ': ' . $line;
			    socket_sendto($sock, $syslog_message, strlen($syslog_message), 0, 'logs4.papertrailapp.com', '41069');
			  }
			  socket_close($sock);
			}
			send_remote_syslog($event['message']['text'], $uid, "linebot");
		}
	}
}
echo "OK";
?>
