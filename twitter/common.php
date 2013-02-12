<?php
//Setup composer autoloader
require_once '../vendor/autoload.php';

//Connect to beanstalkd
$pheanstalk = new Pheanstalk_Pheanstalk('127.0.0.1');

//Connect to Twitter
$_keys = json_decode(file_get_contents(__DIR__ . '/keys.json'), true);
$twitter = new TwitterOAuth($_keys['consumer_key'], $_keys['consumer_secret'], $_keys['token'], $_keys['token_secret']);

//Connect to MongoDB
$mongo = new MongoClient();
$db = $mongo->tweets;