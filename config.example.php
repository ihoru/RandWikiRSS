<?php

$CONFIG = array();
$CONFIG['title'] = 'Random article from Wikipedia.org';
$CONFIG['description'] = 'New article every day';
$CONFIG['url'] = 'https://github.com/ihoru/RandWikiRSS';
$CONFIG['image_url'] = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Rss-feed.svg/256px-Rss-feed.svg.png';
$CONFIG['default_language'] = 'ru';
$CONFIG['items_amount'] = 20; // Amount of articles to output
$CONFIG['article_period'] = '+1 day'; // strtotime format
$CONFIG['articles_amount'] = 1; // amount of random pages generated in a period
$CONFIG['store_type'] = 'sqlite3'; // mysql, sqlite3, file
$CONFIG['sqlite3'] = array(
    'dir'       => 'data/',
);
//$CONFIG['store_type'] = 'mysql'; // mysql, sqlite3, file
//$CONFIG['mysql'] = array(
//    'host'       => '127.0.0.1',
//    'port'       => '3306',
//    'user'       => 'root',
//    'password'   => '',
//    'db_name'    => 'test',
//    'table_name' => 'randwikirss',
//);
