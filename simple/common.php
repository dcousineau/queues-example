<?php
//Setup composer autoloader
require_once '../vendor/autoload.php';

//Connect to beanstalkd
$pheanstalk = new Pheanstalk_Pheanstalk('127.0.0.1');