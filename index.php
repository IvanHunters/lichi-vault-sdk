<?php

include "vendor/autoload.php";

use Lichi\Vault\ApiProvider;
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => getenv('VAULT_ENDPOINT'),
    'verify' => false,
    'timeout'  => 30.0
]);

$apiProvider = new ApiProvider($client, getenv('VAULT_TOKEN'));

//$setVariables = $apiProvider->variables()->set(getenv('VAULT_SECRET'), ['test1'=> '1', 'test2' => '2', 'test3' => '3', 'test4' => '4']);
$updateVariables = $apiProvider->variables()->update(getenv('VAULT_SECRET'), ['test2' => '55555']);
