<?php
require('./vendor/autoload.php');

# Add your client ID and Secret
$client_id = "15920144987-kv28pssimksmbsnhhe1lvo7t8e8cdc1b.apps.googleusercontent.com";
$client_secret = "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";

$client = new Google\Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);

# redirection location is the path to login.php
$redirect_uri = 'http://localhost/medicine_front/login.php';
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");
