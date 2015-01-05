<?php

if (!file_exists('vendor/autoload.php')) {
    die('Install dependencies!');
}

if (!file_exists('config.php')) {
    die('Create config.php file!');
}

require_once('vendor/autoload.php');
require_once('RandWikiRSS.php');
require_once('Store.php');
require_once('config.php');

try {
    $cur_time = time();
    $request = new \Oleku\SuperVarriable\Varriable($_GET);
    $rwrss = new RandWikiRSS($CONFIG);
    if ($request->lang) {
        $rwrss->setLang($request->lang);
    }
    if ($request->engine) {
        $rwrss->setEngine($request->engine);
    }
    $list = $rwrss->store()->getList($rwrss->config()->items_amount);
    $last_article_time = 0;
    foreach ($list as $item) {
        $last_article_time = max($last_article_time, $item['time']);
    }
    $next_generation = strtotime($rwrss->config()->article_period, $last_article_time);
    if ($next_generation <= $cur_time) {
        $lang = $rwrss->getLang();
        $engine = $rwrss->getEngine();
        $lock_fn = 'data/load_data_' . $engine . '_' . $lang . '.lock';
        $fp = fopen($lock_fn, 'w+');
        if (!$fp || !flock($fp, LOCK_EX)) {
            throw new Exception('Lock failed.');
        }
        do {//for correct unlock
            $new_list = $rwrss->store()->getList($rwrss->config()->items_amount);
            if ($new_list && $list && $new_list[0] != $list[0]) {
                //data updated while waiting for lock
                $list = $new_list;
                break;
            }
            $wr = new WikiRandom($lang, false, $engine);
            $count = $rwrss->config()->articles_amount;
            $add_items = array();
            $max_queries = 10;
            while ($count > 0 && $max_queries > 0) {
                --$max_queries;
                $data = $wr->getBulkData(1, 5, 0, true);
                $item = $data[0];
                if (!$item['title'] || !$item['text'] || !$item['images']) {
                    continue;
                }
                $image = false;
                foreach ($item['images'] as $im) {
                    if (substr($im, -4) == '.png') continue;
                    $image = $im;
                    break;
                }
                if (!$image) continue;
                --$count;
                $item['text'] = strip_tags($item['text']);
                $add_items[] = array(
                    'page_id' => $item['page_id'],
                    'title' => $item['title'],
                    'image' => $image,
                    'link' => $item['url'],
                    'text' => $item['text'],
                );
            }
            if (!$data || !$add_items) {
                error_log(sprintf('RandWikiRSS: !$data || !$add_items: %s, %s', json_encode($data), json_encode($add_items)));
                break;
            }
            $last_article_time = $cur_time;
            $add_items = array_reverse($add_items);
            foreach ($add_items as $item) {
                $item['time'] = $cur_time;
                $id = $rwrss->store()->saveItem($item);
                if (!$id) {
                    continue;
                }
                $item['id'] = $id;
                array_unshift($list, $item);
            }
        } while(false);
        flock($fp, LOCK_UN);
        fclose($fp);
        unlink($lock_fn);
    }
} catch (Exception $e) {
    die($e->getMessage());
}

$feed = new \FeedWriter\RSS2();
$feed->setTitle($rwrss->config()->title);
$feed->setLink($rwrss->config()->url);
$feed->setDescription($rwrss->config()->description);
$feed->setImage($rwrss->config()->title, $rwrss->config()->url, $rwrss->config()->image_url);
$feed->setChannelElement('language', $rwrss->getLang());
$feed->setDate(date(DATE_RSS, $last_article_time));
$feed->setChannelElement('pubDate', date(DATE_RSS, $last_article_time));
$feed->addGenerator();

foreach ($list as $item) {
    $newItem = $feed->createNewItem();
    $newItem->setTitle($item['title']);
    $newItem->setLink($item['link']);
    $description = sprintf('<img src="%s" width="200" /><br /> ', $item['image']);
    $description .= strip_tags($item['text']);
    $newItem->setDescription($description);
    $newItem->setDate($item['time']);
    $newItem->setId($item['page_id'], false);
    $feed->addItem($newItem);
}
if ($request->debug) {
    echo $feed->generateFeed();
} else {
    header('Content-Type: charset=utf-8');
    $feed->printFeed();
}
