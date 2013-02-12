<?php
date_default_timezone_set("America/Chicago");
require_once __DIR__ . '/common.php';

//Get 'all' collection
$collection = $db->all;

$count = $collection->find()->count();
echo "$count TOTAL TWEETS\n---\n";
foreach ($collection->find()->sort(array('created_at_ts' => -1))->limit(10) as $tweet) {
    $date = date('Y-m-d h:ia', strtotime($tweet['created_at']));
    
    echo "[$date] @{$tweet['user']['screen_name']}: {$tweet['text']}\n";
}