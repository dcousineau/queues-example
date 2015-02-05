<?php
require_once __DIR__ . '/common.php';

echo "Producing Jobs...\n";

foreach (new GlobIterator('./photos/*') as $f) {
    $jobId = $pheanstalk->useTube('create_small_thumb')
                        ->put(json_encode(array(
                            'file' => $f->getPathname(),
                        )));

    echo " * Small Thumb job ({$f->getPathname()}) successfully created, ID#$jobId\n";


    $jobId = $pheanstalk->useTube('create_medium_thumb')
                        ->put(json_encode(array(
                            'file' => $f->getPathname(),
                        )));

    echo " * Medium Thumb job successfully created, ID#$jobId\n";
}