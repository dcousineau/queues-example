<?php
//Setup composer autoloader
require_once '../vendor/autoload.php';

//Connect to beanstalkd
$pheanstalk = new Pheanstalk\Pheanstalk('127.0.0.1');