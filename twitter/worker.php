<?php
date_default_timezone_set('UTC');
require_once __DIR__ . '/common.php';

$pheanstalk->watch('crawl');
echo "Listening to 'crawl' tube...\n";

//Get 'all' collection
$collection = $db->all;

while ($job = $pheanstalk->reserve()) {
    /** @var $job Pheanstalk_Job */

    echo " Received job {$job->getId()}\n";
    
    //Decode job data
    $data = json_decode($job->getData(), true);
    
    echo " - Fetching tweets for {$data['screen_name']}\n";
    $statuses = $twitter->get('statuses/user_timeline', $data);
    
    foreach ($statuses as $tweet) {
        if ($collection->findOne(array('id_str' => $tweet->id_str)))
            continue; //Already have a copy of this tweet, skip
        
        //Normalize created_at for sorting purposes later
        $tweet->created_at_ts = strtotime($tweet->created_at);
        
        //Insert tweet into collection
        $collection->insert($tweet);
    }
    
    //Clear job from queue, we're finished with it
    echo " - Finished saving tweets\n";
    $pheanstalk->delete($job);
    
    //Sleep as naive throttling mechanism
    sleep(1);
}