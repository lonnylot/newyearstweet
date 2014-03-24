<?php

require 'twitteroauth-master/twitteroauth/twitteroauth.php';

function getTwitterConnection($oauth_token = null, $oauth_token_secret = null) {
	if($oauth_token !== null && $oauth_token_secret != null) {
		return new TwitterOAuth($_SERVER['TWITTER_CONSUMER_KEY'], $_SERVER['TWITTER_CONSUMER_SECRET'], $oauth_token, $oauth_token_secret);
	}

	if(isset($_SESSION['request_token']) && isset($_SESSION['request_token_secret'])){
		return new TwitterOAuth($_SERVER['TWITTER_CONSUMER_KEY'], $_SERVER['TWITTER_CONSUMER_SECRET'], $_SESSION['request_token'], $_SESSION['request_token_secret']);
	}

	if(isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])){
		return new TwitterOAuth($_SERVER['TWITTER_CONSUMER_KEY'], $_SERVER['TWITTER_CONSUMER_SECRET'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	}

	return new TwitterOAuth($_SERVER['TWITTER_CONSUMER_KEY'], $_SERVER['TWITTER_CONSUMER_SECRET']);
}

function getUser() {
	$user = [
		'id' => '',
		'oauth_token' => '',
		'oauth_token_secret' => '',
		'tweet' => '',
		'timezone' => '-4',
		'sent' => 0,
		'created' => null,
		'updated' => null
	];

	if(LIVE && isset($_SESSION['id'])) {
		$pdo = new PDO('mysql:dbname=' . $_SERVER['DBNAME'] . ';host=' . $_SERVER['DBHOST'], $_SERVER['DBUSER'], $_SERVER['DBPASSWORD']);

		$sql = $pdo->prepare('SELECT * FROM ' . $_SERVER['DBTABLE'] . ' WHERE id = ?');
		$sql->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
		$sql->execute();

		$result = $sql->fetch(PDO::FETCH_ASSOC);
		if($result !== false){
			$user = $result;
		}
	} else {
		clearSession();
	}

	return $user;
}

function setAccessToken($accessToken){
	clearSession();
	$_SESSION['oauth_token'] = $accessToken['oauth_token'];
	$_SESSION['oauth_token_secret'] = $accessToken['oauth_token_secret'];
}

function setRequestToken($requestToken) {
	$_SESSION['request_token'] = $requestToken['oauth_token'];
	$_SESSION['request_token_secret'] = $requestToken['oauth_token_secret'];
}

function saveCredentials($content){
	if(!LIVE){
		return;
	}
	$pdo = new PDO('mysql:dbname=' . $_SERVER['DBNAME'] . ';host=' . $_SERVER['DBHOST'], $_SERVER['DBUSER'], $_SERVER['DBPASSWORD']);

	$sql = $pdo->prepare(
		'INSERT INTO ' . $_SERVER['DBTABLE'] . ' SET id = ?, oauth_token = ?,' .
		' oauth_token_secret = ? ON DUPLICATE KEY UPDATE oauth_token = ?,' .
		' oauth_token_secret = ?'
	);
	$sql->bindValue(1, $content->id, PDO::PARAM_INT);
	$sql->bindValue(2, $_SESSION['oauth_token'], PDO::PARAM_STR);
	$sql->bindValue(3, $_SESSION['oauth_token_secret'], PDO::PARAM_STR);
	$sql->bindValue(4, $_SESSION['oauth_token'], PDO::PARAM_STR);
	$sql->bindValue(5, $_SESSION['oauth_token_secret'], PDO::PARAM_STR);
	$sql->execute();
	
	$_SESSION['id'] = $content->id;
}

function saveTweet($userID, $tweet, $timezone) {
	if(!LIVE){
		return;
	}
	$pdo = new PDO('mysql:dbname=' . $_SERVER['DBNAME'] . ';host=' . $_SERVER['DBHOST'], $_SERVER['DBUSER'], $_SERVER['DBPASSWORD']);

	$sql = $pdo->prepare(
		'UPDATE ' . $_SERVER['DBTABLE'] . ' SET tweet = ?, timezone = ?, updated = NOW() WHERE id = ?'
	);
	$sql->bindValue(1, $tweet, PDO::PARAM_STR);
	$sql->bindValue(2, $timezone, PDO::PARAM_STR);
	$sql->bindValue(3, $userID, PDO::PARAM_INT);
	$sql->execute();
}

function clearSession() {
	unset($_SESSION['oauth_token'], $_SESSION['oauth_token_secret'], $_SESSION['request_token'], $_SESSION['request_token_secret']);
}

function sendTweet($userID) {
	if(!LIVE){
		return;
	}
	$pdo = new PDO('mysql:dbname=' . $_SERVER['DBNAME'] . ';host=' . $_SERVER['DBHOST'], $_SERVER['DBUSER'], $_SERVER['DBPASSWORD']);

	$sql = $pdo->prepare('SELECT * FROM ' . $_SERVER['DBTABLE'] . ' WHERE id = ?');
	$sql->bindValue(1, $userID, PDO::PARAM_INT);
	$sql->execute();

	$user = $sql->fetch(PDO::FETCH_ASSOC);
	if($user == false){
		return;
	}

	if($user['sent']){
		return;
	}

	if($user['tweet'] == false){
		return;
	}

	$twitter = getTwitterConnection($user['oauth_token'], $user['oauth_token_secret']);
	if($twitter->token === null){
		return;
	}
	$twitter->post('https://api.twitter.com/1.1/statuses/update.json', array('status' => $user['tweet']));
	if($twitter->http_code == 200){
		$sql = $pdo->prepare('UPDATE ' . $_SERVER['DBTABLE'] . ' SET sent = 1 WHERE id = ?');
		$sql->bindValue(1, $userID, PDO::PARAM_INT);
		$sql->execute();
	}
}

