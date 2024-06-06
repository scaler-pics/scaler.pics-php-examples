
<?php

require 'vendor/autoload.php';

use Scaler\Scaler;

/* 
Perform the tests from location with good upload and downnload speeds,
(for examle from your server environment) to get the best and real performance results.
*/

$apiKey = $argv[1] ?? '';
$scaler = new Scaler($apiKey, './results/access-token.txt');

$options = [
	'input' => ['localPath' => 'images/test.heic'],
	'output' => [
		'type' => 'jpeg',
		'fit' => ['width' => 1024, 'height' => 1024],
		'quality' => 0.8
	]
];

try {
	$result = $scaler->transform($options);
	echo 'result: ' . print_r($result, true);
} catch (Exception $e) {
	echo 'Error: ' . $e->getMessage();
	echo 'Stack trace: ' . $e->getTraceAsString() . "\n";
}
