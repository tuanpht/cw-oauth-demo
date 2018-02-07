<?php
require __DIR__ . '/vendor/autoload.php';

use ChatWork\OAuth2\Client\ChatWorkProvider;
use GuzzleHttp\Client;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;

echo 'Request vars: ';
print_r($_GET);

if (!empty($_GET['error'])) {
	die('User denied your app requests!');
}

if (empty($_GET['code'])) {
	die('Error: no code returned!');
}

$provider = new ChatWorkProvider(
	getenv('CW_CLIENT_ID'),
	getenv('CW_CLIENT_SECRET'),
	getenv('CW_REDIRECT_URI')
);

$accessToken = $provider->getAccessToken((string) new AuthorizationCode(), [
	'code' => $_GET['code'],
]);

echo 'access_token: ' . $accessToken->getToken() . PHP_EOL;

$resourceOwner = $provider->getResourceOwner($accessToken);
if ($accessToken->hasExpired()) {
	$refreshedAccessToken = $provider->getAccessToken((string) new RefreshToken(), [
		'refresh_token' => $accessToken->getRefreshToken()
	]);
}

echo 'Resource owner: ';
print_r($resourceOwner->toArray());

$client = new Client();
$response = $client->request('GET', 'https://api.chatwork.com/v2/rooms', [
	'headers' => [
		'Authorization' => sprintf('Bearer %s', $accessToken)
	]
]);

echo 'API my status: ';
print_r(json_decode((string) $response->getBody()));

