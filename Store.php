<?php

abstract class Store
{
    protected $lang = null;
    protected $engine = null;
    function __construct(array $config, $lang, $engine)
    {
        $this->lang = $lang;
        $this->engine = $engine;
    }
    /**
     * @param int $number
     *
     * @return array
     */
    abstract public function getList($number = 0);

    /**
     * @param array $data
     *
     * @return int
     */
    abstract public function saveItem(array $data);
}

//class MysqlStore extends Store {}

class Sqlite3Store extends Store
{
    private $file = null;
    /**
     * @var SQLite3
     */
    private $dbh = null;
    function __construct(array $config, $lang, $engine)
    {
        parent::__construct($config, $lang, $engine);
        $file = $config['dir'].'store_'.$this->engine.'_'.$this->lang.'.db';
        $exists = file_exists($file);
        $this->dbh = new SQLite3($file);
        if (!$exists || !filesize($file)) {
            $this->dbh->exec(file_get_contents('sqlite3.sql'));
        }
    }
    function __destruct() {
        $this->dbh->close();
    }

    public function getList($number = 0)
    {
        $results = $this->dbh->query('SELECT * FROM randwikirss ORDER BY time DESC, id DESC LIMIT '.$number);
        $list = array();
        while ($row = $results->fetchArray()) {
            $list[] = $row;
        }
        return $list;
    }

    public function saveItem(array $data)
    {
        $stmt = $this->dbh->prepare('INSERT INTO randwikirss (page_id, title, image, link, text, time) VALUES (:page_id, :title, :image, :link, :text, :time)');
        $stmt->bindValue(':page_id', $data['page_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':title', $data['title'], SQLITE3_TEXT);
        $stmt->bindValue(':image', $data['image'], SQLITE3_TEXT);
        $stmt->bindValue(':link', $data['link'], SQLITE3_TEXT);
        $stmt->bindValue(':text', $data['text'], SQLITE3_TEXT);
        $stmt->bindValue(':time', $data['time'] ?: time(), SQLITE3_INTEGER);
        if (!$stmt->execute()) {
            return false;
        }
        return $this->dbh->lastInsertRowID();
    }
}

//class FileStore extends Store {}
