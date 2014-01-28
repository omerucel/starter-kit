<?php

namespace Application\Resource;

trait PdoResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return \PDO
     */
    public function getPDO()
    {
        if (!ResourceMemory::hasKey('pdo')) {
            $pdoConfigs = $this->getConfigs()['pdo'];

            $pdo = new \PDO(
                $pdoConfigs['dsn'],
                $pdoConfigs['username'],
                $pdoConfigs['password']
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            ResourceMemory::set('pdo', $pdo);
        }

        return ResourceMemory::get('pdo');
    }
}