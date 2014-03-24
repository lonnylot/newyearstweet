<?php
session_cache_limiter(false);
session_start();

define('LIVE', false);

require '../vendor/autoload.php';
require '../libs/helpers.php';

$app = new \Slim\Slim([
	'templates.path' => '../view',
	'log.writer' => new \Slim\LogWriter( fopen( '../slim.log', 'a' ) ),
	'mode' => 'production',
	'debug' => false,
	'log.enable' => true
]);

$app->get('/', function() use ($app) {
	$user = getUser();

	$app->etag(rand());
	$app->render('index.php', $user);
});

$app->post('/signin/twitter/', function() use ($app) {
	$twitter = getTwitterConnection();

	// Get temporary credentials
	$requestToken = $twitter->getRequestToken($_SERVER['TWITTER_OAUTH_CALLBACK']);

	switch($twitter->http_code) {
		case 200: {
			setRequestToken($requestToken);
			$url = $twitter->getAuthorizeURL($requestToken['oauth_token']);
			header('Location: ' . $url);
			break;
		}
		default: {
			$app->response()->setStatus($twitter->http_code);
			break;
		}
	}
});

$app->get('/auth/twitter(/?:garbage)', function($garbage=null) use ($app) {
	$twitter = getTwitterConnection();

	if( isset($_SESSION['request_token']) === false 
		|| $app->request->params('oauth_token') === null 
		|| $_SESSION['request_token'] != $app->request->params('oauth_token'))
	{
		$app->redirect('/');
		$app->stop();
	}

	if($app->request->params('oauth_verifier') !== null) {
		$accessToken = $twitter->getAccessToken($app->request->params('oauth_verifier'));
		
		if($twitter->http_code === 200) {
			setAccessToken($accessToken);
			$twitter = getTwitterConnection();
			$content = $twitter->get('account/verify_credentials');
			
			if($twitter->http_code === 200) {
				saveCredentials($content);
			}
		}
	}

	$app->redirect('/#step2');
});

$app->post('/new-years-tweet', function() use ($app) {
	$user = getUser();

	if(!$user['id']) {
		$app->redirect('/');
	}

	if($app->request->params('tweet') === null
		|| strlen($app->request->params('tweet')) < 1
		|| strlen($app->request->params('tweet')) > 140)
	{
		$app->flash('error', 'Please enter a tweet between 1 and 140 characters.');
		$app->redirect('/');
		$app->stop();
	}

	if($app->request->params('timezone') === null
		|| new DateTimeZone($app->request->params('timezone')) === false )
	{
		$app->flash('error', 'Please enter a valid timezone.');
		$app->redirect('/');
		$app->stop();
	}

	saveTweet($user['id'], $app->request->params('tweet'), $app->request->params('timezone'));
	$app->flash('success', 'Your tweet has been set and will be sent after midnight!');
	$app->redirect('/');
});

$app->get('/tweet', function() use ($app) {
	sendTweet(117306539);
});

$app->run();
