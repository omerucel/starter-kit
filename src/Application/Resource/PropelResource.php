<?php

namespace Application\Resource;

use Propel\Runtime\Propel;

trait PropelResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function getDBConnection()
    {
        $conn = Propel::getWriteConnection($this->getConfigs()['pdo']['dbname']);
        return $conn;
    }
}
