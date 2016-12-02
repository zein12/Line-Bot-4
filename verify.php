<?php
$access_token = 'o4hK4jsIHPLGkGsK7Wc2ea8AFmdfbHd2EXrLZw6t3CxIXOM6/k1OMcMfDrZMYHuve1N/c3JQt8w0XBBw1I6x4ev2Gwkyl8SHPibsqTS1hzfLZwsERRY6KO6OUYNHsWak6JeV6XwEifi/5lkjwxrW2QdB04t89/1O/w1cDnyilFU=';

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
