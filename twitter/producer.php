<?php
require_once __DIR__ . '/common.php';

echo "Producing Jobs...\n";

$screen_names = array(
    'phpdeveloper',
    'lonestarphp',
    'sunshinephp',
);

foreach ($screen_names as $screen_name) {
    $jobId = $pheanstalk->useTube('crawl')
                        ->put(json_encode(array(
                            'screen_name' => $screen_name
                        )));

    echo " * Job successfully created, ID#$jobId\n";
}