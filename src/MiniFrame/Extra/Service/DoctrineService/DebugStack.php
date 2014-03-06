<?php

namespace MiniFrame\Extra\Service\DoctrineService;

use Monolog\Logger;

class DebugStack extends \Doctrine\DBAL\Logging\DebugStack
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function stopQuery()
    {
        parent::stopQuery();

        if ($this->enabled) {
            $this->logger->debug(
                $this->queries[$this->currentQuery]['sql'],
                array(
                    'params' => $this->queries[$this->currentQuery]['params'],
                    'types' => $this->queries[$this->currentQuery]['types'],
                    'executionMS' => $this->queries[$this->currentQuery]['executionMS']
                )
            );
        }
    }
}
