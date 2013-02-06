<?php
require_once __DIR__ . '/common.php';

$pheanstalk->watch('testtube');

echo "Listening to 'testtube' tube...\n";
while ($job = $pheanstalk->reserve()) {
    /** @var $job Pheanstalk_Job */

    echo " Received job {$job->getId()}\n";

    $data = $job->getData();
    echo "   * $data\n";
    sleep(1);


    $pheanstalk->delete($job);

    echo "   Finished Job!\n";
}