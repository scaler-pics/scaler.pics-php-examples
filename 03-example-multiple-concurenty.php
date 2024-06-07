<?php

require 'vendor/autoload.php';
require '../scaler.pics-php/src/Scaler.php';

// use GuzzleHttp\Client;
// use GuzzleHttp\Pool;
use GuzzleHttp\Promise\Utils as PromiseUtils;
use Scaler\Scaler;

$apiKey = $argv[1] ?? '';
$scaler = new Scaler($apiKey, './results/access-token.txt');

$count = 2;

$promises = [];

for ($i = 0; $i < $count; $i++) {
	echo "Adding transform {$i}" . PHP_EOL;
	$promises[] = $scaler->transformAsync([
		'input' => ['localPath' => "images/test-" . ($i + 1) . ".heic"],
		'output' => [
			[
				'type' => 'jpeg',
				'fit' => ['width' => 1280, 'height' => 1280],
				'quality' => 0.8,
				'imageDelivery' => [
					'saveToLocalPath' => "results/output-{$i}-1280.jpeg",
				],
			],
			[
				'type' => 'jpeg',
				'fit' => ['width' => 1024, 'height' => 1024],
				'quality' => 0.8,
				'imageDelivery' => [
					'saveToLocalPath' => "results/output-{$i}-1024.jpeg",
				],
			],
			[
				'type' => 'jpeg',
				'fit' => ['width' => 512, 'height' => 512],
				'quality' => 0.8,
				'imageDelivery' => [
					'saveToLocalPath' => "results/output-{$i}-512.jpeg",
				],
			],
		],
	]);
}

$results = PromiseUtils::settle($promises)->wait();

//var_dump($results);

//$client = new Client();

// $requests = function ($count, $scaler) {
// 	for ($i = 0; $i < $count; $i++) {
// 		yield function () use ($scaler, $i) {
// 			return $scaler->transformAsync([
// 				'input' => ['localPath' => "images/test-" . ($i + 1) . ".heic"],
// 				'output' => [
// 					[
// 						'type' => 'jpeg',
// 						'fit' => ['width' => 1280, 'height' => 1280],
// 						'quality' => 0.8,
// 						'imageDelivery' => [
// 							'saveToLocalPath' => "results/output-{$i}-1280.jpeg",
// 						],
// 					],
// 					[
// 						'type' => 'jpeg',
// 						'fit' => ['width' => 1024, 'height' => 1024],
// 						'quality' => 0.8,
// 						'imageDelivery' => [
// 							'saveToLocalPath' => "results/output-{$i}-1024.jpeg",
// 						],
// 					],
// 					[
// 						'type' => 'jpeg',
// 						'fit' => ['width' => 512, 'height' => 512],
// 						'quality' => 0.8,
// 						'imageDelivery' => [
// 							'saveToLocalPath' => "results/output-{$i}-512.jpeg",
// 						],
// 					],
// 				],
// 			]);
// 		};
// 	}
// };

$timeStart = microtime(true);



// $pool = new Pool($client, $requests($count, $scaler), [
// 	'concurrency' => $count,
// 	'fulfilled' => function ($response, $index) {
// 		echo "response for index {$index}: " . json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;
// 	},
// 	'rejected' => function ($reason, $index) {
// 		echo "error for index {$index}: " . json_encode($reason, JSON_PRETTY_PRINT) . PHP_EOL;
// 	},
// ]);

// $promise = $pool->promise();
// $promise->wait();

$totalTime = microtime(true) - $timeStart;
echo "Total time for {$count} concurrent transforms: {$totalTime}" . PHP_EOL;
