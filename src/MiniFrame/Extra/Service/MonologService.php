<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonologService extends BaseService
{
    /**
     * @var array
     */
    protected $loggers = array();

    /**
     * @return Logger
     */
    public function getDefaultLogger()
    {
        return $this->getLogger($this->getConfigs()->get('monolog.default_logger'));
    }

    /**
     * @return Logger
     */
    public function getSqlLogger()
    {
        return $this->getLogger('sql');
    }

    /**
     * @param $name
     * @return Logger
     */
    public function getLogger($name)
    {
        if (!isset($this->loggers[$name])) {
            $loggerConfigs = $this->getConfigs()->get('monolog.loggers.' . $name);

            $formatter = new LineFormatter($loggerConfigs->line_format, $loggerConfigs->datetime_format);
            $stream = new StreamHandler($loggerConfigs->file, $loggerConfigs->level);
            $stream->setFormatter($formatter);

            $logger = new Logger($name);
            $logger->pushHandler($stream);

            $this->loggers[$name] = $logger;
        }

        return $this->loggers[$name];
    }
}
