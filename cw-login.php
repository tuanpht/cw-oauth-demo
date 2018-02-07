<?php
require __DIR__ . '/vendor/autoload.php';

use ChatWork\OAuth2\Client\ChatWorkProvider;

$provider = new ChatWorkProvider(
    getenv('CW_CLIENT_ID'),
    getenv('CW_CLIENT_SECRET'),
    getenv('CW_REDIRECT_URI')
);

$url = $provider->getAuthorizationUrl([
    'scope' => [
        'users.profile.me:read', 
        'rooms.all:read_write',
    ],
]);

echo "<a href='$url'>Login with chatwork</a>";

