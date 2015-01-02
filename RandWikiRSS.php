<?php

/**
 * Class RandWikiRSS
 */
class RandWikiRSS
{
    /**
     * @var null|Oleku\SuperVarriable\Varriable
     */
    private $config = null;
    /**
     * @var MysqlStore|null
     */
    private $store = null;
    /**
     * @var string
     */
    private $lang = '';

    /**
     * @param array $config
     */
    function __construct(array $config)
    {
        $this->config = new \Oleku\SuperVarriable\Varriable($config);
        if (!$this->config->default_language) {
            throw new Exception('Wrong config.default_language param.');
        }
        $this->setLang($this->config->default_language);
    }

    private function storeInit()
    {
        if ($this->store) {
            return;
        }
        switch ($this->config->store_type) {
//            case 'mysql':
//                $this->store = new MysqlStore($this->config->mysql);
//                break;
            case 'sqlite3':
                $this->store = new Sqlite3Store($this->config->sqlite3, $this->lang);
                break;
//            case 'file':
//                $this->store = new FileStore($this->config->file);
//                break;
            default:
                throw new Exception('Unknown config.store_type.');
        }
    }

    /**
     * @param $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return null|\Oleku\SuperVarriable\Varriable
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * @return Store|null
     */
    public function store()
    {
        $this->storeInit();
        return $this->store;
    }
}
