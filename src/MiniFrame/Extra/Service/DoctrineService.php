<?php

namespace MiniFrame\Extra\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use MiniFrame\BaseService;
use MiniFrame\Extra\Service\DoctrineService\DebugStack;

class DoctrineService extends BaseService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->entityManager == null) {
            $paths = $this->getConfigs()->getArray('doctrine.entity_path');
            $isDevMode = $this->getConfigs()->get('doctrine.is_dev_mode', false);
            $sqlLogEnable = $this->getConfigs()->get('doctrine.sql_log_enable', false);

            /**
             * @var PdoService $pdoService
             */
            $pdoService = $this->getApplication()->getService('pdo');
            $conn = array(
                'pdo' => $pdoService->getPdo()
            );

            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

            if ($sqlLogEnable) {
                $config->setSQLLogger($this->createSqlLogger());
            }
            $this->entityManager = EntityManager::create($conn, $config);
        }

        return $this->entityManager;
    }

    /**
     * @return DebugStack
     */
    protected function createsqlLogger()
    {
        /**
         * @var MonologService $monologService
         */
        $monologService = $this->getApplication()->getService('monolog');
        return new DebugStack($monologService->getSqlLogger());
    }
}
