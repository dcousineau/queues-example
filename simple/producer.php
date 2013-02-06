<?php
require_once __DIR__ . '/common.php';

echo "Producing Jobs...\n";

foreach (range(1, 50) as $i) {
    $jobId = $pheanstalk->useTube('testtube')
                        ->put(json_encode(array(
                            'payload' => 10
                        )));

    echo " * Job successfully created, ID#$jobId\n";
}