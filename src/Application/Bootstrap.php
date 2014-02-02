<?php

namespace Application;

use Application\Resource\ConfigResource;
use Application\Resource\MonologResource;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;

class Bootstrap
{
    use ConfigResource;
    use MonologResource;

    /**
     * @param array $configs
     */
    public function __construct(array $configs = array())
    {
        $this->setConfigs($configs);
    }

    public function boot()
    {
        $configs = $this->getConfigs();

        AnnotationRegistry::registerAutoloadNamespaces(
            array(
                'Symfony\Component\Validator\Constraints' => $configs['base_path'] . '/vendor/symfony/validator',
                'Application' => $configs['base_path'] . '/src/'
            )
        );

        /**
         * TODO: Logger debug environment ile aktif hale gelmeli.
         * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface $serviceContainer
         */
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass($configs['pdo']['dbname'], 'mysql');
        //$serviceContainer->setLogger('defaultLogger', $this->getMonolog());
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration(
            array(
                'dsn'      => $configs['pdo']['dsn'],
                'user'     => $configs['pdo']['username'],
                'password' => $configs['pdo']['password'],
            )
        );
        $serviceContainer->setConnectionManager($configs['pdo']['dbname'], $manager);
        //Propel::getWriteConnection($configs['pdo']['dbname'])->useDebug(true);
    }
}
