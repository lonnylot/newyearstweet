<?php

ini_set('display_errors', 1);
error_reporting(-1);

define('LIVE', false);

require 'helpers.php';

$pdo = new PDO('mysql:dbname=' . $_SERVER['DBNAME'] . ';host=' . $_SERVER['DBHOST'], $_SERVER['DBUSER'], $_SERVER['DBPASSWORD']);

$sql = $pdo->prepare('SELECT id, timezone FROM ' . $_SERVER['DBTABLE'] . ' WHERE sent = 0 AND timezone IS NOT NULL');
$sql->execute();

echo "Found " . $sql->rowCount() . " results.\n";

$now = new DateTime();
$fNow = $now->format('Y-m-d H:i:s');

while($result = $sql->fetch(PDO::FETCH_ASSOC)) {
	try {
		$tz = new DateTimeZone($result['timezone']);
	} catch (\Exception $e){
		continue;
	}
	$datetime = new DateTime('now', $tz);
	if($datetime->format('j') == 1 && $datetime->format('n') == 1 && $datetime->format('Y') == 2014){
		echo "[{$fNow}] Sending {$result['id']}\n";
		sendTweet($result['id']);
	} else {
		echo "[{$fNow}] Skipping {$result['id']}\n";
	}
}
