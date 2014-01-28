<?php

namespace Application\Resource;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait MonologResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return \Monolog\Logger
     */
    public function getMonolog()
    {
        if (!ResourceMemory::hasKey('monolog')) {
            $monologConfigs = $this->getConfigs()['monolog'];

            $formatter = new LineFormatter(
                $monologConfigs['line_format'],
                $monologConfigs['datetime_format']
            );

            $stream = new StreamHandler(
                $monologConfigs['file'],
                $monologConfigs['level']
            );
            $stream->setFormatter($formatter);

            $monolog = new Logger($monologConfigs['name']);
            $monolog->pushHandler($stream);

            ResourceMemory::set('monolog', $monolog);
        }

        return ResourceMemory::get('monolog');
    }
}
