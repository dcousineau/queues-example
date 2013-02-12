Queues And The Giant beanstalkd
-------------------------------

This repository contains example code for the talk I gave on Queues and beanstalkd.

The 'simple' folder contains an absolutely bare bones worker architecture to demonstrate scaling with queues.

The 'twitter' folder contains a slightly more fleshed out exmaple of crawling Twitter user tweets.

To run the examples:

* Install beanstalkd & launch `beanstalkd`
* Install mongodb & launch `mongod` (for twitter example only)
* Run `php composer.phar install` to installd dependencies
* Run *n* copies of `worker.php` from a specificed example (ideally in separate terminal windows)
* Run `producer.php` to inject jobs, watch the log outputs from the previous `worker.php` instances

For the twitter example you will need to copy `/twitter/keys.json.dist` to `keys.json` and fill in the values from https://dev.twitter.com/apps