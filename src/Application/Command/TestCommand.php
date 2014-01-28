<?php

namespace Application\Command;

class TestCommand extends BaseCommand
{
    /**
     * @return mixed
     */
    protected function start()
    {
        echo "hello world\n";
    }
}
