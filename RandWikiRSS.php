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
    private $lang = 'en';
    /**
     * @var string
     */
    private $engine = 'pedia';

    /**
     * @param array $config
     */
    function __construct(array $config)
    {
        $this->config = new \Oleku\SuperVarriable\Varriable($config);
        $this->setLang($this->config->default_language ?: $this->lang);
        $this->setEngine($this->config->default_engine ?: $this->engine);
    }

    /**
     * Inits storing object.
     * @throws Exception
     */
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
                $this->store = new Sqlite3Store($this->config->sqlite3, $this->getLang(), $this->getEngine());
                break;
//            case 'file':
//                $this->store = new FileStore($this->config->file);
//                break;
            default:
                throw new Exception('Unknown config.store_type.');
        }
    }

    /**
     * @param string $lang
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
     * @param string $engine
     */
    public function setEngine($engine)
    {
        if (!in_array($engine, array(
                'pedia',
                'quote',
            ))) {
            $engine = 'pedia';
        }
        $this->engine = $engine;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
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
