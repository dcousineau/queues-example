<?php
require_once __DIR__ . '/common.php';

if (!file_exists('./thumbs/medium'))
    mkdir('./thumbs/medium', 0755, true);

$pheanstalk->watch('create_medium_thumb');

echo "Listening to 'create_medium_thumb' tube...\n";
while ($job = $pheanstalk->reserve()) {
    /** @var $job Pheanstalk_Job */

    echo " Received job {$job->getId()}\n";

    //Get embedded data from job
    $data = json_decode($job->getData(), true);

    $file = $data['file'];

    //Resize image
    if (!file_exists($file)) throw new Exception("Cannot find $file");

    $filename = pathinfo($file, PATHINFO_FILENAME);

    $image = new Imagick($file);

    $image = square_image($image);
    $image->cropThumbnailImage(400, 400);

    $image->setformat('jpg');
    $image->setcompressionquality(85);
    $image->writeimage("./thumbs/medium/$filename.jpg");

    //Delete job from beanstalkd queue as we completed successfully
    $pheanstalk->delete($job);

    echo "   Finished Job!\n";
}