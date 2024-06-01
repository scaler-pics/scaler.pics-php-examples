<?php
require 'vendor/autoload.php';
require 'src/Scaler.php';

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Scaler\Scaler;

$apiKey = $argv[1] ?? '';

$scaler = new Scaler($apiKey, './test-data/access-token.txt');

$optionsList = [
	[
		'input' => [
			'remoteUrl' => 'https://example.com/image1.jpg',
		],
		'output' => [
			'fit' => 'cover',
			'type' => 'jpeg',
			'quality' => 80,
			'imageDelivery' => [
				'saveToLocalPath' => '/path/to/save/image1.jpg',
			],
		],
	],
	[
		'input' => [
			'remoteUrl' => 'https://example.com/image2.jpg',
		],
		'output' => [
			'fit' => 'contain',
			'type' => 'png',
			'quality' => 90,
			'imageDelivery' => [
				'saveToLocalPath' => '/path/to/save/image2.png',
			],
		],
	],
];

$transformImage = function ($options) use ($scaler) {
	try {
		return $scaler->transform($options);
	} catch (Exception $e) {
		return 'Error: ' . $e->getMessage();
	}
};

$client = new Client();
$requests = function ($optionsList) use ($transformImage) {
	foreach ($optionsList as $options) {
		yield function () use ($transformImage, $options) {
			return $transformImage($options);
		};
	}
};

$pool = new Pool($client, $requests($optionsList), [
	'concurrency' => 5,
	'fulfilled' => function ($response, $index) {
		echo "Image {$index} transformed successfully\n";
		print_r($response);
	},
	'rejected' => function ($reason, $index) {
		echo "Image {$index} failed to transform: {$reason}\n";
	},
]);

$promise = $pool->promise();
$promise->wait();
