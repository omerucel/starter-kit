<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;

class PdoService extends BaseService
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        if ($this->pdo == null) {
            $dsn = $this->getConfigs()->get('pdo.dsn');
            $username = $this->getConfigs()->get('pdo.username');
            $password = $this->getConfigs()->get('pdo.password');
            $options = $this->getConfigs()->getArray('pdo.options', array());

            $pdo = new \PDO($dsn, $username, $password, $options);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }

        return $this->pdo;
    }
}
