<?php
session_start();
require_once("twitteroauth/twitteroauth.php"); //Path to twitteroauth library
 
$twitteruser		= "envato"; // Change with your twitter username
$notweets			= 30;
$consumerkey		= "aZpZMYHD33nuYEcpqvHB9g";
$consumersecret		= "EyJKsMKckfETkRS5D27D1RK6OlwzCDWwWH5x6mpBPk";
$accesstoken		= "354054292-bUUl96QdXBkJzkKVuzPvh53pCFuS9kZyWyfUpddo";
$accesstokensecret	= "M9ppPjGV5Jgsfvp7ylYPL6KbGSeN8dT64jaNPHBHrc";
 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	return $connection;
}
  
$connection	= getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$tweets		= $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $twitteruser . "&count=" . $notweets);
 
echo json_encode($tweets);
?>