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
            $pdoService = $this->getService('pdo');
            $conn = array(
                'pdo' => $pdoService->getPdo()
            );

            // TODO : Geliştirme ortamı haricinde önbellekleme aktifleştirilmeli.
            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

            if ($sqlLogEnable) {
                /**
                 * @var MonologService $monologService
                 */
                $monologService = $this->getService('monolog');
                $debugStack = new DebugStack($monologService->getSqlLogger());

                $config->setSQLLogger($debugStack);
            }
            $this->entityManager = EntityManager::create($conn, $config);
        }

        return $this->entityManager;
    }
}
