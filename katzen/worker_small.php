<?php
require_once __DIR__ . '/common.php';

if (!file_exists('./thumbs/small'))
    mkdir('./thumbs/small', 0755, true);

$pheanstalk->watch('create_small_thumb');

echo "Listening to 'create_small_thumb' tube...\n";
while ($job = $pheanstalk->reserve()) {
    /** @var $job Pheanstalk_Job */
    
    echo " Received job {$job->getId()}\n";

    //Get embedded data from job
    $data = json_decode($job->getData(), true);

    $file = $data['file'];

    //Resize image
    if (!file_exists($file)) throw new Exception("Cannot find $file");

    $filename = pathinfo($file, PATHINFO_FILENAME);
    $extension = pathinfo($file, PATHINFO_EXTENSION);

    $image = new Imagick($file);

    $image = square_image($image);
    $image->cropThumbnailImage(100, 100);

    $image->setformat('jpg');
    $image->setcompressionquality(85);
    $image->writeimage("./thumbs/small/$filename.jpg");

    //Delete job from beanstalkd queue as we completed successfully
    $pheanstalk->delete($job);

    echo "   Finished Job!\n";
}