<?php
$access_token = 'UjY+N6xBQrgchpjZB7IU0ck2aypFc37LrIEozsryFd9WZTdI0fNj8xFyi5RCMzJ9e1N/c3JQt8w0XBBw1I6x4ev2Gwkyl8SHPibsqTS1hzdK6MoPza9/lwcQgttNrZWXGJYNsGJi2/ZgQ5TFflEG9wdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;

?>
